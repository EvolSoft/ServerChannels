<?php

/*
 * ServerChannels (v2.1) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 14/02/2018 10:03 AM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ServerChannels/blob/master/LICENSE)
 */

namespace ServerChannels\Events;

use pocketmine\Player;

class ServerChannelsJoinEvent extends ServerChannelsEvent {
    
    public static $handlerList = null;
    
    /** @var Player */
    private $player;
    
    /** @var string */
    private $channel;
    
    /** @var string */
    private $password;
    
    public function __construct(Player $player, $channel, $password = null){
        $this->player = $player;
        $this->channel = $channel;
        $this->password = $password;
    }
    
    /**
     * Get player
     * 
     * @return Player
     */
    public function getPlayer() : Player {
        return $this->player;
    }

    /**
     * Get channel
     * 
     * @return string
     */
    public function getChannel(){
        return $this->channel;
    }
    
    /**
     * Get player typed password
     * 
     * @return string
     */
    public function getPassword(){
        return $this->password;
    }
}