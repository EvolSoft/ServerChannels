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

class ServerChannelsLeaveEvent extends ServerChannelsEvent {
    
    public static $handlerList = null;
    
    /** @var Player */
    private $player;
    
    /** @var string */
    private $channel;
    
    public function __construct(Player $player, $channel){
        $this->player = $player;
        $this->channel = $channel;
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
     * Get current channel
     *
     * @return string
     */
    public function getChannel(){
        return $this->channel;
    }
}