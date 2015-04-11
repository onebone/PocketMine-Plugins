<?php

namespace ActStop;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\plugin\PluginBase;

class ActStop extends PluginBase implements Listener {

    public $ActStop = false;

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        if ( isset( $args[0] ) ) {
            switch ( $args[0] ) {
                case "on":
                    $this->ActStop = true;
                    $this->getServer()->broadcastMessage("[ActStop] 모든 플레이어는 움직일수 없게 됩니다!");
                    return true;
                case "off":
                    $this->ActStop = false;
                    $this->getServer()->broadcastMessage("[ActStop] 모든 플레이어는 움직일수 있게 됩니다!");
                    return true;
            }
        }
    }

    public function onMove( PlayerMoveEvent $event ) {
        if ( $this->ActStop ) {
            $event->setCancelled();
        }
    }
}