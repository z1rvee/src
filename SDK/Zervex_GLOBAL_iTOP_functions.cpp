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

// Function iTOP.FBHelper.DelayToSetAutoInitFacebookLog
// (Final, Native, Static, Public, BlueprintCallable)
// Parameters:
// bool                           IsAutoInit                     (Parm, ZeroConstructor, IsPlainOldData)

void UFBHelper::DelayToSetAutoInitFacebookLog(bool IsAutoInit)
{
	static UFunction *pFunc = 0;
	if (!pFunc)
		pFunc  = UObject::FindObject<UFunction>("Function iTOP.FBHelper.DelayToSetAutoInitFacebookLog");

	UFBHelper_DelayToSetAutoInitFacebookLog_Params params;
	params.IsAutoInit = IsAutoInit;

	auto flags = pFunc->FunctionFlags;
	pFunc->FunctionFlags |= 0x400;

	static auto defaultObj = StaticClass()->GetDefaultObject();
	defaultObj->ProcessEvent(pFunc, &params);

	pFunc->FunctionFlags = flags;
}


// Function iTOP.FBHelper.DelayToInitFacebookSDK
// (Final, Native, Static, Public, BlueprintCallable)
// Parameters:
// bool                           IsAutoInit                     (Parm, ZeroConstructor, IsPlainOldData)
// bool                           WithLaunchOption               (Parm, ZeroConstructor, IsPlainOldData)

void UFBHelper::DelayToInitFacebookSDK(bool IsAutoInit, bool WithLaunchOption)
{
	static UFunction *pFunc = 0;
	if (!pFunc)
		pFunc  = UObject::FindObject<UFunction>("Function iTOP.FBHelper.DelayToInitFacebookSDK");

	UFBHelper_DelayToInitFacebookSDK_Params params;
	params.IsAutoInit = IsAutoInit;
	params.WithLaunchOption = WithLaunchOption;

	auto flags = pFunc->FunctionFlags;
	pFunc->FunctionFlags |= 0x400;

	static auto defaultObj = StaticClass()->GetDefaultObject();
	defaultObj->ProcessEvent(pFunc, &params);

	pFunc->FunctionFlags = flags;
}


}

