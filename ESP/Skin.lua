-- FUCK BY @SIMPLEXPRO / @Aditya0_1_2


local function apply_hooks()

    local function hook_function(module_name, func_name, hook_fn)
        pcall(function()
            local mod = package.loaded[module_name]
            if not mod then
                if type(_G[module_name]) == "table" then
                    mod = _G[module_name]
                end
            end
            if mod and mod[func_name] then
                mod[func_name] = hook_fn
            end
        end)
    end

    pcall(function()
        local lobby = package.loaded["client.network.Protocol.LobbyHandler"] or _G.LobbyHandler
        if not lobby then return end

        lobby.on_posidon_update_ntf = function(arg) end


        lobby.on_on_hades_update_ntf = function(arg)
            pcall(function()
                local enc_key = "7vu#CH5aJjs1Jjj5"
                local encoded = slua.LuaArchiverEncode(LuaStateWrapper, {})
                local encrypted = Client.Tea2Encrypt(encoded, enc_key)
                lobby.send_on_hades_update_rsp(encrypted)
            end)
        end


        lobby.on_on_zeus_update_ntf = function(arg0, arg1)
            pcall(function()
                local enc_key = "7vu#CH5aJjs1Jjj5"
                local encoded = slua.LuaArchiverEncode(LuaStateWrapper, {})
                local encrypted = Client.Tea2Encrypt(encoded, enc_key)
                lobby.send_on_zeus_update_rsp(encrypted, arg1)
            end)
        end
    end)


    pcall(function()
        local login = package.loaded["client.logic.login.login_module"]
        if login then
            login.PakMonitorStart = function(arg0, arg1) end
        end
    end)


    pcall(function()
        if _G.Tss then
            _G.Tss.GetUserTag4Lua = function() return "" end
            _G.Tss.GetDeviceFeature = function() return "VALID_DEVICE" end
            _G.Tss.EigenArrayObfuscationVerify = function() return "VALID" end
            _G.Tss.SendEigeninfoData = function() return 0 end
            _G.Tss.SaveSendEigeninfoCode = function() end
        end

        local goku = package.loaded["GameLua.Mod.BaseMod.Client.Security.Gokuba"] or _G.GokubaLogic
        if goku then
            goku.ForwardFeature = function()
                local payload = { 0, 0, 0, 0, 0 }
                if NetUtil and NetUtil.SendPkg then
                    NetUtil.SendPkg("battle_client_sync_allstar_auth_check_result_req", payload)
                end
            end
        end

        local crew_safety = package.loaded["client.slua.umg.complaint.crew_safety_detection_base"]
        if crew_safety then
            crew_safety.PerformCheckWithObfuscation = function(self)
                self:SendCheckResult("SAFE_OBFUSCATION")
            end
            crew_safety.PerformCheckWithoutObfuscation = function(self)
                self:SendCheckResult({ 0, 0, 0, 0, 0 })
            end
        end
    end)


    pcall(function()
        if _G.GameplayCallbacks then
            _G.GameplayCallbacks.ReportPlayerIP = function() end
            _G.GameplayCallbacks.SendClientStats = function() end
            _G.GameplayCallbacks.ReportPlayerFramePingRecord = function() end
            _G.GameplayCallbacks.OnDSConnectionSaturated = function() end
            _G.GameplayCallbacks.ReportDSNetSaturation = function() end
            _G.GameplayCallbacks.ReportNetContinuousSaturate = function() end
            _G.GameplayCallbacks.ReportDSNetRate = function() end
            _G.GameplayCallbacks.SendDSErrorLogToLobby = function() end
            _G.GameplayCallbacks.SendDSHawkEyePatrolLogToLobby = function() end

            _G.GameplayCallbacks.SendTssSdkAntiDataToLobby = function(arg0, arg1, arg2, arg3, arg4)
                if NetUtil and NetUtil.SendPacket then
                    local dummy_data = {
                        ErrorCode = 0,
                        ReportLog = 0
                    }
                    NetUtil.SendPacket("on_tss_sdk_anti_data", arg1, "", 0, arg4, dummy_data)
                end
            end
        end

        if _G.NetUtil and not _G.NetUtil._AKHooked then
            local orig_SendPacket = _G.NetUtil.SendPacket
            local orig_SendPkg = _G.NetUtil.SendPkg

            local blocked_packets = {
                ["report_unrealnet_exception"] = true,
                ["report_dh_calc_key_error"] = true,
                ["report_client_net_continuous_saturate"] = true,
                ["report_player_ip"] = true,
                ["report_unrealnet_clientstats"] = true,
                ["shutdown_after_error"] = true,
            }

            _G.NetUtil.SendPacket = function(packet_name, ...)
                if blocked_packets[packet_name] then
                    return nil
                end
                return orig_SendPacket(packet_name, ...)
            end

            _G.NetUtil.SendPkg = function(pkg_name, ...)
                if blocked_packets[pkg_name] then
                    return nil
                end
                return orig_SendPkg(pkg_name, ...)
            end

            _G.NetUtil._AKHooked = true
        end
    end)


    pcall(function()
        local puffer = package.loaded["client.slua.logic.download.puffer.logic_puffer_common"]
        if puffer then
            puffer._GetKickOutList = function()
                return {}
            end
            puffer.HandleKickOutList = function(self)
                return
            end
        end
    end)
end

apply_hooks()


pcall(function()
    local ticker = package.loaded["common.time_ticker"] or require("common.time_ticker")
    if ticker and ticker.AddTimerLoop then
        ticker.AddTimerLoop(0, function()
            pcall(apply_hooks)
        end, -1, 5.0)
    end
end)
