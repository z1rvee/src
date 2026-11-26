#pragma once
#import <Foundation/Foundation.h>

static NSString *const kKeyAuthSuccessNotification = @"KeyAuthSuccessNotification";

static inline BOOL IsKeyAuthenticated(void) {
    return [[NSUserDefaults standardUserDefaults] boolForKey:@"ESP_Key_Verified_v1"];
}

static inline void SetKeyAuthenticated(BOOL authenticated) {
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    BOOL previous = [defaults boolForKey:@"ESP_Key_Verified_v1"];
    [defaults setBool:authenticated forKey:@"ESP_Key_Verified_v1"];
    [defaults synchronize];

    if (authenticated && !previous) {
        dispatch_async(dispatch_get_main_queue(), ^{
            [[NSNotificationCenter defaultCenter] postNotificationName:kKeyAuthSuccessNotification object:nil];
        });
    }
}
