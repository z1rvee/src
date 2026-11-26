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

// Enum Development.EImGuiWindowDisplayMode
enum class EImGuiWindowDisplayMode : uint8_t
{
	EImGuiWindowDisplayMode__Embedded = 0,
	EImGuiWindowDisplayMode__Standalone = 1,
	EImGuiWindowDisplayMode__EImGuiWindowDisplayMode_MAX = 2
};



//---------------------------------------------------------------------------
// Script Structs
//---------------------------------------------------------------------------

// ScriptStruct Development.PropertyItemData
// 0x0028
struct FPropertyItemData
{
	struct FString                                     PropertyName;                                             // 0x0000(0x0010) (ZeroConstructor)
	class UEditableTextBox*                            EditableTextBox;                                          // 0x0010(0x0008) (ExportObject, ZeroConstructor, InstancedReference, IsPlainOldData)
	class UWidget*                                     ContainerWidget;                                          // 0x0018(0x0008) (ExportObject, ZeroConstructor, InstancedReference, IsPlainOldData)
	class UButton*                                     NameButton;                                               // 0x0020(0x0008) (ExportObject, ZeroConstructor, InstancedReference, IsPlainOldData)
};

}

