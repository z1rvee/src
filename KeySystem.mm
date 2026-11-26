#import <UIKit/UIKit.h>
#import <Foundation/Foundation.h>
#include <stdlib.h>

static NSString *const API_BASE_URL = @"https://newsrc.onrender.com";
static NSString *const API_VERIFY_ENDPOINT = @"https://newsrc.onrender.com/api/verify";
static NSTimeInterval const SYNC_INTERVAL_SECONDS = 10.0;

NSString *globalActiveKey = nil;
static NSTimeInterval globalRemainingSeconds = -1;
static BOOL globalIsLifetime = NO;
static NSTimer *syncTimer = nil;
static BOOL isHandlingBanOrUpdate = NO;

static NSString *getDeviceHWID(void) {
    NSString *uuid = [[[UIDevice currentDevice] identifierForVendor] UUIDString];
    return uuid ?: @"UNKNOWN_DEVICE_HWID";
}

static UIWindow *getKeyWindow(void) {
    for (UIWindowScene *scene in [UIApplication sharedApplication].connectedScenes) {
        if (scene.activationState == UISceneActivationStateForegroundActive) {
            for (UIWindow *window in scene.windows) {
                if (window.isKeyWindow) {
                    return window;
                }
            }
        }
    }
    return [UIApplication sharedApplication].windows.firstObject;
}

static NSString *formatRemainingTimeText(void) {
    if (globalIsLifetime) {
        return @"LIFETIME";
    }
    if (globalRemainingSeconds < 0) {
        return @"Bilinmir";
    }
    NSInteger total = (NSInteger)MAX(0.0, floor(globalRemainingSeconds));
    NSInteger days = total / 86400;
    total %= 86400;
    NSInteger hours = total / 3600;
    total %= 3600;
    NSInteger minutes = total / 60;
    NSInteger seconds = total % 60;

    if (days > 0) {
        return [NSString stringWithFormat:@"%ldg %ldh %ldm %lds", (long)days, (long)hours, (long)minutes, (long)seconds];
    }
    return [NSString stringWithFormat:@"%ldh %ldm %lds", (long)hours, (long)minutes, (long)seconds];
}

static void showStatusBanner(NSString *message, UIColor *bgColor) {
    dispatch_async(dispatch_get_main_queue(), ^{
        UIWindow *window = getKeyWindow();
        if (!window) return;

        UILabel *banner = [[UILabel alloc] initWithFrame:CGRectMake(20, 52, window.bounds.size.width - 40, 44)];
        banner.backgroundColor = bgColor;
        banner.textColor = [UIColor whiteColor];
        banner.textAlignment = NSTextAlignmentCenter;
        banner.font = [UIFont boldSystemFontOfSize:13];
        banner.numberOfLines = 2;
        banner.text = message;
        banner.layer.cornerRadius = 10;
        banner.clipsToBounds = YES;
        banner.alpha = 0.0;

        [window addSubview:banner];

        [UIView animateWithDuration:0.25 animations:^{
            banner.alpha = 1.0;
        } completion:^(BOOL finished) {
            dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(2.5 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
                [UIView animateWithDuration:0.25 animations:^{
                    banner.alpha = 0.0;
                } completion:^(BOOL finished) {
                    [banner removeFromSuperview];
                }];
            });
        }];
    });
}

