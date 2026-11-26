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

// BlueprintGeneratedClass VirtualBackpackEmoteHandleBase_BP.VirtualBackpackEmoteHandleBase_BP_C
// 0x0000 (0x01F0 - 0x01F0)
class UVirtualBackpackEmoteHandleBase_BP_C : public UBackpackEmoteHandle
{
public:

	static UClass* StaticClass()
	{
        static UClass *pStaticClass = 0;
        if (!pStaticClass)
            pStaticClass = UObject::FindClass("BlueprintGeneratedClass VirtualBackpackEmoteHandleBase_BP.VirtualBackpackEmoteHandleBase_BP_C");
		return pStaticClass;
	}


	struct FBattleItemData ExtractItemData();
	bool HandleDisuse(EBattleItemDisuseReason* Reason);
	bool HandleUse(struct FBattleItemUseTarget* Target, EBattleItemUseReason* Reason);
	bool HandlePickup(struct FBattleItemPickupInfo* PickupInfo, EBattleItemPickupReason* Reason, TScriptInterface<class UItemContainerInterface>* ItemContainer);
};


}

