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
// Enums
//---------------------------------------------------------------------------

#ifdef PF_MAX
#undef PF_MAX
#endif

// Enum MainCity.ECarryBackAnimState
enum class ECarryBackAnimState : uint8_t
{
	ECarryBackAnimState__None      = 0,
	ECarryBackAnimState__CarryBack = 1,
	ECarryBackAnimState__BeCarryBack = 2,
	ECarryBackAnimState__ECarryBackAnimState_MAX = 3
};


#ifdef PF_MAX
#undef PF_MAX
#endif

// Enum MainCity.EHoldingHandsIKMode
enum class EHoldingHandsIKMode : uint8_t
{
	HH_Default                     = 0,
	HH_SIMPLEIK                    = 1,
	HH_MAX                         = 2
};


#ifdef PF_MAX
#undef PF_MAX
#endif

// Enum MainCity.ESeesawState
enum class ESeesawState : uint8_t
{
	Seesaw_None                    = 0,
	Seesaw_Idle                    = 1,
	Seesaw_InMatch                 = 2,
	Seesaw_EnterField              = 3,
	Seesaw_InBattle                = 4,
	Seesaw_InBattle_SpeedUp        = 5,
	Seesaw_Settle                  = 6,
	Seesaw_Finished                = 7,
	Seesaw_MAX                     = 8
};


#ifdef PF_MAX
#undef PF_MAX
#endif

// Enum MainCity.ESeesawSeatState
enum class ESeesawSeatState : uint8_t
{
	Seat_Idle                      = 0,
	Seat_Waiting                   = 1,
	Seat_Entering                  = 2,
	Seat_Entered                   = 3,
	Seat_Exiting                   = 4,
	Seat_MAX                       = 5
};



}

