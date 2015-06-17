<?php

/*
 * ServerChannels (v1.2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 17/06/2015 09:42 AM (UTC)
 * Copyright & License: (C) 2014-2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ServerChannels/blob/master/LICENSE)
 */

namespace ServerChannels;

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase {
	
	//About Plugin Const
	const PRODUCER = "EvolSoft";
	const VERSION = "1.2";
	const MAIN_WEBSITE = "http://www.evolsoft.tk";
	//Other Const
	//Prefix
	const PREFIX = "&b[&aServer&eChannels&b] ";
	
	public $cfg;
	
	public $users;
	
	public function translateColors($symbol, $message){
		
		$message = str_replace($symbol."0", TextFormat::BLACK, $message);
		$message = str_replace($symbol."1", TextFormat::DARK_BLUE, $message);
		$message = str_replace($symbol."2", TextFormat::DARK_GREEN, $message);
		$message = str_replace($symbol."3", TextFormat::DARK_AQUA, $message);
		$message = str_replace($symbol."4", TextFormat::DARK_RED, $message);
		$message = str_replace($symbol."5", TextFormat::DARK_PURPLE, $message);
		$message = str_replace($symbol."6", TextFormat::GOLD, $message);
		$message = str_replace($symbol."7", TextFormat::GRAY, $message);
		$message = str_replace($symbol."8", TextFormat::DARK_GRAY, $message);
		$message = str_replace($symbol."9", TextFormat::BLUE, $message);
		$message = str_replace($symbol."a", TextFormat::GREEN, $message);
		$message = str_replace($symbol."b", TextFormat::AQUA, $message);
		$message = str_replace($symbol."c", TextFormat::RED, $message);
		$message = str_replace($symbol."d", TextFormat::LIGHT_PURPLE, $message);
		$message = str_replace($symbol."e", TextFormat::YELLOW, $message);
		$message = str_replace($symbol."f", TextFormat::WHITE, $message);
		
		$message = str_replace($symbol."k", TextFormat::OBFUSCATED, $message);
		$message = str_replace($symbol."l", TextFormat::BOLD, $message);
		$message = str_replace($symbol."m", TextFormat::STRIKETHROUGH, $message);
		$message = str_replace($symbol."n", TextFormat::UNDERLINE, $message);
		$message = str_replace($symbol."o", TextFormat::ITALIC, $message);
		$message = str_replace($symbol."r", TextFormat::RESET, $message);
		
		return $message;
	}
	
    public function onEnable(){
        @mkdir($this->getDataFolder());
        if(!file_exists($this->getDataFolder() . "channels/")){
        	@mkdir($this->getDataFolder() . "channels/");
        	$this->saveResource("channels/admin.yml");
        	$this->saveResource("channels/staff.yml");
        }
        $this->saveDefaultConfig();
        $this->cfg = $this->getConfig()->getAll();
        $this->initializeChannelPermissions();
        $this->getCommand("serverchannels")->setExecutor(new Commands\Commands($this));
	    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
    
    public function createChannel($channel){
    	$channel = strtolower($channel);
    	$tmp = new Config($this->getDataFolder() . "channels/" . strtolower($channel . ".yml"), Config::YAML);
    	$data = array(
    			"prefix" => "[".$channel."]",
    			"suffix" => "",
    			"format" => "{PREFIX} <{PLAYER}> {MESSAGE}",
    			"public" => true
    	             );
    	$tmp->setAll($data);
    	$tmp->save();
    	return true;
    }
    
    public function getLogOnConsole(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["log-on-console"];
    }
    
    public function initializeChannelPermissions(){
    	$channels = $this->getAllChannels();
    	for($i = 0; $i < count($channels); $i++){
    		$permission = new Permission("serverchannels.channels." . strtolower($channels[$i]), "ServerChannels " . strtolower($channels[$i]) . " channel permission.");
    		Server::getInstance()->getPluginManager()->addPermission($permission);
    	}
    }
    
    public function hasJoined(Player $player){
    	return isset($this->users[strtolower($player->getName())]);
    }
    
    public function joinChannel(Player $player, $channel){
    	$channel = strtolower($channel);
    	//Check if channel exists
    	if($this->channelExists($channel)){
    		//Check if player has already joined another channel
    		if($this->hasJoined($player)){
    			$this->leaveChannel($player);
    			$this->users[strtolower($player->getName())] = $channel;
    			return true;
    		}else{
    			$this->users[strtolower($player->getName())] = $channel;
    			return true;
    		}
    	}else{
    		return false;
    	}
    }
    
    public function leaveChannel(Player $player){
    	if($this->hasJoined($player)){
    		unset($this->users[strtolower($player->getName())]);
    		return true;
    	}else{
    		return false;
    	}
    }
    
    public function getPlayerChannel(Player $player){
    	if($this->hasJoined($player)){
    		return $this->users[strtolower($player->getName())];
    	}else{
    		return false;
    	}
    }
    
    public function getAllChannels(){
    	$files = glob($this->getDataFolder() . "channels/*.yml");
    	for($i = 0; $i < count($files); $i++){
    		$name = strtolower(basename($files[$i]));
    		$name = substr($name, 0, -4);
    		$result[$i] = $name;
    	}
    	return $result;
    }
    
    public function channelExists($channel){
    	return file_exists($this->getDataFolder() . "channels/" . strtolower($channel . ".yml"));
    }
    
    public function isChannelPublic($channel){
    	$channel = strtolower($channel);
    	if($this->channelExists($channel)){
    		$tmp = new Config($this->getDataFolder() . "channels/" . strtolower($channel . ".yml"), Config::YAML);
    		$tmp = $tmp->getAll();
    		return $tmp["public"];
    	}else{
    		return false;
    	}
    }
    
    public function getChannelPlayers($channel){
    	$channel = strtolower($channel);
    	$tmp = array_keys($this->users, $channel);
    	for($i = 0; $i < count($tmp); $i++){
    		$result[$i] = $this->getServer()->getPlayer($tmp[$i]);
    	}
    	return $result;
    }
    
    public function getChannelFormat($channel, Player $player, $message){
    	$channel = strtolower($channel);
    	if($this->channelExists($channel)){
    		$conf = $this->getConfig()->getAll();
    		$tmp = new Config($this->getDataFolder() . "channels/" . strtolower($channel . ".yml"), Config::YAML);
    		$tmp = $tmp->getAll();
    		$prefix = $tmp["prefix"];
    		$suffix = $tmp["suffix"];
    		$format = $tmp["format"];
    		$format = str_replace("{MESSAGE}", $message, $format);
    		$format = str_replace("{PLAYER}", $player->getName(), $format);
    		$format = str_replace("{PREFIX}", $prefix, $format);
    		$format = str_replace("{SUFFIX}", $suffix, $format);
    		$format = str_replace("{TIME}", date($conf["datetime-format"]), $format);
    		$format = str_replace("{WORLD}", $player->getLevel()->getName(), $format);
    		return $format;
    	}else{
    		return false;
    	}
    }
    
    public function SendChannelMessage(Player $player, $channel, $message){
    	$channel = strtolower($channel);
    	//Check if channel exists
    	if($this->channelExists($channel)){
    		$tmp = new Config($this->getDataFolder() . "channels/" . strtolower($channel . ".yml"), Config::YAML);
    		$message = $this->getChannelFormat($channel, $player, $message);
    		//Check if channel is pubblic
    		if($this->isChannelPublic($channel)){
    			foreach($this->getServer()->getOnlinePlayers() as $players){
    				$players->sendMessage($this->translateColors("&", $message));
    			}
    		}else{
    			foreach($this->getChannelPlayers($channel) as $players){
    				if($this->getPlayerChannel($players)){
    					$players->sendMessage($this->translateColors("&", $message));
    				}
    			}
    		}
    		if($this->getLogOnConsole()){
    			Server::getInstance()->getLogger()->info($this->translateColors("&", $message));
    		}
    	}
    }
    
}
?>
