<?php

/*
 * ServerChannels (v1.1) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 29/12/2014 09:51 AM (UTC)
 * Copyright & License: (C) 2014 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ServerChannels/blob/master/LICENSE)
 */

namespace ServerChannels;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class EventListener extends PluginBase implements Listener{
	
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function onPlayerChat(PlayerChatEvent $event){
		$player = $event->getPlayer();
		$this->cfg = $this->plugin->getConfig()->getAll();
		$message = $event->getMessage();
		if($this->plugin->getPlayerChannel($player)){
			$channel = $this->plugin->getPlayerChannel($player);
			$this->plugin->SendChannelMessage($player, $channel, $message);
			$event->setCancelled(true);
		}
	}
	
	public function onPlayerQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		if($this->plugin->hasJoined($player)){
			$this->plugin->leaveChannel($player);
		}
	}

}
?>
