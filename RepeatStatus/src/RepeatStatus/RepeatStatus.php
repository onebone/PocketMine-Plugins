<?php

namespace RepeatStatus;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\scheduler\CallbackTask;

class RepeatStatus extends PluginBase {

	public function onEnable() {
		@mkdir( $this->getDataFolder () );
		$this->config = new Config( $this->getDataFolder () . "config.yml", Config::YAML, ["repeat-minute" => 1, "repeat-second" => 0] );
		$this->configData = $this->config->getAll();
		$this->callback = $this->getServer()->getScheduler()->scheduleRepeatingTask( new CallbackTask( [ $this,"repeatServerStatus" ] ), ( $this->configData[ "repeat-minute" ] * 1200 ) + ( $this->configData["repeat-second"] * 20 ) );
	}
	
	public function repeatServerStatus() {
		$tps = ( $this->getServer()->getTicksPerSecond() ) *5 ;
		$allPlayers = count($this->getServer()->getOnlinePlayers());
		$maxPlayer = $this->getServer()->getMaxPlayers();
		$this->getServer()->broadcastMessage( "  Server Status  " );
		$this->getServer()->broadcastMessage( "===============" );
		$this->getServer()->broadcastMessage( "서버안정도 : " . $tps . "%" );
		$this->getServer()->broadcastMessage( "OnlinePlayers : " . $allPlayer . "/" . $maxPlayer );
		$this->getServer()->broadcastMessage( "===============" );
	}
	
}

?>