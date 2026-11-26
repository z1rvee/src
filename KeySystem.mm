#import <UIKit/UIKit.h>
#import <Foundation/Foundation.h>

static NSString *const API_VERIFY_URL = @"https://sizin-saytiniz.com/index.php?api=verify";

NSString *globalActiveKey = nil;

NSString *getDeviceHWID() {
    NSString *uuid = [[[UIDevice currentDevice] identifierForVendor] UUIDString];
    return uuid ?: @"UNKNOWN_DEVICE_HWID";
}

UIWindow *getKeyWindow() {
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

void showStatusBanner(NSString *message, UIColor *bgColor) {
    dispatch_async(dispatch_get_main_queue(), ^{
        UIWindow *window = getKeyWindow();
        if (!window) return;

        UILabel *banner = [[UILabel alloc] initWithFrame:CGRectMake(20, 50, window.bounds.size.width - 40, 44)];
        banner.backgroundColor = bgColor;
        banner.textColor = [UIColor whiteColor];
        banner.textAlignment = NSTextAlignmentCenter;
        banner.font = [UIFont boldSystemFontOfSize:14];
        banner.text = message;
        banner.layer.cornerRadius = 10;
        banner.clipsToBounds = YES;
        banner.alpha = 0.0;

        [window addSubview:banner];

        [UIView animateWithDuration:0.3 animations:^{
            banner.alpha = 1.0;
        } completion:^(BOOL finished) {
            dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(2.5 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
                [UIView animateWithDuration:0.3 animations:^{
                    banner.alpha = 0.0;
                } completion:^(BOOL finished) {
                    [banner removeFromSuperview];
                }];
            });
        }];
    });
}

void verifyKeyWithServer(NSString *inputKey, UIView *overlayView) {
    NSString *hwid = getDeviceHWID();
    NSURL *url = [NSURL URLWithString:API_VERIFY_URL];
    
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:url];
    request.HTTPMethod = @"POST";
    [request setValue:@"application/x-www-form-urlencoded" forHTTPHeaderField:@"Content-Type"];

    NSString *postString = [NSString stringWithFormat:@"key=%@&hwid=%@",
                            [inputKey stringByAddingPercentEncodingWithAllowedCharacters:[NSCharacterSet URLQueryAllowedCharacterSet]],
                            [hwid stringByAddingPercentEncodingWithAllowedCharacters:[NSCharacterSet URLQueryAllowedCharacterSet]]];
    request.HTTPBody = [postString dataUsingEncoding:NSUTF8StringEncoding];

    NSURLSessionDataTask *task = [[NSURLSession sharedSession] dataTaskWithRequest:request completionHandler:^(NSData *data, NSURLResponse *response, NSError *error) {
        if (error || !data) {
            showStatusBanner(@"Serverə qoşulmaq mümkün olmadı!", [UIColor systemRedColor]);
            return;
        }

        NSError *jsonError = nil;
        NSDictionary *json = [NSJSONSerialization JSONObjectWithData:data options:0 error:&jsonError];
        
        if (jsonError || ![json isKindOfClass:[NSDictionary class]]) {
            showStatusBanner(@"Server cavabı oxunmadı!", [UIColor systemRedColor]);
            return;
        }

        NSString *status = json[@"status"];
        NSString *message = json[@"message"] ?: @"Naməlum cavab";

        if ([status isEqualToString:@"success"]) {
            globalActiveKey = inputKey;
            [[NSUserDefaults standardUserDefaults] setObject:inputKey forKey:@"SavedLicenseKey"];
            [[NSUserDefaults standardUserDefaults] synchronize];

            dispatch_async(dispatch_get_main_queue(), ^{
                [overlayView removeFromSuperview];
                showStatusBanner(message, [UIColor systemGreenColor]);
                [[NSNotificationCenter defaultCenter] postNotificationName:@"StartHack" object:nil];
            });
        } else {
            showStatusBanner(message, [UIColor systemRedColor]);
        }
    }];
    [task resume];
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
    verifyKeyWithServer(keyText, self.overlayView);
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
            verifyKeyWithServer(savedKey, overlay);
        }
    });
}
