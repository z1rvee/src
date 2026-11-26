#import <Foundation/Foundation.h>
#include <sys/sysctl.h>
#include <sys/types.h>
#include <unistd.h>
#include <errno.h>
#include <string.h>
#include <dlfcn.h>
#include <mach/mach.h>
#include <mach-o/dyld.h>
#include <substrate.h>

#ifndef PT_DENY_ATTACH
#define PT_DENY_ATTACH 31
#endif

#ifndef CS_OPS_STATUS
#define CS_OPS_STATUS 0
#endif

#ifndef CS_DEBUGGED
#define CS_DEBUGGED 0x10000000
#endif

extern "C" int csops(pid_t pid, unsigned int ops, void *useraddr, size_t usersize);
extern "C" int sysctlbyname(const char *name, void *oldp, size_t *oldlenp, void *newp, size_t newlen);

static int (*orig_ptrace)(int request, pid_t pid, caddr_t addr, int data) = NULL;
static int (*orig_sysctl)(int *name, u_int namelen, void *info, size_t *infosize, void *newinfo, size_t newinfosize) = NULL;
static int (*orig_sysctlbyname)(const char *name, void *oldp, size_t *oldlenp, void *newp, size_t newlen) = NULL;
static const char *(*orig_dyld_get_image_name)(uint32_t image_index) = NULL;
static const struct mach_header *(*orig_dyld_get_image_header)(uint32_t image_index) = NULL;
static int (*orig_dladdr)(const void *addr, Dl_info *info) = NULL;
static int (*orig_csops)(pid_t pid, unsigned int ops, void *useraddr, size_t usersize) = NULL;
static kern_return_t (*orig_task_get_special_port)(task_t task, int which_port, mach_port_t *special_port) = NULL;

static const char *const kFakeImagePath = "/usr/lib/system/libsystem_kernel.dylib";

static const char *const kBlockedImages[] = {
    "M7MD.dylib",
    "Bypass.dylib",
    "Substrate",
    "CydiaSubstrate",
    "frida",
    "cycript",
    "libhooker",
    "ellekit",
    "TweakInject",
    "Shadow",
    "Liberty",
    "A-Bypass",
    "FlyJB",
    "KernBypass"
};

static BOOL is_blocked_image_name(const char *path) {
    if (path == NULL || path[0] == '\0') {
        return NO;
    }
    size_t count = sizeof(kBlockedImages) / sizeof(kBlockedImages[0]);
    for (size_t i = 0; i < count; i++) {
        if (strcasestr(path, kBlockedImages[i]) != NULL) {
            return YES;
        }
    }
    return NO;
}

static int my_ptrace(int request, pid_t pid, caddr_t addr, int data) {
    if (request == PT_DENY_ATTACH) {
        return 0;
    }
    if (orig_ptrace == NULL) {
        errno = ENOSYS;
        return -1;
    }
    return orig_ptrace(request, pid, addr, data);
}

static int my_sysctl(int *name, u_int namelen, void *info, size_t *infosize, void *newinfo, size_t newinfosize) {
    if (orig_sysctl == NULL) {
        errno = ENOSYS;
        return -1;
    }

    int ret = orig_sysctl(name, namelen, info, infosize, newinfo, newinfosize);
    if (ret != 0) {
        return ret;
    }

    if (name != NULL && info != NULL && infosize != NULL && namelen >= 3) {
        if (*infosize >= sizeof(struct kinfo_proc) &&
            name[0] == CTL_KERN &&
            name[1] == KERN_PROC &&
            name[2] == KERN_PROC_PID) {
            struct kinfo_proc *kp = (struct kinfo_proc *)info;
            kp->kp_proc.p_flag &= ~P_TRACED;
        }
    }

    return ret;
}

static int my_sysctlbyname(const char *name, void *oldp, size_t *oldlenp, void *newp, size_t newlen) {
    if (orig_sysctlbyname == NULL) {
        errno = ENOSYS;
        return -1;
    }

    int ret = orig_sysctlbyname(name, oldp, oldlenp, newp, newlen);
    if (ret != 0) {
        return ret;
    }

    if (name != NULL && strcmp(name, "kern.proc.pid") == 0 && oldp != NULL && oldlenp != NULL) {
        if (*oldlenp >= sizeof(struct kinfo_proc)) {
            struct kinfo_proc *kp = (struct kinfo_proc *)oldp;
            kp->kp_proc.p_flag &= ~P_TRACED;
        }
    }

    return ret;
}

