<?php

namespace Pell ;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Pell extends PluginBase implements Listener {
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents( $this, $this );
    }

    public function onCommand( CommandSender $sender, Command $command, $label, array $args ){
        if ( count($args) >= 2 ) {
            $name = strtolower( array_shift( $args ) );
            $target = $sender->getServer()->getPlayer( $name );
            if ( $target instanceof Player ) {
                $target->sendPopup( TextFormat::AQUA . "[" . $sender->getName() . "] ". TextFormat::WHITE . implode(" ", $args) );
                return true;
            } else {
                $sender->sendMessage( "[Pell] 해당 플레이어가 존재하지 않습니다 !" );
                return true;
            }
        }
    }
}

?>