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

// Enum PixUILog.EPxLogLevels
enum class EPxLogLevels : uint8_t
{
	EPxLogLevels__Log              = 0,
	EPxLogLevels__Warning          = 1,
	EPxLogLevels__Error            = 2,
	EPxLogLevels__Debug            = 3,
	EPxLogLevels__Count            = 4,
	EPxLogLevels__EPxLogLevels_MAX = 5
};


#ifdef PF_MAX
#undef PF_MAX
#endif

// Enum PixUILog.EPxLogGroups
enum class EPxLogGroups : uint8_t
{
	EPxLogGroups__Core             = 0,
	EPxLogGroups__Plugin           = 1,
	EPxLogGroups__Script           = 2,
	EPxLogGroups__Profiler         = 3,
	EPxLogGroups__Count            = 4,
	EPxLogGroups__EPxLogGroups_MAX = 5
};



}

