#pragma once

// =============================================================================
// PUBG Mobile Global 4.7.0 (4.7.0 -- 64-Bit) SDK
// Copyright (C) 2026 Zervex RIYAZ. All Rights Reserved.
// Developed by: @Zervexcheat RIYAZ (TG: @RIYAZXERO | Channel: @RIYAZ_STUFF)
// GitHub: https://github.com/RIYAZXERO
// Generation Time: Thu Nov 12 22:34:26 2026

// =============================================================================

#include <iostream>
#include <string>
#include <unordered_set>
#include <codecvt>

namespace SDK
{
template<typename Fn>
    inline Fn GetVFunction(void *thiz, int idx)
    {
	auto VTable = *reinterpret_cast<void***>(const_cast<void*>(thiz));
	return (Fn)(VTable[idx]);
    }

class UObject;

class FUObjectItem
{
public:
    UObject* Object;
    int32_t Flags;
    int32_t ClusterIndex;
    int32_t SerialNumber;

    enum class ObjectFlags : int32_t
    {
        None = 0,
        Native = 1 << 25,
        Async = 1 << 26,
        AsyncLoading = 1 << 27,
        Unreachable = 1 << 28,
        PendingKill = 1 << 29,
        RootSet = 1 << 30,
        NoStrongReference = 1 << 31
    };

    inline bool IsUnreachable() const
    {
        return !!(Flags & static_cast<std::underlying_type_t<ObjectFlags>>(ObjectFlags::Unreachable));
    }
    inline bool IsPendingKill() const
    {
        return !!(Flags & static_cast<std::underlying_type_t<ObjectFlags>>(ObjectFlags::PendingKill));
    }
};

class TUObjectArray
{
public:
    inline int32_t Num() const
    {
        return NumElements;
    }

    inline UObject* GetByIndex(int32_t index) const
    {
        return Objects[index].Object;
    }

    inline FUObjectItem* GetItemByIndex(int32_t index) const
    {
        if (index < NumElements)
        {
            return &Objects[index];
        }
        return nullptr;
    }

private:
    FUObjectItem* Objects;
    int32_t MaxElements;
    int32_t NumElements;
};

class FUObjectArray
{
public:
    int32_t ObjFirstGCIndex;
    int32_t ObjLastNonGCIndex;
    int32_t MaxObjectsNotConsideredByGC;
    int32_t OpenForDisregardForGC;
    TUObjectArray ObjObjects;
};

template<class T>
struct TArray
{
    friend struct FString;

public:
    inline TArray()
        : Data(nullptr), Count(0), Max(0)
    {
    }

    inline int Num() const noexcept
    {
        return Count;
    }

    inline T& operator[](int i)
    {
        return Data[i];
    }

    inline const T& operator[](int i) const
    {
        return Data[i];
    }

    inline bool IsValidIndex(int i) const noexcept
    {
        return i >= 0 && i < Count && Data != nullptr;
    }

    inline T* GetData() noexcept
    {
        return Data;
    }

    inline const T* GetData() const noexcept
    {
        return Data;
    }

private:
    T* Data;
    int32_t Count;
    int32_t Max;
};

class FNameEntry
{
public:
    static const auto NAME_WIDE_MASK = 0x1;
    static const auto NAME_INDEX_SHIFT = 1;

    int32_t Index;
    char UnknownData00[0x04];
    FNameEntry* HashNext;
    union
    {
        char AnsiName[1024];
        wchar_t WideName[1024];
    };

    inline const int32_t GetIndex() const noexcept
    {
        return Index >> NAME_INDEX_SHIFT;
    }

    inline bool IsWide() const noexcept
    {
        return Index & NAME_WIDE_MASK;
    }

    inline const char* GetAnsiName() const noexcept
    {
        return AnsiName;
    }

    inline const wchar_t* GetWideName() const noexcept
    {
        return WideName;
    }
};

template<typename ElementType, int32_t MaxTotalElements, int32_t ElementsPerChunk>
class TStaticIndirectArrayThreadSafeRead
{
public:
    inline size_t Num() const noexcept
    {
        return NumElements;
    }

    inline bool IsValidIndex(int32_t index) const noexcept
    {
        return index >= 0 && index < NumElements;
    }

    inline ElementType const* const& operator[](int32_t index) const
    {
        return *GetItemPtr(index);
    }

private:
    inline ElementType const* const* GetItemPtr(int32_t Index) const
    {
        int32_t ChunkIndex = Index / ElementsPerChunk;
        int32_t WithinChunkIndex = Index % ElementsPerChunk;
        ElementType** Chunk = Chunks[ChunkIndex];
        return Chunk + WithinChunkIndex;
    }

    enum
    {
        ChunkTableSize = (MaxTotalElements + ElementsPerChunk - 1) / ElementsPerChunk
    };

    ElementType** Chunks[ChunkTableSize];
    int32_t NumElements;
    int32_t NumChunks;
};

using TNameEntryArray = TStaticIndirectArrayThreadSafeRead<FNameEntry, 2 * 1024 * 1024, 16384>;

struct FName
{
    int32_t ComparisonIndex;
    int32_t Number;

    static TNameEntryArray* GNames;

    inline FName() noexcept
        : ComparisonIndex(0),
          Number(0)
    {
    }

    inline FName(int32_t i) noexcept
        : ComparisonIndex(i),
          Number(0)
    {
    }