// Açar dogru olanda ekranda 3 saniye qalan vaxti gosterir
static void showRemainingTimeForThreeSeconds(void) {
    dispatch_async(dispatch_get_main_queue(), ^{
        UIWindow *window = getKeyWindow();
        if (!window) return;

        UIView *box = [[UIView alloc] initWithFrame:CGRectMake(24, 56, window.bounds.size.width - 48, 76)];
        box.backgroundColor = [UIColor colorWithRed:0.08 green:0.08 blue:0.10 alpha:0.96];
        box.layer.cornerRadius = 12;
        box.layer.borderWidth = 1.2;
        box.layer.borderColor = [UIColor colorWithRed:0.90 green:0.22 blue:0.27 alpha:0.85].CGColor;
        box.alpha = 0.0;

        UILabel *lblTitle = [[UILabel alloc] initWithFrame:CGRectMake(12, 10, box.bounds.size.width - 24, 20)];
        lblTitle.text = @"ACAR AKTİVDİR · QALAN VAXT";
        lblTitle.textColor = [UIColor colorWithRed:1.0 green:0.32 blue:0.38 alpha:1.0];
        lblTitle.font = [UIFont boldSystemFontOfSize:12];
        lblTitle.textAlignment = NSTextAlignmentCenter;
        [box addSubview:lblTitle];

        UILabel *lblTime = [[UILabel alloc] initWithFrame:CGRectMake(12, 34, box.bounds.size.width - 24, 30)];
        lblTime.text = formatRemainingTimeText();
        lblTime.textColor = [UIColor whiteColor];
        lblTime.font = [UIFont monospacedSystemFontOfSize:17 weight:UIFontWeightBold];
        lblTime.textAlignment = NSTextAlignmentCenter;
        [box addSubview:lblTime];

        [window addSubview:box];

        [UIView animateWithDuration:0.2 animations:^{
            box.alpha = 1.0;
        }];

        __block NSInteger ticks = 0;
        NSTimer *liveTick = [NSTimer scheduledTimerWithTimeInterval:1.0 repeats:YES block:^(NSTimer * _Nonnull timer) {
            ticks += 1;
            if (!globalIsLifetime && globalRemainingSeconds > 0) {
                globalRemainingSeconds = MAX(0.0, globalRemainingSeconds - 1.0);
            }
            lblTime.text = formatRemainingTimeText();

            if (ticks >= 3) {
                [timer invalidate];
                [UIView animateWithDuration:0.25 animations:^{
                    box.alpha = 0.0;
                } completion:^(BOOL finished) {
                    [box removeFromSuperview];
                }];
            }
        }];
        [[NSRunLoop mainRunLoop] addTimer:liveTick forMode:NSRunLoopCommonModes];
    });
}

static void showFatalModalAndCrashAfterDelay(NSString *titleText, NSString *detailText, NSInteger delaySeconds) {
    dispatch_async(dispatch_get_main_queue(), ^{
        if (isHandlingBanOrUpdate) return;
        isHandlingBanOrUpdate = YES;

        [syncTimer invalidate];
        syncTimer = nil;

        UIWindow *window = getKeyWindow();
        if (!window) {
            abort();
        }

        UIView *blocker = [[UIView alloc] initWithFrame:window.bounds];
        blocker.backgroundColor = [[UIColor blackColor] colorWithAlphaComponent:0.92];
        [window addSubview:blocker];

        UIView *box = [[UIView alloc] initWithFrame:CGRectMake(0, 0, MIN(320.0, window.bounds.size.width - 36.0), 185)];
        box.center = blocker.center;
        box.backgroundColor = [UIColor colorWithRed:0.10 green:0.10 blue:0.13 alpha:1.0];
        box.layer.cornerRadius = 14;
        box.layer.borderWidth = 1.2;
        box.layer.borderColor = [UIColor colorWithRed:0.90 green:0.22 blue:0.27 alpha:0.85].CGColor;
        [blocker addSubview:box];

        UILabel *lblTitle = [[UILabel alloc] initWithFrame:CGRectMake(14, 20, box.bounds.size.width - 28, 30)];
        lblTitle.text = titleText;
        lblTitle.textColor = [UIColor colorWithRed:1.0 green:0.28 blue:0.34 alpha:1.0];
        lblTitle.font = [UIFont boldSystemFontOfSize:19];
        lblTitle.textAlignment = NSTextAlignmentCenter;
        [box addSubview:lblTitle];

        UILabel *lblSub = [[UILabel alloc] initWithFrame:CGRectMake(14, 58, box.bounds.size.width - 28, 56)];
        lblSub.text = detailText;
        lblSub.textColor = [UIColor whiteColor];
        lblSub.font = [UIFont systemFontOfSize:13 weight:UIFontWeightMedium];
        lblSub.numberOfLines = 3;
        lblSub.textAlignment = NSTextAlignmentCenter;
        [box addSubview:lblSub];

        UILabel *lblTimer = [[UILabel alloc] initWithFrame:CGRectMake(14, 126, box.bounds.size.width - 28, 34)];
        lblTimer.textColor = [UIColor lightGrayColor];
        lblTimer.font = [UIFont monospacedSystemFontOfSize:13 weight:UIFontWeightBold];
        lblTimer.textAlignment = NSTextAlignmentCenter;
        [box addSubview:lblTimer];

        __block NSInteger left = delaySeconds;
        lblTimer.text = [NSString stringWithFormat:@"%ld...", (long)left];

        NSTimer *countdown = [NSTimer scheduledTimerWithTimeInterval:1.0 repeats:YES block:^(NSTimer * _Nonnull timer) {
            left -= 1;
            if (left > 0) {
                lblTimer.text = [NSString stringWithFormat:@"%ld...", (long)left];
            } else {
                [timer invalidate];
                abort();
            }
        }];
        [[NSRunLoop mainRunLoop] addTimer:countdown forMode:NSRunLoopCommonModes];
    });
}