static const char *my_dyld_get_image_name(uint32_t image_index) {
    if (orig_dyld_get_image_name == NULL) {
        return NULL;
    }

    const char *image_name = orig_dyld_get_image_name(image_index);
    if (image_name == NULL) {
        return NULL;
    }

    if (is_blocked_image_name(image_name)) {
        return kFakeImagePath;
    }

    return image_name;
}

static const struct mach_header *my_dyld_get_image_header(uint32_t image_index) {
    if (orig_dyld_get_image_header == NULL) {
        return NULL;
    }

    if (orig_dyld_get_image_name != NULL) {
        const char *image_name = orig_dyld_get_image_name(image_index);
        if (is_blocked_image_name(image_name)) {
            return orig_dyld_get_image_header(0);
        }
    }

    return orig_dyld_get_image_header(image_index);
}

static int my_dladdr(const void *addr, Dl_info *info) {
    if (orig_dladdr == NULL) {
        return 0;
    }

    int ret = orig_dladdr(addr, info);
    if (ret != 0 && info != NULL && is_blocked_image_name(info->dli_fname)) {
        info->dli_fname = kFakeImagePath;
    }
    return ret;
}

static int my_csops(pid_t pid, unsigned int ops, void *useraddr, size_t usersize) {
    if (orig_csops == NULL) {
        errno = ENOSYS;
        return -1;
    }

    int ret = orig_csops(pid, ops, useraddr, usersize);
    if (ret == 0 && ops == CS_OPS_STATUS && useraddr != NULL && usersize >= sizeof(uint32_t)) {
        uint32_t *flags = (uint32_t *)useraddr;
        *flags &= ~CS_DEBUGGED;
    }
    return ret;
}

static kern_return_t my_task_get_special_port(task_t task, int which_port, mach_port_t *special_port) {
    if (orig_task_get_special_port == NULL) {
        return KERN_FAILURE;
    }

    if (which_port == TASK_EXCEPTION_PORT || which_port == TASK_BOOTSTRAP_PORT) {
        if (special_port != NULL) {
            *special_port = MACH_PORT_NULL;
        }
        return KERN_FAILURE;
    }

    return orig_task_get_special_port(task, which_port, special_port);
}

static void install_hook(void *target, void *replacement, void **original) {
    if (target == NULL || replacement == NULL || original == NULL) {
        return;
    }
    MSHookFunction(target, replacement, original);
}

static void *resolve_symbol(const char *name, void *fallback) {
    if (name != NULL) {
        void *sym = dlsym(RTLD_DEFAULT, name);
        if (sym != NULL) {
            return sym;
        }
    }
    return fallback;
}

__attribute__((constructor))
static void bypass_init(void) {
    @autoreleasepool {
        install_hook((void *)ptrace, (void *)my_ptrace, (void **)&orig_ptrace);
        install_hook((void *)sysctl, (void *)my_sysctl, (void **)&orig_sysctl);
        install_hook(resolve_symbol("sysctlbyname", (void *)sysctlbyname), (void *)my_sysctlbyname, (void **)&orig_sysctlbyname);

        install_hook(resolve_symbol("_dyld_get_image_name", (void *)&_dyld_get_image_name),
                     (void *)my_dyld_get_image_name,
                     (void **)&orig_dyld_get_image_name);

        install_hook(resolve_symbol("_dyld_get_image_header", (void *)&_dyld_get_image_header),
                     (void *)my_dyld_get_image_header,
                     (void **)&orig_dyld_get_image_header);

        install_hook(resolve_symbol("dladdr", (void *)dladdr),
                     (void *)my_dladdr,
                     (void **)&orig_dladdr);

        install_hook(resolve_symbol("csops", (void *)csops),
                     (void *)my_csops,
                     (void **)&orig_csops);

        install_hook(resolve_symbol("task_get_special_port", (void *)task_get_special_port),
                     (void *)my_task_get_special_port,
                     (void **)&orig_task_get_special_port);
    }
}
