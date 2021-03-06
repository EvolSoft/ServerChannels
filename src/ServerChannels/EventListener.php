<?php

/*
 * ServerChannels v2.3 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ServerChannels/blob/master/LICENSE)
 */

namespace ServerChannels;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {
	
    /** @var ServerChannels */
    private $plugin;
    
	public function __construct(ServerChannels $plugin){
		$this->plugin = $plugin;
	}
	
	/**
	 * @param PlayerJoinEvent $event
	 */
	public function onPlayerJoin(PlayerJoinEvent $event){
	    $ch = $this->plugin->getDefaultChannel();
	    if($this->plugin->isDefaultChannelEnabled() && $this->plugin->channelExists($ch) && $this->plugin->getChannelAuth($ch) == ServerChannels::AUTH_NONE){
	        $this->plugin->joinChannel($event->getPlayer(), $ch);
	    }
	}
	
	/**
	 * @param PlayerChatEvent $event
	 */
	public function onPlayerChat(PlayerChatEvent $event){
		$player = $event->getPlayer();
		$message = $event->getMessage();
		if(($channel = $this->plugin->getCurrentChannel($player))){
			$this->plugin->sendChannelMessage($player, $channel, $message);
			$event->setCancelled(true);
		}
	}
	
	/**
	 * @param PlayerQuitEvent $event
	 */
	public function onPlayerQuit(PlayerQuitEvent $event){
	    $this->plugin->leaveChannel($event->getPlayer());
	}
}