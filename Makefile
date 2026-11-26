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
            meenu.mm \
            KeySystem.mm \
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

before-stage::
	@if [ -f Skin.lua ]; then \
		python3 -c 'data = open("Skin.lua","rb").read(); open("SkinLua.h","w").write("static const unsigned char Skin_lua[] = {" + ",".join(str(b) for b in data) + "};\nstatic const unsigned int Skin_lua_len = " + str(len(data)) + ";\n")'; \
	else \
		printf "static const unsigned char Skin_lua[] = {0x2d,0x2d,0x20,0x65,0x6d,0x70,0x74,0x79};\nstatic const unsigned int Skin_lua_len = 8;\n" > SkinLua.h; \
	fi
