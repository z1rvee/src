#import <UIKit/UIKit.h>
#import <Foundation/Foundation.h>
#include "SkinLua.h"

extern bool MenDeal;

static BOOL IsESPKeyVerified(void) {
    return [[NSUserDefaults standardUserDefaults] boolForKey:@"ESP_Key_Verified_v1"];
}

static NSString *RunEmbeddedSkinLua(void) {
    if (!IsESPKeyVerified()) {
        return @"Əvvəlcə key təsdiqlənməlidir";
    }

    NSData *raw = [NSData dataWithBytes:Skin_lua length:Skin_lua_len];
    NSString *script = [[NSString alloc] initWithData:raw encoding:NSUTF8StringEncoding];
    if (!script || script.length == 0 || [script isEqualToString:@"-- empty"]) {
        return @"Skin.lua boşdur";
    }

    NSString *docsDir = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES).firstObject;
    NSString *outPath = [docsDir stringByAppendingPathComponent:@"Skin_loaded.lua"];
    NSError *err = nil;
    BOOL saved = [script writeToFile:outPath atomically:YES encoding:NSUTF8StringEncoding error:&err];
    if (!saved) {
        return err.localizedDescription ?: @"Yazma xətası";
    }

    return [NSString stringWithFormat:@"Skin.lua hazırdır (%lu bayt)", (unsigned long)raw.length];
}

@interface MeenuOverlayController : NSObject
@property (nonatomic, strong) UIButton *blackCircleButton;
@property (nonatomic, strong) UIView *meenuPopup;
@property (nonatomic, strong) UILabel *statusLabel;
- (void)handleBlackCircleTap;
- (void)openMeenuWindow;
- (void)closeMeenuWindow;
- (void)runSkinLuaAction;
- (void)syncWithKeyAuth;
@end

static MeenuOverlayController *gMeenuOverlay = nil;

@implementation MeenuOverlayController

- (instancetype)init {
    self = [super init];
    if (self) {
        [[NSNotificationCenter defaultCenter] addObserver:self
                                                 selector:@selector(onKeyAuthenticated)
                                                     name:@"KeyAuthSuccessNotification"
                                                   object:nil];
        [[NSNotificationCenter defaultCenter] addObserver:self
                                                 selector:@selector(openMeenuWindow)
                                                     name:@"MeenuOpenRequested"
                                                   object:nil];
    }
    return self;
}

- (UIWindow *)activeWindow {
    for (UIWindowScene *scene in [UIApplication sharedApplication].connectedScenes) {
        if (scene.activationState == UISceneActivationStateForegroundActive) {
            for (UIWindow *w in scene.windows) {
                if (w.isKeyWindow) return w;
            }
            if (scene.windows.firstObject) return scene.windows.firstObject;
        }
    }
    return [UIApplication sharedApplication].windows.firstObject;
}

- (void)syncWithKeyAuth {
    if (!IsESPKeyVerified()) {
        MenDeal = false;
        self.blackCircleButton.hidden = YES;
        [self.meenuPopup removeFromSuperview];
        return;
    }

    [self attachCircle];
    self.blackCircleButton.hidden = NO;
}

- (void)onKeyAuthenticated {
    [self syncWithKeyAuth];
    [self openMeenuWindow];
}

- (void)handleBlackCircleTap {
    if (!IsESPKeyVerified()) {
        MenDeal = false;
        [self.meenuPopup removeFromSuperview];
        return;
    }
    MenDeal = !MenDeal;
}

- (void)runSkinLuaAction {
    self.statusLabel.text = RunEmbeddedSkinLua();
    self.statusLabel.textColor = [UIColor colorWithRed:0.85 green:0.85 blue:0.85 alpha:1.0];
}

