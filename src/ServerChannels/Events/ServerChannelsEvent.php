<?php

/*
 * ServerChannels (v2.2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 14/02/2018 10:03 AM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ServerChannels/blob/master/LICENSE)
 */

namespace ServerChannels\Events;

use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;

abstract class ServerChannelsEvent extends PluginEvent implements Cancellable {
    
    /** @var string */
    private $cmessage;
    
    /**
     * Get event cancelled message
     *
     * @return string
     */
    public function getCancelledMessage(){
        return $this->cmessage;
    }
    
    /**
     * Set event cancelled message
     *
     * @param string $message
     */
    public function setCancelledMessage($cmessage){
        $this->cmessage = $cmessage;
    }
}