    FName(const char* nameToFind)
        : ComparisonIndex(0),
          Number(0)
    {
        static std::unordered_set<int> cache;

        for (auto i : cache)
        {
            if (GetGlobalNames().IsValidIndex(i) && GetGlobalNames()[i] && !std::strcmp(GetGlobalNames()[i]->GetAnsiName(), nameToFind))
            {
                ComparisonIndex = i;
                return;
            }
        }

        for (auto i = 0; i < GetGlobalNames().Num(); ++i)
        {
            if (GetGlobalNames().IsValidIndex(i) && GetGlobalNames()[i] != nullptr)
            {
                if (!std::strcmp(GetGlobalNames()[i]->GetAnsiName(), nameToFind))
                {
                    cache.insert(i);
                    ComparisonIndex = i;
                    return;
                }
            }
        }
    }

    static inline TNameEntryArray& GetGlobalNames() noexcept
    {
        return *GNames;
    }

    inline const char* GetName() const
    {
        if (GetGlobalNames().IsValidIndex(ComparisonIndex) && GetGlobalNames()[ComparisonIndex])
        {
            return GetGlobalNames()[ComparisonIndex]->GetAnsiName();
        }
        return "Unknown";
    }

    inline bool operator==(const FName& other) const noexcept
    {
        return ComparisonIndex == other.ComparisonIndex;
    }
};

struct FString : public TArray<wchar_t>
{
    inline FString() = default;

    FString(const wchar_t* other)
    {
        Max = Count = (other && *other) ? static_cast<int32_t>(std::wcslen(other) + 1) : 0;
        if (Count)
        {
            Data = const_cast<wchar_t*>(other);
        }
    }

    inline bool IsValid() const noexcept
    {
        return Data != nullptr && Count > 0;
    }

    inline const wchar_t* c_str() const noexcept
    {
        return Data ? Data : L"";
    }

    inline std::string ToString() const
    {
        if (!Data || Count <= 0) return "";
        std::string str;
        str.reserve(Count);
        for (int i = 0; i < Count; ++i)
        {
            wchar_t c = Data[i];
            if (c == 0) break;
            if (c < 128) str.push_back(static_cast<char>(c));
            else str.push_back('?');
        }
        return str;
    }
};

template<class TEnum>
class TEnumAsByte
{
public:
    inline TEnumAsByte()
    {
    }

    inline TEnumAsByte(TEnum _value)
        : value(static_cast<uint8_t>(_value))
    {
    }

    explicit inline TEnumAsByte(int32_t _value)
        : value(static_cast<uint8_t>(_value))
    {
    }

    explicit inline TEnumAsByte(uint8_t _value)
        : value(_value)
    {
    }

    inline operator TEnum() const
    {
        return (TEnum)value;
    }

    inline TEnum GetValue() const
    {
        return (TEnum)value;
    }

private:
    uint8_t value;
};

class FScriptInterface
{
private:
    UObject* ObjectPointer;
    void* InterfacePointer;

public:
    inline UObject* GetObject() const
    {
        return ObjectPointer;
    }

    inline UObject*& GetObjectRef()
    {
        return ObjectPointer;
    }

    inline void* GetInterface() const
    {
        return ObjectPointer != nullptr ? InterfacePointer : nullptr;
    }
};

template<class InterfaceType>
class TScriptInterface : public FScriptInterface
{
public:
    inline InterfaceType* operator->() const
    {
        return (InterfaceType*)GetInterface();
    }

    inline InterfaceType& operator*() const
    {
        return *((InterfaceType*)GetInterface());
    }

    inline operator bool() const
    {
        return GetInterface() != nullptr;
    }
};

struct FText
{
#if defined(__LP64__)
    char pad[24];
#else
    char pad[12];
#endif
};

struct FScriptDelegate
{
    char pad[12];
};

struct FScriptMulticastDelegate
{
#if defined(__LP64__)
    char pad[16];
#else
    char pad[12];
#endif
};

template<typename Key, typename Value>
class TMap
{
#if defined(__LP64__)
    char pad[80];
#else
    char pad[60];
#endif
};

struct FWeakObjectPtr
{
public:
    inline bool SerialNumbersMatch(FUObjectItem* ObjectItem) const
    {
        return ObjectItem->SerialNumber == ObjectSerialNumber;
    }

    bool IsValid() const;

    UObject* Get() const;

    int32_t ObjectIndex;
    int32_t ObjectSerialNumber;
};

template<class T, class TWeakObjectPtrBase = FWeakObjectPtr>
struct TWeakObjectPtr : private TWeakObjectPtrBase
{
public:
    inline T* Get() const
    {
        return (T*)TWeakObjectPtrBase::Get();
    }

    inline T& operator*() const
    {
        return *Get();
    }

    inline T* operator->() const
    {
        return Get();
    }

    inline bool IsValid() const
    {
        return TWeakObjectPtrBase::IsValid();
    }
};

template<class T, class TBASE>
class TAutoPointer : public TBASE
{
public:
    inline operator T*() const
    {
        return TBASE::Get();
    }

    inline operator const T*() const
    {
        return (const T*)TBASE::Get();
    }

    explicit inline operator bool() const
    {
        return TBASE::Get() != nullptr;
    }
};

template<class T>
class TAutoWeakObjectPtr : public TAutoPointer<T, TWeakObjectPtr<T>>
{
public:
};

template<typename TObjectID>
class TPersistentObjectPtr
{
public:
    FWeakObjectPtr WeakPtr;
    int32_t TagAtLastTest;
    TObjectID ObjectID;
};

struct FStringAssetReference_
{

};

class FAssetPtr : public TPersistentObjectPtr<FStringAssetReference_>
{

};

template<typename ObjectType>
class TAssetPtr : FAssetPtr
{

};

struct FUniqueObjectGuid_
{

};

class FLazyObjectPtr : public TPersistentObjectPtr<FUniqueObjectGuid_>
{

};

template<typename ObjectType>
class TLazyObjectPtr : FLazyObjectPtr
{

};
}

