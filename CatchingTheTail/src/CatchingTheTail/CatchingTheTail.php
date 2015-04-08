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
	
	public $gamePlayers = array();
	public $gameBool = false;
	public $countOfPlayer;
	public $bannedPlayer = array();
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents( $this, $this );
	}
	
	public function onCommand ($sender, $command, $label, $args) {
		
		switch ( $command->getName() ) {
			
			case "ct":
				if ( isset ( $args[0] ) ) {
					
					switch ( $args[0] ) {
						
						case "start":
							if ( $gameBool == false ) {
								if ( count($gamePlayers) >= 3 ) {
									gameStart();
								} else {
									$sender->sendMessage( "[꼬리잡기] 플레이어는 최소 3명이상 등록해주세요." );
								}
							} else {
								$sender->sendMessage( "[꼬리잡기] 게임은 이미 시작되었습니다 :)" );
							}
							
						case "stop":
							if ( $gameBool == true ) {
								$gameBool = false;
								$this->getServer()->broadcastMessage( "[꼬리잡기] 관리자가 게임을 중지하였습니다." );
							} else {
								$sender->sendMessage( "[꼬리잡기] 게임을 시작한적이 없는대 어떻게 중지하라는 건지 ㅋㅋ" );
							}
							
						case "add":
							if ( isset ( $args[1] ) ) {
								array_push ( $gamePlayers, $args[1] );
							} else {
								$sender->sendMessage( "[꼬리잡기] 사용법: /ct add [닉네임]" );
								return true;
							}
							
						case "remove":
							if ( in_array ( $args[1], $gamePlayers ) ) { #삭제하려는 닉네임이 배열에 있는지 확인
								
							} else {
								$sender->sendMessage ( "[꼬리잡기] 해당 플레이어를 찾을 수 없습니다." );
							}
							
						case "list":
							if ( count( $gamePlayers ) > 0 ) {
								$sender->sendMessage( "[꼬리잡기] 플레이어 리스트 : " . $gamePlayers );
							} else {
								$sender->sendMessage( "[꼬리잡기] 등록된 플레이어가 없습니다." );
							}
							
					}
					
				} else {
					$sender->sendMessage( "[꼬리잡기] 사용법: /ct <start|stop|add|remove|list>" );
					return true;
				}
				
		}
		
	}
	
	public function gameStart() {
		$gameBool = true;
		$this->getServer()->broadcastMessage( "[꼬리잡기] 관리자가 게임을 시작하였습니다." );
		array_shuffle( $gamePlayers );
		showTarget();	
	}
	
	public function onDeath( EntityDeathEvent $event ) {
		$deathPlayer = $event->getEntity()->getName();
		if ( in_array( $deathPlayer, $gamePlayers ) ) {
			$this->getServer()->broadcastMessage("[꼬리잡기] 플레이어 " . $deathPlayer . " 가 사망하였습니다.");
			$this->getServer()->broadcastMessage("[꼬리잡기] 플레이어가 사망하여 타겟을 랜덤으로 변경합니다.");
			array_shuffle( $gamePlayers );
			showTarget();
		}
	}
	
	public function showTarget() {
		
		$countOfPlayer = count( $gamePlayers ) - 1 ;
		
		while ( $countOfPlayers >= 0 ) {
			if ( $countOfPlayer == count( $gamePlayers ) - 1 ) {
				$gamePlayers[$countOfPlayer]->sendMessage( "당신의 타겟은 " . $gamePlayers[0] . " 입니다." );
				$gamePlayers[$countOfPlayer]->sendMessage( "좌표 : " . $gamePlayers[0] );
			} else {
				$gamePlayers[$countOfPlayer]->sendMessage( "당신의 타겟은 " . $gamePlayers[$countOfPlayer + 1] . " 입니다." );
				$gamePlayers[$countOfPlayer]->sendMessage( "좌표 : " . $gamePlayers[$countOfPlayer + 1] );
			}
			$countOfPlayer -- ;
		}
	}
	
}

?>