static void showStopOrUpdateNotice(NSString *titleText, NSString *bodyText, UIView *overlayView) {
    dispatch_async(dispatch_get_main_queue(), ^{
        UIWindow *window = getKeyWindow();
        if (!window) return;

        UIView *targetParent = overlayView ?: window;
        UILabel *notice = [[UILabel alloc] initWithFrame:CGRectMake(24, targetParent.bounds.size.height * 0.5 - 55, targetParent.bounds.size.width - 48, 110)];
        notice.backgroundColor = [UIColor colorWithRed:0.12 green:0.12 blue:0.15 alpha:0.98];
        notice.textColor = [UIColor whiteColor];
        notice.numberOfLines = 4;
        notice.textAlignment = NSTextAlignmentCenter;
        notice.font = [UIFont boldSystemFontOfSize:14];
        notice.layer.cornerRadius = 12;
        notice.layer.borderWidth = 1.0;
        notice.layer.borderColor = [UIColor colorWithRed:0.90 green:0.22 blue:0.27 alpha:0.7].CGColor;
        notice.text = [NSString stringWithFormat:@"%@\n%@", titleText, bodyText];
        [targetParent addSubview:notice];
    });
}

static void parseDurationFromJSON(NSDictionary *json) {
    if ([json[@"lifetime"] boolValue]) {
        globalIsLifetime = YES;
        globalRemainingSeconds = -1;
    } else if (json[@"remainingSeconds"] != nil && json[@"remainingSeconds"] != [NSNull null]) {
        globalIsLifetime = NO;
        globalRemainingSeconds = [json[@"remainingSeconds"] doubleValue];
    } else if (json[@"expiresAt"] != nil && json[@"expiresAt"] != [NSNull null]) {
        globalIsLifetime = NO;
        double expMs = [json[@"expiresAt"] doubleValue];
        double nowMs = [[NSDate date] timeIntervalSince1970] * 1000.0;
        globalRemainingSeconds = MAX(0.0, (expMs - nowMs) / 1000.0);
    }
}

static void sendKeyRequest(NSString *inputKey,
                           BOOL isBackgroundSync,
                           void (^completion)(NSDictionary * _Nullable json, NSError * _Nullable error)) {
    NSString *hwid = getDeviceHWID();
    NSURL *url = [NSURL URLWithString:API_VERIFY_ENDPOINT];

    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:url];
    request.HTTPMethod = @"POST";
    request.timeoutInterval = isBackgroundSync ? 6.0 : 12.0;
    [request setValue:@"application/x-www-form-urlencoded" forHTTPHeaderField:@"Content-Type"];
    [request setValue:API_BASE_URL forHTTPHeaderField:@"Referer"];

    NSString *postString = [NSString stringWithFormat:@"key=%@&hwid=%@&sync=%@",
                            [inputKey stringByAddingPercentEncodingWithAllowedCharacters:[NSCharacterSet URLQueryAllowedCharacterSet]],
                            [hwid stringByAddingPercentEncodingWithAllowedCharacters:[NSCharacterSet URLQueryAllowedCharacterSet]],
                            isBackgroundSync ? @"1" : @"0"];
    request.HTTPBody = [postString dataUsingEncoding:NSUTF8StringEncoding];

    NSURLSessionDataTask *task = [[NSURLSession sharedSession] dataTaskWithRequest:request completionHandler:^(NSData *data, NSURLResponse *response, NSError *error) {
        if (error || !data) {
            if (completion) completion(nil, error);
            return;
        }
        NSError *jsonError = nil;
        id obj = [NSJSONSerialization JSONObjectWithData:data options:0 error:&jsonError];
        if (jsonError || ![obj isKindOfClass:[NSDictionary class]]) {
            if (completion) completion(nil, jsonError);
            return;
        }
        if (completion) completion((NSDictionary *)obj, nil);
    }];
    [task resume];
}

