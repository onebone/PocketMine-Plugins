<?php

namespace Security;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Security extends PluginBase implements Listener {

    public $Database, $userData, $config, $configData, $title = "[Security]";

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents( $this, $this );
        $this->loadYml();
    }

    public function onDisable() {
        $this->saveYml();
    }

    public function onPlayerJoin( PlayerJoinEvent $event ) {
        $playerName = $event->getPlayer()->getName();
        if ( ! ( isset( $this->userData[$playerName] ) ) ) {
            $this->userData[$playerName] = 0;
        }
        if ( $this->configData["NameTagWarning"] == true ) {
            $event->getPlayer()->setNameTag( $playerName . "[" . $this->userData[$playerName] . "]" );
        }
        if ( $this->configData["WarningBan"] == true ) {
            if ( $this->userData[$event->getPlayer()->getName()] >= $this->configData["BanCount"] )
                $event->getPlayer()->setBanned( true );
        }
    }

    public function onCommand( CommandSender $sender, Command $command, $label, array $ub ) {
        switch ( $command->getName() ) {
            case "wc":
                if ( $sender instanceof Player ) {
                    $sender->sendMessage( $this->title . " " . $sender->getName() . " 님의 경고횟수는 " . $this->userData[$sender->getName()] . " 회 입니다." );
                    return true;
                } else {
                    $sender->sendMessage( $this->title . " 플레이어만 사용가능한 명령어입니다 !");
                    return true;
                }
            case "w":
                if ( count($ub) < 2) return false;
                if ( isset( $this->userData[$ub[0]] ) ) {
                    $this->userData[$ub[0]] += $ub[1];
                    $this->getServer()->broadcastMessage( $this->title . " 관리자가 " . $ub[0] . " 님에게 경고를 " . $ub[1] . " 회 부여하였습니다." );
                    $name = array_shift( $ub );
                    $target = $sender->getServer()->getPlayer( $name );
                    if ( $this->configData["NameTagWarning"] == true ) {
                        if ( $target instanceof Player ) {
                            $target->setNameTag($target->getName() . "[" . $this->userData[$target->getName()] . "]");
                        }
                    }
                    if ( $this->configData["WarningBan"] == true ) {
                        if ( $target instanceof Player ) {
                            if ( $this->userData[$target->getPlayer()->getName()] >= $this->configData["BanCount"] ) {
                                $target->getPlayer()->setBanned( true );
                                $this->userData[$target->getPlayer()->getName()] = 0;
                             }
                        }
                    }
                    return true;
                } else {
                    $sender->sendMessage( $this->title . " 해당 플레이어는 존재하지 않습니다 !");
                    return true;
                }
        }
    }

    public function loadYml() {
        @mkdir( $this->getDataFolder() );
        $this->config = new Config( $this->getDataFolder() . "config.yml", Config::YAML, [
            "NameTagWarning"=>true,
            "WarningBan"=>true,
            "BanCount"=>3 ] );
        $this->configData = $this->config->getAll();
        $this->Database = new Config( $this->getDataFolder() . "Database.yml", Config::YAML );
        $this->userData = $this->Database->getAll();
    }

    public function saveYml() {
        $this->Database->setAll( $this->userData );
        $this->Database->save();
    }

}

?>