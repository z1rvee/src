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

// Class PixUILog.PxLogMgr
// 0x0010 (0x0038 - 0x0028)
class UPxLogMgr : public UObject
{
public:
	struct FScriptMulticastDelegate                    LogDelegate;                                              // 0x0028(0x0010) (ZeroConstructor, InstancedReference, BlueprintAssignable)

	static UClass* StaticClass()
	{
        static UClass *pStaticClass = 0;
        if (!pStaticClass)
            pStaticClass = UObject::FindClass("Class PixUILog.PxLogMgr");
		return pStaticClass;
	}


	static void PxLogToggleSwitchLevel(EPxLogLevels eLogLevel, bool beOpen);
	static void PxLogToggleSwitchGroup(EPxLogGroups eLogGroup, bool beOpen);
	static void PxLogToggleForceLocalOutPut(bool beOpen);
	static void PxLogEnable(bool beEnable);
	static class UPxLogMgr* Get();
	static void DispatchLog(EPxLogGroups eLogGroup, EPxLogLevels eLogLevel, const struct FString& strLogContent);
};


}

