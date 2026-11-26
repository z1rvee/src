#pragma once

// =============================================================================
// PUBG Mobile Global 4.7.0 (4.7.0 -- 64-Bit) SDK
// Copyright (C) 2026 Zervex RIYAZ. All Rights Reserved.
// Developed by: @Zervexcheat RIYAZ (TG: @RIYAZXERO | Channel: @RIYAZ_STUFF)
// GitHub: https://github.com/RIYAZXERO
// Generation Time: Thu Nov 12 22:34:20 2026

// =============================================================================

namespace SDK
{
//---------------------------------------------------------------------------
// Classes
//---------------------------------------------------------------------------

// Class bp_plugin.bp_pluginBPLibrary
// 0x0000 (0x0028 - 0x0028)
class Ubp_pluginBPLibrary : public UBlueprintFunctionLibrary
{
public:

	static UClass* StaticClass()
	{
        static UClass *pStaticClass = 0;
        if (!pStaticClass)
            pStaticClass = UObject::FindClass("Class bp_plugin.bp_pluginBPLibrary");
		return pStaticClass;
	}


	static void bp_pluginSendEvent(const struct FString& jsonEventCmd);
	static int bp_pluginRequestInstallWidget(int wigetType, const struct FString& authInfo);
	static float bp_pluginLaunchMeemoFunction(const struct FString& Param);
	static bool bp_pluginIsInForeground();
	static int bp_pluginGetInstalledWidgetType();
};


}

