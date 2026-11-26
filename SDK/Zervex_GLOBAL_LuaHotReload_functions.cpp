// =============================================================================
// PUBG Mobile Global 4.7.0 (4.7.0 -- 64-Bit) SDK
// Copyright (C) 2026 Zervex RIYAZ. All Rights Reserved.
// Developed by: @Zervexcheat RIYAZ (TG: @RIYAZXERO | Channel: @RIYAZ_STUFF)
// GitHub: https://github.com/RIYAZXERO
// Generation Time: Thu Nov 12 22:34:20 2026

// =============================================================================

#include "../SDK.hpp"

namespace SDK
{
//---------------------------------------------------------------------------
// Functions
//---------------------------------------------------------------------------

// Function LuaHotReload.LuaHotReloadHelper.OnLuaFileHotUpdate
// (Final, Native, Public)
// Parameters:
// struct FString                 NotifyMessage                  (Parm, ZeroConstructor)

void ULuaHotReloadHelper::OnLuaFileHotUpdate(const struct FString& NotifyMessage)
{
	static UFunction *pFunc = 0;
	if (!pFunc)
		pFunc  = UObject::FindObject<UFunction>("Function LuaHotReload.LuaHotReloadHelper.OnLuaFileHotUpdate");

	ULuaHotReloadHelper_OnLuaFileHotUpdate_Params params;
	params.NotifyMessage = NotifyMessage;

	auto flags = pFunc->FunctionFlags;
	pFunc->FunctionFlags |= 0x400;

	UObject *currentObj = (UObject *) this;
	currentObj->ProcessEvent(pFunc, &params);

	pFunc->FunctionFlags = flags;
}


}

