#import <UIKit/UIKit.h>
#import <Foundation/Foundation.h>

@interface ImGuiDrawView : UIViewController
+ (void)showChange:(BOOL)o;
+ (BOOL)isMenuShowing;
@end

extern bool menu_open;

@interface MeenuController : NSObject
@property (nonatomic, strong) UIButton *circleButton;
@property (nonatomic, strong) UIView *meenuWindow;
- (void)onCircleTap;
- (void)openMeenuTapped;
- (void)closeMeenuTapped;
@end

static MeenuController *gMeenu = nil;

@implementation MeenuController

- (void)onCircleTap {
    BOOL nextState = !menu_open;
    menu_open = nextState;
    [ImGuiDrawView showChange:nextState];
}

- (void)openMeenuTapped {
    UIWindow *win = nil;
    for (UIWindowScene *scene in [UIApplication sharedApplication].connectedScenes) {
        if (scene.activationState == UISceneActivationStateForegroundActive) {
            for (UIWindow *w in scene.windows) {
                if (w.isKeyWindow) { win = w; break; }
            }
        }
    }
    if (!win) win = [UIApplication sharedApplication].windows.firstObject;
    if (!win) return;

    if (!self.meenuWindow) {
        UIView *box = [[UIView alloc] initWithFrame:CGRectMake(30, 110, 220, 110)];
        box.backgroundColor = [UIColor colorWithRed:0.06 green:0.06 blue:0.07 alpha:0.95];
        box.layer.cornerRadius = 12;
        box.layer.borderWidth = 0.0;

        UILabel *lbl = [[UILabel alloc] initWithFrame:CGRectMake(14, 14, 150, 24)];
        lbl.text = @"MEENU";
        lbl.textColor = [UIColor whiteColor];
        lbl.font = [UIFont boldSystemFontOfSize:14];
        [box addSubview:lbl];

        UIButton *closeBtn = [UIButton buttonWithType:UIButtonTypeSystem];
        closeBtn.frame = CGRectMake(164, 54, 42, 34);
        [closeBtn setTitle:@"X" forState:UIControlStateNormal];
        [closeBtn setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
        [closeBtn addTarget:self action:@selector(closeMeenuTapped) forControlEvents:UIControlEventTouchUpInside];
        [box addSubview:closeBtn];

        self.meenuWindow = box;
    }

    if (!self.meenuWindow.superview) {
        [win addSubview:self.meenuWindow];
    }
    [win bringSubviewToFront:self.meenuWindow];
    if (self.circleButton) {
        [win bringSubviewToFront:self.circleButton];
    }
}

- (void)closeMeenuTapped {
    [self.meenuWindow removeFromSuperview];
}

@end

extern "C" void showMeenu(void) {
    dispatch_async(dispatch_get_main_queue(), ^{
        if (!gMeenu) {
            gMeenu = [[MeenuController alloc] init];
        }
        [gMeenu openMeenuTapped];
    });
}

static void addBlackCircle(void) {
    dispatch_async(dispatch_get_main_queue(), ^{
        UIWindow *win = nil;
        for (UIWindowScene *scene in [UIApplication sharedApplication].connectedScenes) {
            if (scene.activationState == UISceneActivationStateForegroundActive) {
                for (UIWindow *w in scene.windows) {
                    if (w.isKeyWindow) { win = w; break; }
                }
            }
        }
        if (!win) win = [UIApplication sharedApplication].windows.firstObject;
        if (!win) {
            dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(1.5 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
                addBlackCircle();
            });
            return;
        }

        if (!gMeenu) {
            gMeenu = [[MeenuController alloc] init];
        }

        if (!gMeenu.circleButton) {
            CGFloat diameter = 48.0;
            UIButton *btn = [UIButton buttonWithType:UIButtonTypeCustom];
            btn.frame = CGRectMake(24.0, 56.0, diameter, diameter);
            btn.backgroundColor = [UIColor colorWithRed:0.0 green:0.0 blue:0.0 alpha:0.70];
            btn.layer.cornerRadius = diameter * 0.5;
            btn.layer.borderWidth = 0.0;
            btn.clipsToBounds = YES;
            [btn addTarget:gMeenu action:@selector(onCircleTap) forControlEvents:UIControlEventTouchUpInside];
            gMeenu.circleButton = btn;
        }

        if (!gMeenu.circleButton.superview) {
            [win addSubview:gMeenu.circleButton];
        }
        [win bringSubviewToFront:gMeenu.circleButton];
    });
}

__attribute__((constructor))
static void initMeenu(void) {
    dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(2.0 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
        addBlackCircle();
    });
}
