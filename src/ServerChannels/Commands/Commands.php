<?php

/*
 * ServerChannels (v2.2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 14/02/2018 10:03 AM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ServerChannels/blob/master/LICENSE)
 */

namespace ServerChannels\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use ServerChannels\ServerChannels;

class Commands extends PluginCommand implements CommandExecutor {

    /** @var ServerChannels */
    private $plugin;
    
	public function __construct(ServerChannels $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) : bool {
		if(isset($args[0])){
			$args[0] = strtolower($args[0]);
			switch($args[0]){
			    case "create":
			    case "new":
			        if($sender->hasPermission("serverchannels.commands.create")){
			            if(isset($args[1])){
			                $this->plugin->createChannel($args[1]);
			                $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&aChannel &b" . strtolower($args[1]) . "&a created!"));
			            }else{
			                $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&cUsage: /sch create <channel>"));
			            }
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			        break;
			    case "help":
			        goto help;
			    case "info":
			        if($sender->hasPermission("serverchannels.commands.info")){
			            $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX . "&eServerChannels &bv" . $this->plugin->getVersion() . "&e developed by &bEvolSoft"));
			            $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX . "&eWebsite &b" . $this->plugin->getDescription()->getWebsite()));
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
		            break;
			    case "join":
			        if($sender instanceof Player){
			            if($sender->hasPermission("serverchannels.commands.join")){
			                if(isset($args[1])){
			                    if($sender->hasPermission(strtolower("serverchannels.channels." . $args[1]))){
			                        $cmessage = null;
			                        switch($this->plugin->joinChannel($sender, $args[1], isset($args[2]) ? $args[2] : null, $cmessage)){
			                            case ServerChannels::SUCCESS:
			                                $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&aYou joined &b" . strtolower($args[1]) . "&a channel"));
			                                break;
			                            case ServerChannels::ERR_WRONG_PASS:
			                                $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&cWrong password"));
			                                break;
			                            case ServerChannels::ERR_NOT_WHITELISTED:
			                                $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&cYou are not whitelisted on this channel"));
			                                break;
			                            case ServerChannels::ERR_CHANNEL_NOT_FOUND:
			                                $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&cChannel not found"));
    			                            break;
			                            case ServerChannels::CANCELLED:
			                                $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . $cmessage));
			                                break;
			                            default:
			                                $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&cAn error has occurred while joining the channel"));
			                                break;
			                        }
			                    }else{
			                        $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&cYou don't have permissions to join in this channel"));
			                    }
			                }else{
			                    $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&cUsage: /sch join <channel> [password]"));
			                }
			            }else{
			                $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			            }
			        }else{
			            $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX . "&cYou can only perform this command as a player"));
			        }
			        break;
			    case "leave":
			        if($sender instanceof Player){
			            if($sender->hasPermission("serverchannels.commands.leave")){
			                $channel = $this->plugin->getCurrentChannel($sender);
			                $cmessage = null;
			                switch($this->plugin->leaveChannel($sender, $cmessage)){
			                    case ServerChannels::SUCCESS:
			                        $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&aYou left &b" . $channel . "&a channel"));
			                        break;
			                    case ServerChannels::ERR_NO_CHANNEL:
			                        $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&cYou haven't joined on a channel"));
			                        break;
			                    case ServerChannels::CANCELLED:
			                        $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . $cmessage));
			                        break;
			                    default:
			                        $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX  . "&cAn error has occurred while leaving the channel"));
			                        break;
			                        
			                }
			            }else{
			                $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			            }
			        }else{
			            $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX . "&cYou can only perform this command as a player"));
			        }
			        break;
			    case "list":
			        if($sender->hasPermission("serverchannels.commands.list")){
			            $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX . "&b>> &aAvailable Channels &b<<"));
			            foreach($this->plugin->getAllChannels() as $k => $v){
			                if($sender->hasPermission(strtolower("serverchannels.channels." . $k))){
			                    $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX . "&b- &a" . $k));
			                }
			            }
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			        break;
			    case "reload":
			        if($sender->hasPermission("serverchannels.commands.reload")){
			            $this->plugin->reload();
			            $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX . "&aConfiguration reloaded"));
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			        break;
			    default:
			        if($sender->hasPermission("serverchannels")){
			            $sender->sendMessage(TextFormat::colorize(ServerChannels::PREFIX . "&cSubcommand &a" . $args[0] . "&c not found. Use &a/sch help&c to show available commands"));
			            break;
			        }
			        $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
			        break;
			}
		}else{
    		help:
    		if($sender->hasPermission("serverchannels.commands.help")){
    		    $sender->sendMessage(TextFormat::colorize("&b>> &aAvailable Commands &b<<"));
    		    $sender->sendMessage(TextFormat::colorize("&a/sch info &b>>&e Show info about this plugin"));
    		    $sender->sendMessage(TextFormat::colorize("&a/sch help &b>>&e Show help about this plugin"));
    		    $sender->sendMessage(TextFormat::colorize("&a/sch reload &b>>&e Reload the config"));
    		    $sender->sendMessage(TextFormat::colorize("&a/sch create &b>>&e Create new channel"));
    		    $sender->sendMessage(TextFormat::colorize("&a/sch list &b>>&e Show the list of all channels"));
    		    $sender->sendMessage(TextFormat::colorize("&a/sch join &b>>&e Join a channel"));
    		    $sender->sendMessage(TextFormat::colorize("&a/sch leave &b>>&e Leave the current channel"));
    		}else{
    		    $sender->sendMessage(TextFormat::colorize("&cYou don't have permissions to use this command"));
    		}
		}
		return true;
    }
}