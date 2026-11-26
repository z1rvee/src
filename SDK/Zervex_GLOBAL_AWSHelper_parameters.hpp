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

// Function AWSHelper.AWSHelperBlueprintLibrary.UploadFile
struct UAWSHelperBlueprintLibrary_UploadFile_Params
{
	class UAWSHelper*                                  CosHelper;                                                // (Parm, ZeroConstructor, IsPlainOldData)
	struct FString                                     FromFilePath;                                             // (Parm, ZeroConstructor)
	struct FString                                     ToURL;                                                    // (Parm, ZeroConstructor)
	struct FString                                     NoneMatchHeader;                                          // (Parm, ZeroConstructor)
	struct FScriptDelegate                             OnComplete;                                               // (ConstParm, Parm, OutParm, ZeroConstructor, ReferenceParm)
	struct FScriptDelegate                             OnProgress;                                               // (ConstParm, Parm, OutParm, ZeroConstructor, ReferenceParm)
};

// Function AWSHelper.AWSHelperBlueprintLibrary.UploadBinary
struct UAWSHelperBlueprintLibrary_UploadBinary_Params
{
	class UAWSHelper*                                  CosHelper;                                                // (Parm, ZeroConstructor, IsPlainOldData)
	TArray<unsigned char>                              FromBinaries;                                             // (ConstParm, Parm, OutParm, ZeroConstructor, ReferenceParm)
	struct FString                                     ToURL;                                                    // (Parm, ZeroConstructor)
	struct FString                                     NoneMatchHeader;                                          // (Parm, ZeroConstructor)
	struct FScriptDelegate                             OnComplete;                                               // (ConstParm, Parm, OutParm, ZeroConstructor, ReferenceParm)
	struct FScriptDelegate                             OnProgress;                                               // (ConstParm, Parm, OutParm, ZeroConstructor, ReferenceParm)
};

// Function AWSHelper.AWSHelperBlueprintLibrary.DownloadFile
struct UAWSHelperBlueprintLibrary_DownloadFile_Params
{
	class UAWSHelper*                                  CosHelper;                                                // (Parm, ZeroConstructor, IsPlainOldData)
	struct FString                                     FromURL;                                                  // (Parm, ZeroConstructor)
	struct FString                                     ToFilePath;                                               // (Parm, ZeroConstructor)
	struct FScriptDelegate                             OnComplete;                                               // (ConstParm, Parm, OutParm, ZeroConstructor, ReferenceParm)
	struct FScriptDelegate                             OnProgress;                                               // (ConstParm, Parm, OutParm, ZeroConstructor, ReferenceParm)
};

// Function AWSHelper.AWSHelperBlueprintLibrary.DownloadBinary
struct UAWSHelperBlueprintLibrary_DownloadBinary_Params
{
	class UAWSHelper*                                  CosHelper;                                                // (Parm, ZeroConstructor, IsPlainOldData)
	struct FString                                     FromURL;                                                  // (Parm, ZeroConstructor)
	struct FScriptDelegate                             OnComplete;                                               // (ConstParm, Parm, OutParm, ZeroConstructor, ReferenceParm)
	struct FScriptDelegate                             OnProgress;                                               // (ConstParm, Parm, OutParm, ZeroConstructor, ReferenceParm)
};

// Function AWSHelper.AWSHelperBlueprintLibrary.DestroyAWSHelper
struct UAWSHelperBlueprintLibrary_DestroyAWSHelper_Params
{
	class UAWSHelper*                                  AWSHelper;                                                // (Parm, ZeroConstructor, IsPlainOldData)
};

// Function AWSHelper.AWSHelperBlueprintLibrary.ConstructAWSHelper
struct UAWSHelperBlueprintLibrary_ConstructAWSHelper_Params
{
	class UAWSHelper*                                  ReturnValue;                                              // (Parm, OutParm, ZeroConstructor, ReturnParm, IsPlainOldData)
};

// Function AWSHelper.AWSResponse.IsOK
struct UAWSResponse_IsOK_Params
{
	bool                                               ReturnValue;                                              // (Parm, OutParm, ZeroConstructor, ReturnParm, IsPlainOldData)
};

// Function AWSHelper.AWSResponse.GetResponseCode
struct UAWSResponse_GetResponseCode_Params
{
	int                                                ReturnValue;                                              // (Parm, OutParm, ZeroConstructor, ReturnParm, IsPlainOldData)
};

// Function AWSHelper.AWSResponse.GetRequestURL
struct UAWSResponse_GetRequestURL_Params
{
	struct FString                                     ReturnValue;                                              // (Parm, OutParm, ZeroConstructor, ReturnParm)
};

// Function AWSHelper.AWSResponse.GetErrorMessage
struct UAWSResponse_GetErrorMessage_Params
{
	struct FString                                     ReturnValue;                                              // (Parm, OutParm, ZeroConstructor, ReturnParm)
};

// Function AWSHelper.AWSResponse.GetErrorCode
struct UAWSResponse_GetErrorCode_Params
{
	struct FString                                     ReturnValue;                                              // (Parm, OutParm, ZeroConstructor, ReturnParm)
};

// Function AWSHelper.AWSResponse.GetContentAsString
struct UAWSResponse_GetContentAsString_Params
{
	struct FString                                     ReturnValue;                                              // (Parm, OutParm, ZeroConstructor, ReturnParm)
};

// Function AWSHelper.AWSResponse.GetContent
struct UAWSResponse_GetContent_Params
{
	TArray<unsigned char>                              ReturnValue;                                              // (ConstParm, Parm, OutParm, ZeroConstructor, ReturnParm, ReferenceParm)
};

}

