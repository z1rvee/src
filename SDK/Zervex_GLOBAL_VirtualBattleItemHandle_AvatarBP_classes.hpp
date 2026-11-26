#pragma once

// =============================================================================
// PUBG Mobile Global 4.7.0 (4.7.0 -- 64-Bit) SDK
// Copyright (C) 2026 Zervex RIYAZ. All Rights Reserved.
// Developed by: @Zervexcheat RIYAZ (TG: @RIYAZXERO | Channel: @RIYAZ_STUFF)
// GitHub: https://github.com/RIYAZXERO
// Generation Time: Thu Nov 12 22:34:24 2026

// =============================================================================

namespace SDK
{
//---------------------------------------------------------------------------
// Classes
//---------------------------------------------------------------------------

// BlueprintGeneratedClass VirtualBattleItemHandle_AvatarBP.VirtualBattleItemHandle_AvatarBP_C
// 0x000E (0x0BA0 - 0x0B92)
class UVirtualBattleItemHandle_AvatarBP_C : public UBattleItemHandle_AvatarBP_C
{
public:
	unsigned char                                      UnknownData00[0x6];                                       // 0x0B92(0x0006) MISSED OFFSET
	struct FPointerToUberGraphFrame                    UberGraphFrame;                                           // 0x0B98(0x0008) (Transient, DuplicateTransient)

	static UClass* StaticClass()
	{
        static UClass *pStaticClass = 0;
        if (!pStaticClass)
            pStaticClass = UObject::FindClass("BlueprintGeneratedClass VirtualBattleItemHandle_AvatarBP.VirtualBattleItemHandle_AvatarBP_C");
		return pStaticClass;
	}


	void GetWrapperClass(class UClass** WrapperClass);
	void Constuct(struct FItemDefineID* InDefineID);
	void ExecuteUbergraph_VirtualBattleItemHandle_AvatarBP(int EntryPoint);
};


}