static void startLiveKeySync(NSString *activeKey) {
    dispatch_async(dispatch_get_main_queue(), ^{
        [syncTimer invalidate];
        syncTimer = [NSTimer scheduledTimerWithTimeInterval:SYNC_INTERVAL_SECONDS repeats:YES block:^(NSTimer * _Nonnull timer) {
            if (!globalActiveKey || isHandlingBanOrUpdate) return;
            sendKeyRequest(globalActiveKey, YES, ^(NSDictionary * _Nullable json, NSError * _Nullable error) {
                if (!json) return;

                BOOL banned = [json[@"banned"] boolValue] || [[json[@"status"] description] isEqualToString:@"banned"];
                if (banned) {
                    NSString *msg = [json[@"message"] description].length > 0 ? [json[@"message"] description] : @"Acar bloklanib";
                    showFatalModalAndCrashAfterDelay(@"USERBANNED", msg, 3);
                    return;
                }

                BOOL updateMode = [json[@"updateMode"] boolValue] || [[json[@"status"] description] isEqualToString:@"update"];
                if (updateMode) {
                    NSString *msg = [json[@"message"] description].length > 0 ? [json[@"message"] description] : @"Sistem UPDATE rejimine kecdi";
                    showFatalModalAndCrashAfterDelay(@"UPDATE!!!", msg, 2);
                    return;
                }

                BOOL paused = [json[@"paused"] boolValue] || [[json[@"status"] description] isEqualToString:@"paused"];
                if (paused) {
                    NSString *msg = [json[@"message"] description].length > 0 ? [json[@"message"] description] : @"Acar muveqqeti dayandirilib";
                    showStatusBanner(msg, [UIColor systemOrangeColor]);
                    return;
                }

                parseDurationFromJSON(json);
            });
        }];
        [[NSRunLoop mainRunLoop] addTimer:syncTimer forMode:NSRunLoopCommonModes];
    });
}

static void verifyKeyWithServer(NSString *inputKey, UIView *overlayView, BOOL isAutoLogin) {
    sendKeyRequest(inputKey, NO, ^(NSDictionary * _Nullable json, NSError * _Nullable error) {
        if (error || !json) {
            if (!isAutoLogin) {
                showStatusBanner(@"Serverə qoşulmaq mümkün olmadı!", [UIColor systemRedColor]);
            }
            return;
        }

        BOOL banned = [json[@"banned"] boolValue] || [[json[@"status"] description] isEqualToString:@"banned"];
        if (banned) {
            NSString *msg = [json[@"message"] description].length > 0 ? [json[@"message"] description] : @"Bu acar bloklanib";
            showFatalModalAndCrashAfterDelay(@"USERBANNED", msg, 3);
            return;
        }

        BOOL updateMode = [json[@"updateMode"] boolValue] || [[json[@"status"] description] isEqualToString:@"update"];
        if (updateMode) {
            NSString *msg = [json[@"message"] description].length > 0 ? [json[@"message"] description] : @"Sistem hal-hazirda UPDATE rejimindedir";
            showStopOrUpdateNotice(@"UPDATE!!!", msg, overlayView);
            showStatusBanner(msg, [UIColor systemRedColor]);
            return;
        }

        BOOL paused = [json[@"paused"] boolValue] || [[json[@"status"] description] isEqualToString:@"paused"];
        if (paused) {
            NSString *msg = [json[@"message"] description].length > 0 ? [json[@"message"] description] : @"Acar muveqqeti dayandirilib";
            showStopOrUpdateNotice(@"PAUSED", msg, overlayView);
            showStatusBanner(msg, [UIColor systemOrangeColor]);
            return;
        }

        NSString *status = [json[@"status"] description];
        NSString *message = [json[@"message"] description].length > 0 ? [json[@"message"] description] : @"Acar aktivdir";

        if ([status isEqualToString:@"success"] || [status isEqualToString:@"active"]) {
            globalActiveKey = inputKey;
            [[NSUserDefaults standardUserDefaults] setObject:inputKey forKey:@"SavedLicenseKey"];
            [[NSUserDefaults standardUserDefaults] synchronize];

            parseDurationFromJSON(json);
            startLiveKeySync(inputKey);

            dispatch_async(dispatch_get_main_queue(), ^{
                [overlayView removeFromSuperview];
                showStatusBanner(message, [UIColor systemGreenColor]);
                showRemainingTimeForThreeSeconds();
                [[NSNotificationCenter defaultCenter] postNotificationName:@"StartHack" object:nil];
            });
        } else {
            if (!isAutoLogin) {
                showStatusBanner(message, [UIColor systemRedColor]);
            }
        }
    });
}

@interface KeyLoginHandler : NSObject
@property (nonatomic, weak) UITextField *keyField;
@property (nonatomic, weak) UIView *overlayView;
- (void)loginTapped;
- (void)copyHWIDTapped;
@end

