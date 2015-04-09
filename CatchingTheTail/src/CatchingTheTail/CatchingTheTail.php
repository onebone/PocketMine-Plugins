<?php

namespace CatchingTheTail;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\Player;

class CatchingTheTail extends PluginBase implements Listener {
	
	public $playerList = array();
	public $gameBoolean = false;
	public $playerCount;
	public $bannedPlayerList = array();
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents( $this, $this );
	}
	
	public function onCommand (CommandSender $sender, Command $command, $label, array $args) {
				if ( isset ( $args[0] ) ) {
					switch ( $args[0] ) {
						
						case "start":
							if ( $this->gameBool == false ) {
								if ( count($gamePlayers) >= 3 ) {
									gameStart();
								} else {
									$sender->sendMessage( "[꼬리잡기] 플레이어는 최소 3명이상 등록해주세요." );
								}
							} else {
								$sender->sendMessage( "[꼬리잡기] 게임은 이미 시작되었습니다 :)" );
							}
							
						case "stop":
							if ( $this->gameBool == true ) {
								$this->gameBool = false;
								$this->getServer()->broadcastMessage( "[꼬리잡기] 관리자가 게임을 중지하였습니다." );
							} else {
								$sender->sendMessage( "[꼬리잡기] 게임을 시작한적이 없는대 어떻게 중지하라는 건지 ㅋㅋ" );
							}
							
						case "add":
							if ( isset ( $args[1] ) ) {
								array_push( $this->playerList, $args[1] );
							}
							
						case "remove":
							if ( isset( $args[1] ) ) {
								if ( in_array ( $args[1], $this->playerList ) ) {
									$key = array_search( $args[1], $this->playerList );
									unset( $this->playerList[$key] );
									$this->playerList = array_values( $this->playerList );
								} else {
									$sender->sendMessage ( "[꼬리잡기] 해당 플레이어는 등록되지 않았습니다." );
								}
							}
							
						case "list":
							if ( count( $gamePlayers ) > 0 ) {
								$sender->sendMessage( "[꼬리잡기] 플레이어 리스트 : " . $this->playerList );
							} else {
								$sender->sendMessage( "[꼬리잡기] 등록된 플레이어가 없습니다." );
							}
							
					}
				}
	}
	
	public function gameStart() {
		$this->gameBoolean = true;
		$this->getServer()->broadcastMessage( "[꼬리잡기] 관리자가 게임을 시작하였습니다." );
		array_shuffle( $this->playerList );
		showTarget();
	}
	
	public function showTarget() {
		$this->playerCount = count( $gamePlayers ) - 1 ;
		while ( $this->playerCount >= 0 ) {
			if ( $this->playerCount == count( $this->playerList ) - 1 ) {
				$this->playerList[$this->playerCount]->sendMessage( "당신의 타겟은 " . $this->playerList[0] . " 입니다." );
				$this->playerList[$this->playerCount]->sendMessage( "좌표 : " . $this->playerList[0] );
			} else {
				$this->playerList[$this->playerCount]->sendMessage( "당신의 타겟은 " . $gamePlayers[$countOfPlayer + 1] . " 입니다." );
				$this->playerList[$this->playerCount]->sendMessage( "좌표 : " . $gamePlayers[$countOfPlayer + 1] );
			}
			$countOfPlayer -- ;
		}
	}
	
}

?>