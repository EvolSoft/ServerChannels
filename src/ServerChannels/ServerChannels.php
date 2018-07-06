<?php

/*
 * ServerChannels v2.3 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ServerChannels/blob/master/LICENSE)
 */

namespace ServerChannels;

use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use ServerChannels\Events\ServerChannelsJoinEvent;
use ServerChannels\Events\ServerChannelsLeaveEvent;
use ServerChannels\Events\ServerChannelsChatEvent;

class ServerChannels extends PluginBase {
	
    /** @var string */
	const PREFIX = "&b[&aServer&eChannels&b] ";
	
	/** @var string */
	const API_VERSION = "1.0";
	
	/** @var int */
	const CANCELLED = 0;
	
	/** @var int */
	const SUCCESS = 1;
	
	/** @var int */
	const ERR_WRONG_PASS = 2;
	
	/** @var int */
	const ERR_NOT_WHITELISTED = 3;
	
	/** @var int */
	const ERR_CHANNEL_NOT_FOUND = 4;
	
	/** @var int */
	const ERR_NO_CHANNEL = 5;
	
	/** @var int */
	const AUTH_NONE = 0;
	
	/** @var int */
	const AUTH_PASSWORD = 1;
	
	/** @var int */
	const AUTH_WHITELIST = 2;

	/** @var array */
	public $cfg;
	
	/** @var Config */
	private $channels;
	
	/** @var array */
	private $users;
	
	/** @var ServerChannels */
	private static $instance = null;
	
	public function onLoad(){
	    if(!self::$instance instanceof ServerChannels){
	        self::$instance = $this;
	    }
	}
	