@implementation KeyLoginHandler
- (void)loginTapped {
    NSString *keyText = [self.keyField.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    if (keyText.length == 0) {
        showStatusBanner(@"Zəhmət olmasa açar daxil edin!", [UIColor systemOrangeColor]);
        return;
    }
    verifyKeyWithServer(keyText, self.overlayView, NO);
}

- (void)copyHWIDTapped {
    [UIPasteboard generalPasteboard].string = getDeviceHWID();
    showStatusBanner(@"HWID kopyalandı!", [UIColor systemBlueColor]);
}
@end

static KeyLoginHandler *loginHandler = nil;

extern "C" void showKeyMenu(void) {
    dispatch_async(dispatch_get_main_queue(), ^{
        UIWindow *window = getKeyWindow();
        if (!window) return;

        NSString *savedKey = [[NSUserDefaults standardUserDefaults] stringForKey:@"SavedLicenseKey"];

        UIView *overlay = [[UIView alloc] initWithFrame:window.bounds];
        overlay.backgroundColor = [[UIColor blackColor] colorWithAlphaComponent:0.75];
        [window addSubview:overlay];

        UIView *card = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 310, 230)];
        card.center = overlay.center;
        card.backgroundColor = [UIColor colorWithRed:0.11 green:0.14 blue:0.20 alpha:1.0];
        card.layer.cornerRadius = 16;
        card.layer.borderWidth = 1.0;
        card.layer.borderColor = [UIColor colorWithRed:0.22 green:0.74 blue:0.97 alpha:0.5].CGColor;
        [overlay addSubview:card];

        UILabel *title = [[UILabel alloc] initWithFrame:CGRectMake(15, 16, 280, 26)];
        title.text = @"LİSENZİYA GİRİŞİ (HWID)";
        title.textColor = [UIColor whiteColor];
        title.font = [UIFont boldSystemFontOfSize:17];
        title.textAlignment = NSTextAlignmentCenter;
        [card addSubview:title];

        UITextField *textField = [[UITextField alloc] initWithFrame:CGRectMake(20, 58, 270, 42)];
        textField.backgroundColor = [UIColor colorWithRed:0.06 green:0.09 blue:0.14 alpha:1.0];
        textField.textColor = [UIColor whiteColor];
        textField.attributedPlaceholder = [[NSAttributedString alloc] initWithString:@"Açarınızı daxil edin..." attributes:@{NSForegroundColorAttributeName: [UIColor lightGrayColor]}];
        textField.layer.cornerRadius = 8;
        textField.textAlignment = NSTextAlignmentCenter;
        textField.autocapitalizationType = UITextAutocapitalizationTypeAllCharacters;
        if (savedKey) textField.text = savedKey;
        [card addSubview:textField];

        loginHandler = [[KeyLoginHandler alloc] init];
        loginHandler.keyField = textField;
        loginHandler.overlayView = overlay;

        UIButton *loginBtn = [UIButton buttonWithType:UIButtonTypeSystem];
        loginBtn.frame = CGRectMake(20, 114, 270, 42);
        loginBtn.backgroundColor = [UIColor colorWithRed:0.01 green:0.52 blue:0.78 alpha:1.0];
        [loginBtn setTitle:@"DAXİL OL" forState:UIControlStateNormal];
        [loginBtn setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
        loginBtn.titleLabel.font = [UIFont boldSystemFontOfSize:15];
        loginBtn.layer.cornerRadius = 8;
        [loginBtn addTarget:loginHandler action:@selector(loginTapped) forControlEvents:UIControlEventTouchUpInside];
        [card addSubview:loginBtn];

        UIButton *hwidBtn = [UIButton buttonWithType:UIButtonTypeSystem];
        hwidBtn.frame = CGRectMake(20, 168, 270, 36);
        hwidBtn.backgroundColor = [UIColor colorWithWhite:1.0 alpha:0.08];
        [hwidBtn setTitle:@"HWID Kopyala" forState:UIControlStateNormal];
        [hwidBtn setTitleColor:[UIColor lightGrayColor] forState:UIControlStateNormal];
        hwidBtn.titleLabel.font = [UIFont systemFontOfSize:13];
        hwidBtn.layer.cornerRadius = 8;
        [hwidBtn addTarget:loginHandler action:@selector(copyHWIDTapped) forControlEvents:UIControlEventTouchUpInside];
        [card addSubview:hwidBtn];

        if (savedKey.length > 0) {
            verifyKeyWithServer(savedKey, overlay, YES);
        }
    });
}
