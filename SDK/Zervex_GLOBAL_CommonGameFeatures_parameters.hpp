#pragma once

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
// Parameters
//---------------------------------------------------------------------------

// Function CommonGameFeatures.ActorRepControlComponent.ToggleGroupedRepControlByUID
struct UActorRepControlComponent_ToggleGroupedRepControlByUID_Params
{
	int64_t                                            UID;                                                      // (Parm, ZeroConstructor, IsPlainOldData)
	int                                                ControlMark;                                              // (Parm, ZeroConstructor, IsPlainOldData)
	int                                                RepControlGroup;                                          // (Parm, ZeroConstructor, IsPlainOldData)
};

// Function CommonGameFeatures.ActorRepControlComponent.ShouldBlockRepByUID
struct UActorRepControlComponent_ShouldBlockRepByUID_Params
{
	int64_t                                            UID;                                                      // (Parm, ZeroConstructor, IsPlainOldData)
	int                                                RepControlGroup;                                          // (Parm, ZeroConstructor, IsPlainOldData)
	bool                                               ReturnValue;                                              // (Parm, OutParm, ZeroConstructor, ReturnParm, IsPlainOldData)
};

// Function CommonGameFeatures.ActorRepControlComponent.GetControlGroupData
struct UActorRepControlComponent_GetControlGroupData_Params
{
	int                                                RepControlGroup;                                          // (Parm, ZeroConstructor, IsPlainOldData)
	struct FRepControlGroupData                        OutData;                                                  // (Parm, OutParm)
	bool                                               ReturnValue;                                              // (Parm, OutParm, ZeroConstructor, ReturnParm, IsPlainOldData)
};

// Function CommonGameFeatures.RepControlActorBase.IsNetRelevantForCustomCheck
struct ARepControlActorBase_IsNetRelevantForCustomCheck_Params
{
	class AActor*                                      RealViewer;                                               // (ConstParm, Parm, ZeroConstructor, IsPlainOldData)
	class AActor*                                      ViewTarget;                                               // (ConstParm, Parm, ZeroConstructor, IsPlainOldData)
	struct FVector                                     SrcLocation;                                              // (ConstParm, Parm, OutParm, ReferenceParm, IsPlainOldData)
	bool                                               ReturnValue;                                              // (Parm, OutParm, ZeroConstructor, ReturnParm, IsPlainOldData)
};

}