    public function onEnable(){
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->saveResource("channels.yml");
        $this->cfg = $this->getConfig()->getAll();
        $this->channels = new Config($this->getDataFolder() . "channels.yml");
        $this->initializeChannelsPermissions();
        $this->getCommand("serverchannels")->setExecutor(new Commands\Commands($this));
	    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
    
    /**
     * Reload ServerChannels configurations
     */
    public function reload(){
        $this->reloadConfig();
        $this->cfg = $this->getConfig()->getAll();
        $this->channels->reload();
        $this->initializeChannelsPermissions();
    }
    
    /**
     * Initialize channels permissions (internal)
     */    
    public function initializeChannelsPermissions(){
        foreach($this->getAllChannels() as $k => $v){
            $permission = new Permission("serverchannels.channels." . strtolower($k), "ServerChannels " . $k . " channel permission.");
            $this->getServer()->getPluginManager()->addPermission($permission);
        }
    }
    
    /**
     * Replace variables inside a string
     *
     * @param string $str
     * @param array $vars
     *
     * @return string
     */
    public function replaceVars($str, array $vars){
        foreach($vars as $key => $value){
            $str = str_replace("{" . $key . "}", $value, $str);
        }
        return $str;
    }
    
    /**
     * Get ServerChannels API
     *
     * @return ServerChannels
     */
    public static function getAPI(){
        return self::$instance;
    }
    
    /**
     * Get ServerChannels version
     *
     * @return string
     */
    public function getVersion(){
        return $this->getVersion();
    }
    
    /**
     * Get ServerChannels API version
     *
     * @return string
     */
    public function getAPIVersion(){
        return self::API_VERSION;
    }
    
    /**
     * Check if console logging is enabled
     */
    public function getLogOnConsole(){
        return $this->cfg["log-on-console"];
    }
    
    /**
     * Check if default (join) channel is enabled
     */
    public function isDefaultChannelEnabled(){
        return $this->cfg["default-channel"]["enabled"];
    }
    
    /**
     * Get default (join) channel
     */
    public function getDefaultChannel(){
        return $this->cfg["default-channel"]["channel"];
    }
    
    /**
     * Create a new channel
     * 
     * @param string $channel
     */
    public function createChannel($channel){
        $ch = array(
            "name" => $channel,
            "prefix" => "[" . $channel . "]",
            "suffix" => null,
            "format" => "{PREFIX} <{PLAYER}> {MESSAGE}",
            "hidden" => true,
            "auth" => "none",
            "password" => null,
            "whitelist" => array()
        );
        $this->channels->set(strtolower($channel), $ch);
        $this->channels->save();
        $this->initializeChannelsPermissions();
    }
    
    /**
     * Check if channel exists
     * 
     * @param string $channel
     * 
     * @return bool
     */
    public function channelExists($channel) : bool {
        return $this->channels->exists(strtolower($channel));
    }
    
    /**
     * Get all channels
     *
     * @return array
     */
    public function getAllChannels(){
        return $this->channels->getAll();
    }
    
    /**
     * Get the specified channel
     *
     * @param string $channel
     *
     * @return array|null
     */
    public function getChannel($channel){
        $channel = strtolower($channel);
        if($this->channelExists($channel)){
            return $this->channels->get($channel);
        }
        return null;
    }
    
    
    /**
     * Get channel name
     *
     * @param string $channel
     *
     * @return string|null
     */
    public function getChannelName($channel){
        if(($ch = $this->getChannel($channel)) && isset($ch["name"])){
            return $ch["name"];
        }
        return null;
    }
    
    
    /**
     * Get channel prefix
     *
     * @param string $channel
     *
     * @return string|null
     */
    public function getChannelPrefix($channel){
        if(($ch = $this->getChannel($channel)) && isset($ch["prefix"])){
            return $ch["prefix"];
        }
        return null;
    }
    
    
    /**
     * Get channel suffix
     *
     * @param string $channel
     *
     * @return string|null
     */
    public function getChannelSuffix($channel){
        if(($ch = $this->getChannel($channel)) && isset($ch["suffix"])){
            return $ch["suffix"];
        }
        return null;
    }
    
    /**
     * Get channel format
     *
     * @param string $channel
     *
     * @return string|null
     */
    public function getChannelFormat($channel){
        if(($ch = $this->getChannel($channel)) && isset($ch["format"])){
            return $ch["format"];
        }
        return null;
    }
    
    /**
     * Check if the specified channel is hidden
     *
     * @param string $channel
     *
     * @return bool
     */
    public function isChannelHidden($channel) : bool {
        if(($ch = $this->getChannel($channel)) && isset($ch["hidden"])){
            return $ch["hidden"];
        }
        return true;
    }
    
    /**
     * Get channel authentication
     *
     * @param string $channel
     *
     * @return int
     */
    public function getChannelAuth($channel) : int {
        if(($ch = $this->getChannel($channel)) && isset($ch["auth"])){
            if(is_int($ch["auth"])){
                return $ch["auth"];
            }else{
                switch(strtolower($ch["auth"])){
                    case "none":
                    default:
                        return self::AUTH_NONE;
                    case "password":
                        return self::AUTH_PASSWORD;
                    case "whitelist":
                        return self::AUTH_WHITELIST;
                }
            }   
        }
        return self::AUTH_NONE;
    }
    
    /**
     * Get channel password
     *
     * @param string $channel
     *
     * @return string|null
     */
    public function getChannelPassword($channel){
        if(($ch = $this->getChannel($channel)) && isset($ch["password"])){
            return $ch["password"];
        }
        return null;
    }
    
    /**
     * Get channel whitelist
     *
     * @param string $channel
     *
     * @return array|null
     */
    public function getChannelWhitelist($channel){
        if(($ch = $this->getChannel($channel)) && isset($ch["whitelist"])){
            return $ch["whitelist"];
        }
        return null;
    }
    
    /**
     * Get current player channel
     * 
     * @param Player $player
     * 
     * @return string|null
     */
    public function getCurrentChannel(Player $player){
        if($this->hasJoined($player)){
            return $this->users[strtolower($player->getName())];
        }
        return null;
    }
    
    /**
     * Join the specified channel
     * 
     * @param Player $player
     * @param string $channel
     * @param string $password
     * @param string $cmessage
     * 
     * @return int
     */
    public function joinChannel(Player $player, $channel, $password = null, &$cmessage = null) : int {
        $channel = strtolower($channel);
        if($this->channelExists($channel)){
            $scevent = new ServerChannelsJoinEvent($player, $channel, $password);
            $scevent->setCancelledMessage("&cOperation cancelled");
            $this->getServer()->getPluginManager()->callEvent($scevent);
            if($scevent->isCancelled()){
                $cmessage = $scevent->getCancelledMessage();
                return self::CANCELLED;
            }
            switch($this->getChannelAuth($channel)){
                default:
                case self::AUTH_NONE:
                    break;
                case self::AUTH_PASSWORD:
                    if(($pwd = $this->getChannelPassword($channel)) && $pwd != $password){
                        return self::ERR_WRONG_PASS;
                    }
                    break;
                case self::AUTH_WHITELIST:
                    if(!in_array($player->getName(), $this->getChannelWhitelist($channel))){
                        return self::ERR_NOT_WHITELISTED;
                    }
                    break;
            }
            if($this->hasJoined($player)){
                $this->leaveChannel($player);
            }
            $this->users[strtolower($player->getName())] = $channel;
            return self::SUCCESS;
        }
        return self::ERR_CHANNEL_NOT_FOUND;
    }
    
    /**
     * Leave current channel
     * 
     * @param Player $player
     * @param string $cmessage
     * 
     * @return int
     */
    public function leaveChannel(Player $player, &$cmessage = null) : int {
        if($this->hasJoined($player)){
            $pname = strtolower($player->getName());
            $channel = $this->users[$pname];
            $scevent = new ServerChannelsLeaveEvent($player, $channel);
            $scevent->setCancelledMessage("&cOperation cancelled");
            $this->getServer()->getPluginManager()->callEvent($scevent);
            if($scevent->isCancelled()){
                $cmessage = $scevent->getCancelledMessage();
                return self::CANCELLED;
            }
            unset($this->users[$pname]);
            return self::SUCCESS;
        }
        return self::ERR_NO_CHANNEL;
    }
    
    /**
     * Check if player has joined a channel
     *
     * @param Player $player
     *
     * @return bool
     */
    public function hasJoined(Player $player) : bool {
        return isset($this->users[strtolower($player->getName())]);
    }
    
    /**
     * Get all players in the specified channel
     *
     * @param string $channel
     *
     * @return Player[]
     */
    public function getChannelPlayers($channel){
        $channel = strtolower($channel);
        $array = array_keys($this->users, $channel);
        $result = array();
        foreach($array as $v){
            array_push($result, $this->getServer()->getPlayer($v));
        }
        return $result;
    }
    
    /**
     * Format channel message
     *
     * @param string $channel
     * @param Player $player
     * @param string $message
     *
     * @return string|null
     */
    public function formatChannelMessage($channel, Player $player, $message){
        $channel = strtolower($channel);
        if(($ch = $this->getChannel($channel))){
            $prefix = $ch["prefix"];
            $suffix = $ch["suffix"];
            $format = $ch["format"];
            $str = $this->replaceVars($format, array(
                "MESSAGE" => $message,
                "PLAYER" => $player->getName(),
                "PREFIX" => $prefix,
                "SUFFIX" => $suffix,
                "TIME" => date($this->cfg["datetime-format"]),
                "WORLD" => $player->getLevel()->getName()
            ));
            return $str;
        }
        return null;
    }
    
    /**
     * Send message into the specified channel
     * 
     * @param Player $player
     * @param string $channel
     * @param string $message
     * @param string $cmessage
     * 
     * @return int
     */
    public function sendChannelMessage(Player $player, $channel, $message, &$cmessage = null){
        if($this->channelExists($channel)){
            $scevent = new ServerChannelsChatEvent($player, $channel, $message);
            $scevent->setCancelledMessage("&cOperation cancelled");
            $this->getServer()->getPluginManager()->callEvent($scevent);
            if($scevent->isCancelled()){
                $cmessage = $scevent->getCancelledMessage();
                return self::CANCELLED;
            }
            $message = TextFormat::colorize($this->formatChannelMessage($channel, $player, $scevent->getMessage()));
            if($this->isChannelHidden($channel)){
                foreach($this->getChannelPlayers($channel) as $cp){
                    $cp->sendMessage($message);
                }
            }else{
                foreach($this->getServer()->getOnlinePlayers() as $op){
                    $op->sendMessage($message);
                }
            }
            if($this->getLogOnConsole()){
                $this->getServer()->getLogger()->info($message);
            }
        }
        return self::SUCCESS;
    }
}