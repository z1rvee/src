#pragma once

// =============================================================================
// PUBG Mobile Global 4.7.0 (4.7.0 -- 64-Bit) SDK
// Copyright (C) 2026 Zervex RIYAZ. All Rights Reserved.
// Developed by: @Zervexcheat RIYAZ (TG: @RIYAZXERO | Channel: @RIYAZ_STUFF)
// GitHub: https://github.com/RIYAZXERO
// Generation Time: Thu Nov 12 22:34:19 2026

// =============================================================================

namespace SDK
{
//---------------------------------------------------------------------------
// Enums
//---------------------------------------------------------------------------

#ifdef PF_MAX
#undef PF_MAX
#endif

// Enum GameplayTasks.ETaskResourceOverlapPolicy
enum class ETaskResourceOverlapPolicy : uint8_t
{
	ETaskResourceOverlapPolicy__StartOnTop = 0,
	ETaskResourceOverlapPolicy__StartAtEnd = 1,
	ETaskResourceOverlapPolicy__ETaskResourceOverlapPolicy_MAX = 2
};


#ifdef PF_MAX
#undef PF_MAX
#endif

// Enum GameplayTasks.EGameplayTaskState
enum class EGameplayTaskState : uint8_t
{
	EGameplayTaskState__Uninitialized = 0,
	EGameplayTaskState__AwaitingActivation = 1,
	EGameplayTaskState__Paused     = 2,
	EGameplayTaskState__Active     = 3,
	EGameplayTaskState__Finished   = 4,
	EGameplayTaskState__EGameplayTaskState_MAX = 5
};


#ifdef PF_MAX
#undef PF_MAX
#endif

// Enum GameplayTasks.EGameplayTaskRunResult
enum class EGameplayTaskRunResult : uint8_t
{
	EGameplayTaskRunResult__Error  = 0,
	EGameplayTaskRunResult__Failed = 1,
	EGameplayTaskRunResult__Success_Paused = 2,
	EGameplayTaskRunResult__Success_Active = 3,
	EGameplayTaskRunResult__Success_Finished = 4,
	EGameplayTaskRunResult__EGameplayTaskRunResult_MAX = 5
};



//---------------------------------------------------------------------------
// Script Structs
//---------------------------------------------------------------------------

// ScriptStruct GameplayTasks.GameplayResourceSet
// 0x0002
struct FGameplayResourceSet
{
	unsigned char                                      UnknownData00[0x2];                                       // 0x0000(0x0002) MISSED OFFSET
};

}

