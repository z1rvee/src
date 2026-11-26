export THEOS ?= $(HOME)/theos
THEOS_MAKE_PATH ?= $(THEOS)/makefiles

THEOS_DEVICE_IP = 127.0.0.1
ARCHS = arm64
TARGET = iphone:clang:latest:15.0

INSTALL_TARGET_PROCESSES = ShadowTrackerExtra

include $(THEOS)/makefiles/common.mk

TWEAK_NAME = ESP

SDK_CPP_FILES = $(wildcard SDK/*.cpp)
SDK_MM_FILES  = $(wildcard SDK/*.mm)

ESP_FILES = Engine.mm \
            Esp.mm \
            Menu.mm \
            $(SDK_CPP_FILES) \
            $(SDK_MM_FILES) \
            ESP/HeeeNoScreenShotView.m \
            ESP/HideFunsion.m \
            ESP/PubgLoad.mm \
            ESP/ini_rw.cpp \
            ESP/imgui/Tools.cpp \
            ESP/imgui/imgui.cpp \
            ESP/imgui/imgui_draw.cpp \
            ESP/imgui/imgui_impl_metal.mm \
            ESP/imgui/imgui_tables.cpp \
            ESP/imgui/imgui_widgets.cpp

ESP_CFLAGS = -fobjc-arc -w -I. -IESP -IESP/imgui -ISDK
ESP_CCFLAGS = -std=c++17 -fno-rtti -fno-exceptions -w -I. -IESP -IESP/imgui -ISDK

ESP_FRAMEWORKS = UIKit Foundation Metal MetalKit QuartzCore CoreGraphics CoreText Security AVFoundation
ESP_LIBRARIES = c++ z

include $(THEOS_MAKE_PATH)/tweak.mk
