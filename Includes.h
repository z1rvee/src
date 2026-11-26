#include "PUBG/core/MenuLoad/DrawView.h"
#include "PUBG/core/MenuLoad/Menu.h"

#include "../imgui/imgui.h"
#include "../imgui/imgui_internal.h"
#include "../imgui/imgui_impl_metal.h"

#import <Metal/Metal.h>
#import <MetalKit/MetalKit.h>
#import <Foundation/Foundation.h>
#import <dlfcn.h>
#include <vector>
#include <map>
#include <pthread.h>

#define SCREEN_WIDTH [UIScreen mainScreen].bounds.size.width
#define SCREEN_HEIGHT [UIScreen mainScreen].bounds.size.height
#define SCREEN_SCALE [UIScreen mainScreen].scale

extern ImVec2 MenuOrigin;
extern ImVec2 MenuSize;
extern bool MoveMenu;