- (void)openMeenuWindow {
    if (!IsESPKeyVerified()) {
        MenDeal = false;
        [self.meenuPopup removeFromSuperview];
        return;
    }

    UIWindow *win = [self activeWindow];
    if (!win) return;

    if (!self.meenuPopup) {
        UIView *box = [[UIView alloc] initWithFrame:CGRectMake(32, 114, 240, 134)];
        box.backgroundColor = [UIColor colorWithRed:0.04 green:0.04 blue:0.04 alpha:0.96];
        box.layer.cornerRadius = 10;
        box.layer.borderWidth = 1.0;
        box.layer.borderColor = [UIColor colorWithWhite:0.18 alpha:1.0].CGColor;

        UILabel *title = [[UILabel alloc] initWithFrame:CGRectMake(14, 12, 160, 20)];
        title.text = @"MEENU";
        title.textColor = [UIColor whiteColor];
        title.font = [UIFont boldSystemFontOfSize:13];
        [box addSubview:title];

        UIButton *closeBtn = [UIButton buttonWithType:UIButtonTypeSystem];
        closeBtn.frame = CGRectMake(196, 8, 34, 28);
        [closeBtn setTitle:@"X" forState:UIControlStateNormal];
        [closeBtn setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
        closeBtn.titleLabel.font = [UIFont boldSystemFontOfSize:13];
        [closeBtn addTarget:self action:@selector(closeMeenuWindow) forControlEvents:UIControlEventTouchUpInside];
        [box addSubview:closeBtn];

        UIButton *runBtn = [UIButton buttonWithType:UIButtonTypeSystem];
        runBtn.frame = CGRectMake(14, 44, 212, 36);
        runBtn.backgroundColor = [UIColor colorWithWhite:0.12 alpha:1.0];
        runBtn.layer.cornerRadius = 6;
        [runBtn setTitle:@"RUN SKIN.LUA" forState:UIControlStateNormal];
        [runBtn setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
        runBtn.titleLabel.font = [UIFont boldSystemFontOfSize:12];
        [runBtn addTarget:self action:@selector(runSkinLuaAction) forControlEvents:UIControlEventTouchUpInside];
        [box addSubview:runBtn];

        UILabel *status = [[UILabel alloc] initWithFrame:CGRectMake(14, 90, 212, 30)];
        status.text = @"Hazırdır";
        status.textColor = [UIColor colorWithWhite:0.55 alpha:1.0];
        status.font = [UIFont systemFontOfSize:11];
        status.numberOfLines = 2;
        [box addSubview:status];
        self.statusLabel = status;

        self.meenuPopup = box;
    }

    if (!self.meenuPopup.superview) {
        [win addSubview:self.meenuPopup];
    }
    [win bringSubviewToFront:self.meenuPopup];
    if (self.blackCircleButton && !self.blackCircleButton.hidden) {
        [win bringSubviewToFront:self.blackCircleButton];
    }
}

- (void)closeMeenuWindow {
    [self.meenuPopup removeFromSuperview];
}

- (void)attachCircle {
    if (!IsESPKeyVerified()) {
        self.blackCircleButton.hidden = YES;
        return;
    }

    UIWindow *win = [self activeWindow];
    if (!win) {
        dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(1.5 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
            [self attachCircle];
        });
        return;
    }

    if (!self.blackCircleButton) {
        CGFloat size = 48.0;
        UIButton *btn = [UIButton buttonWithType:UIButtonTypeCustom];
        btn.frame = CGRectMake(24.0, 56.0, size, size);
        btn.backgroundColor = [UIColor colorWithRed:0.0 green:0.0 blue:0.0 alpha:0.70];
        btn.layer.cornerRadius = size * 0.5;
        btn.layer.borderWidth = 0.0;
        btn.clipsToBounds = YES;
        [btn addTarget:self action:@selector(handleBlackCircleTap) forControlEvents:UIControlEventTouchUpInside];
        self.blackCircleButton = btn;
    }

    self.blackCircleButton.hidden = NO;
    if (!self.blackCircleButton.superview) {
        [win addSubview:self.blackCircleButton];
    }
    [win bringSubviewToFront:self.blackCircleButton];
}

@end

extern "C" void MeenuOverlayOpen(void) {
    dispatch_async(dispatch_get_main_queue(), ^{
        if (!gMeenuOverlay) {
            gMeenuOverlay = [[MeenuOverlayController alloc] init];
        }
        [gMeenuOverlay syncWithKeyAuth];
        [gMeenuOverlay openMeenuWindow];
    });
}

__attribute__((constructor))
static void initMeenuOverlay(void) {
    dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(2.0 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
        if (!gMeenuOverlay) {
            gMeenuOverlay = [[MeenuOverlayController alloc] init];
        }
        [gMeenuOverlay syncWithKeyAuth];
        if (IsESPKeyVerified()) {
            [gMeenuOverlay openMeenuWindow];
        }
    });
}
