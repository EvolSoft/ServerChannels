<?php

/*
 * ServerChannels v2.3 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
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