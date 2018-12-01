<?php
namespace PSPUI;

use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket; 
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\Player;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;


class ui extends PluginBase implements Listener {

public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents ($this, $this);
		@mkdir ( $this->getDataFolder () );
      //오프: 0 온: 1
      $this->onoff = new Config($this->getDataFolder() . "onoff.yml", Config::YAML,[
      "#Warnning: number of 명령어 갯수 has to match the array of Cban.yml",
      "채팅" => "0",
      "공격" => "0",
      "블럭" => "0",
      "움직임" => "0",
      "명령어" => "0",
      "명령어갯수" => 0
         ]);
         $this->data = $this->onoff->getAll();
         $this->cban = new Config($this->getDataFolder() . "CBan.yml", Config::YAML);
         $this->cb = $this->cban->getAll();
         $this->cb[0] = "sv";
         $this->save();
}

public function onMove(PlayerMoveEvent $event) {
	$player = $event->getPlayer();
    if (! $player->isOp ()) {
		if($this->data["움직임"] == "1"){
			$player->sendMessage("§cMovement is locked by Admib");
              $event->setCancelled();
           }
		}
	}
	
public function onPCP(PlayerCommandPreprocessEvent $event) {
	$player = $event->getPlayer();
	$cmd = $event->getMessage();
    if (! $player->isOp ()) {
		if($this->data["명령어"] == "1"){
			$player->sendMessage("§cCommand is locked by Admin");
              $event->setCancelled();
           }
        for($i=0;$i<$this->data["명령어갯수"];$i++){
        	if($cmd == "/".$this->cb[$i]){
               $player->sendMessage("§cThis command is locked by Admin");
              $event->setCancelled();
              break;
           }
        }
		}
	}
	
public function place(BlockPlaceEvent $event){
	$player = $event->getPlayer();
    if (! $player->isOp ()) {
		if($this->data["블럭"] == "1"){
			$player->sendMessage("§cInstalling blocks are locked by Admin");
              $event->setCancelled();
           }
		}
	}
	
	public function onChat(PlayerChatEvent $event) {
	$player = $event->getPlayer();
    if (! $player->isOp ()) {
		if($this->data["채팅"] == "1"){
			$player->sendMessage("§cChatting is locked by Admin");
              $event->setCancelled();
           }
		}
	}
	
	public function EntityDamageEvent_Player(EntityDamageEvent $event){
		   if($event instanceof EntityDamageByEntityEvent){
            if($event->getEntity() instanceof Player and $event->getDamager() instanceof Player){
            	$player = $event->getDamager();
                if (! $player->isOp ()) {
		            if($this->data["채팅"] == "1"){
			           $player->sendMessage("§cPVP is locked by Admin");
                       $event->setCancelled();
                    }
		          }
	           }
            }
           }
        

public function sendUI(Player $p, $c, $d) {
		$pack = new ModalFormRequestPacket();
		$pack->formId = $c;
		$pack->formData = $d;
		$p->dataPacket($pack);
	}
	
	public function OpenUiF() {
		$a = $this->data["채팅"];
	if($a == "1") {
		$a = "§clocked";
		}
	else{
		$a = "§aopened";
		}
		
		$b = $this->data["공격"];
	if($b == "1") {
		$b = "§clocked";
		}
	else{
		$b = "§aopened";
		}
		
		$c = $this->data["블럭"];
	if($c == "1") {
		$c = "§clocked";
		}
	else{
		$c = "§aopened";
		}
		
		$d = $this->data["움직임"];
	if($d == "1") {
		$d = "§clocked";
		}
	else{
		$d = "§aopened";
		}
	$e = $this->data["명령어"];
	if($e == "1") {
		$e = "§clocked";
		}
	else{
		$e = "§aopened";
		}
         $encode = [
		"type" => "form",
		"title" => "§l§cServer Menu",
		"content" => "§l<< Server Menu >>\n\nVersion 1.4\n\nChat lock: ".$a."\n\n§fPVP lock: ".$b."\n\n§fIntalling block lock: ".$c."\n\n§fMovement lock: ".$d."\n\n§fCommand lock: ".$e,
		"buttons" => [
		[
		"text" => "Open Menu",
		],
		[
		"text" => "Broadcast Message",
		],
		[
		"text" => "Open Command ban menu",
		],
		[
		"text" => "Exit",
		]
		]
		];
		return json_encode($encode);
	}
	
public function OpenUiCB() {
	
$encode = [
		"type" => "form",
		"title" => "§l§cCommand Menu",
		"content" => "§lCommand menu\n\n",
		"buttons" => [
		[
		"text" => "Open Command ban list",
		],
		[
		"text" => "Ban Command",
		],
		[
		"text" => "Delete banned command",
		],
		[
		"text" => "Main",
		],
		[
		"text" => "Exit",
		]
		]
		];
		return json_encode($encode);
	}

public function OpenUi() {
		
         $encode = [
		"type" => "form",
		"title" => "§cMenu",
		"content" => "Server Menu",
		"buttons" => [
		[
		"text" => "Lock Chatting",
		],
		[
		"text" => "Lock PVP",
		],
		[
		"text" => "Lock Block install",
		],
		[
		"text" => "Lock Movement",
		],
		[
		"text" => "Lock all command",
		],
		[
		"text" => "Main",
		],
		[
		"text" => "Exit",
		]
		]
		];
		return json_encode($encode);
	}

public function OpenUi2() {
	$a = $this->data["채팅"];
	if($a == "1") {
		$a = "§l§clocked";
		}
	else{
		$a = "§l§aopened";
		}
		$encode = [
		"type" => "form",
		"title" => "§l§aChat Lock panel",
		"content" => "§lLock status: ".$a ,
		"buttons" => [
		[
		"text" => "§l§cLock",
		],
		[
		"text" => "§l§aOpen",
		],
		[
		"text" => "§lExit",
		]
		]
		];
		return json_encode($encode);
	}
	
	public function OpenUi3() {
	$a = $this->data["공격"];
	if($a == "1"){
		$a = "§c§llocked";
		}
	else{
		$a = "§a§lopened";
		}
		$encode = [
		"type" => "form",
		"title" => "§l§aPVP Lock panel",
		"content" => "§lPVP Lock status: ".$a ,
		"buttons" => [
		[
		"text" => "§l§cLock",
		],
		[
		"text" => "§l§aOpen",
		],
		[
		"text" => "§lExit",
		]
		]
		];
		return json_encode($encode);
	}
	
	public function OpenUi4() {
	$a = $this->data["블럭"];
	if($a == "1"){
		$a = "§l§clocked";
		}
	else{
		$a = "§a§lopened";
		}
		$encode = [
		"type" => "form",
		"title" => "§l§aLock Block Install",
		"content" => "§lLock status: ".$a ,
		"buttons" => [
		[
		"text" => "§l§cLock",
		],
		[
		"text" => "§l§aOpen",
		],
		[
		"text" => "§lExit",
		]
		]
		];
		return json_encode($encode);
	}
	
	public function OpenUi5() {
	$a = $this->data["움직임"];
	if($a == "1"){
		$a = "§c§llocked";
		}
	else{
		$a = "§a§lopened";
		}
		$encode = [
		"type" => "form",
		"title" => "§l§aMovement Lock panel",
		"content" => "§lLock status: ".$a ,
		"buttons" => [
		[
		"text" => "§l§cLock",
		],
		[
		"text" => "§l§aOpen",
		],
		[
		"text" => "§lExit",
		]
		]
		];
		return json_encode($encode);
	}

public function OpenUi6() {
	
		$encode = [
		"type" => "custom_form",
		"title" => "§lBroadcast Message",
		"content" => [
		[
		"type" => "input",
		"text" => "§lInput Message to Broadcast\n",
		]
		]
		];
		return json_encode($encode);
	}
	
public function OpenUi100() {
	
		$encode = [
		"type" => "custom_form",
		"title" => "§l§cAdd Command to Ban",
		"content" => [
		[
		"type" => "input",
		"text" => "§l§c※Input Command to Ban.\n(slash '/' should not be inputted)\n",
		]
		]
		];
		return json_encode($encode);
	}
	
public function OpenUi1000() {
	
		$encode = [
		"type" => "custom_form",
		"title" => "§l§cAdd Commabd to Ban",
		"content" => [
		[
		"type" => "input",
		"text" => "§l§c※Input Command to Ban.\n(slash '/' should not be inputted)\n\n*essential input\n",
		]
		]
		];
		return json_encode($encode);
	}
	
public function OpenUi101() {
	
		$encode = [
		"type" => "custom_form",
		"title" => "§l§cDelete Banned Command",
		"content" => [
		[
		"type" => "input",
		"text" => "§l§c※Input Banned Command to Delete.\n(slash '/' should not be inputted)\n",
		]
		]
		];
		return json_encode($encode);
	}
	
public function OpenUi1010() {
	
		$encode = [
		"type" => "custom_form",
		"title" => "§l§cDelete Banned Command",
		"content" => [
		[
		"type" => "input",
		"text" => "§l§c※Input Banned Command to Delete.\n(slash '/' should not be inputted)\n\n*essential input\n",
		]
		]
		];
		return json_encode($encode);
	}
	
public function OpenUi102($a) {
	
		$encode = [
		"type" => "form",
		"title" => "§l§cAdd Comand to Banned",
		"content" => "§lSucessfuly Added.\n\n§fAdded Command:\n \n ".$a ,
		"buttons" => [
		[
		"text" => "§lMain",
		],
		[
		"text" => "§lExit",
		]
		]
		];
		return json_encode($encode);
	}
	
public function OpenUi103($a) {
	
		$encode = [
		"type" => "form",
		"title" => "§l§cDeleted Banned Command",
		"content" => "§lSucessfuly Deleted.\n\n§fDeleted Command: \n\n ".$a ,
		"buttons" => [
		[
		"text" => "§lMain",
		],
		[
		"text" => "§lExit",
		]
		]
		];
		return json_encode($encode);
	}

public function OpenUi8( $a ) {
	
		$encode = [
		"type" => "form",
		"title" => "§l§aBroadcast Message",
		"content" => "§lMessage Broadcasted.\n\n§fMessage: \n ".$a ,
		"buttons" => [
		[
		"text" => "§lMain",
		],
		[
		"text" => "§lExit",
		]
		]
		];
		return json_encode($encode);
	}

public function OpenUi9() {
$a = $this->data["명령어"];
	if($a == "1"){
		$a = "§c§llocked";
		}
	else{
		$a = "§a§lopened";
		}
		$encode = [
		"type" => "form",
		"title" => "§l§aBan All Command panel",
		"content" => "§lLock status: ".$a ,
		"buttons" => [
		[
		"text" => "§l§cLock",
		],
		[
		"text" => "§l§aOpen",
		],
		[
		"text" => "§lExit",
		]
		]
		];
		return json_encode($encode);
	}

public function onDataPacketRecieve(DataPacketReceiveEvent $event) {
		$packet = $event->getPacket();
		$player = $event->getPlayer();
		if ($packet instanceof ModalFormResponsePacket) {
			$id = $packet->formId;
			$a = json_decode($packet->formData, true);
			
			if ($id === 12345) {
			if ($a === 0) {//메뉴
					$this->sendUI($player, 22345, $this->OpenUi());
					return;
				} 
			
			else if ($a === 1) {//공지
				$this->sendUI($player, 1115, $this->OpenUi6());
				return;
				}
			
			else if($a === 2){//명령어 메뉴
				$this->sendUI($player, 55555, $this->OpenUiCB());
				return;
				}
			
			else if($a === 3){//나가기
				$player->sendMessage("§l정상적으로 나갔습니다");
				return;
				}
			}
			
			if ($id === 55555) {
				
			if ($a === 0) {//밴 목록
			  $player->sendMessage("§l§cBanned Command List: \n");
					for($i=0;$i<$this->data["명령어갯수"];$i++){
						if($this->cb[$i] != "deleted command"){
                           $player->sendMessage("/".$this->cb[$i]."\n");
                           }
                    }
					return;
				} 
			
			else if ($a === 1) {//밴 추가
				$this->sendUI($player, 9998, $this->OpenUi100());
				return;
				}
			
			else if($a === 2){//밴 제거
				$this->sendUI($player, 9999, $this->OpenUi101());
				return;
				}
			
			else if($a === 3){//메인
				$this->sendUI($player, 12345, $this->OpenUiF());
				return;
				}
			
			else if($a === 4){//나가기
				$player->sendMessage("§lExited Sucessfuly");
				return;
				}
			}
			
			else if ($id === 9998) {//밴추가
            	if (!isset ($a[0])) {
					$this->sendUI($player, 12345, $this->OpenUi7());
					return;
					}
				else {
					if($a[0] != ""){
					$this->cb[$this->data["명령어갯수"]] = $a[0];
					$this->data["명령어갯수"]++;
					$this->save();
					$this->sendUI($player, 9997, $this->OpenUi102($a[0]));
					return;
					}
					else{
						$this->sendUI($player, 999900, $this->OpenUi1000());
						return;
						}
					return;
					}
				}
			
			else if ($id === 999900) {//밴추가 오류
            	if (!isset ($a[0])) {
					$this->sendUI($player, 12345, $this->OpenUi7());
					return;
					}
				else {
					if($a[0] != ""){
					$this->cb[$this->data["명령어갯수"]] = $a[0];
					$this->data["명령어갯수"]++;
					$this->save();
					$this->sendUI($player, 9997, $this->OpenUi102($a[0]));
					return;
					}
					else{
						$this->sendUI($player, 999900, $this->OpenUi1000());
						return;
						}
					return;
					}
				}
			
			else if ($id === 9999) {//밴제거
            	if (!isset ($a[0])) {
					$this->sendUI($player, 12345, $this->OpenUi7());
					return;
					}
				else {
					if($a[0] != ""){
					for($i=0;$i<$this->data["명령어갯수"];$i++){
        	           if($this->cb[$i] == "$a[0]"){
                           $this->cb[$i] = "deleted command";
                           $this->save();
                           $this->sendUI($player, 9996, $this->OpenUi103($a[0]));
                           break;
                         }
                     }
					return;
					}
					else {
                    $this->sendUI($player, 999901, $this->OpenUi1010());
                      }
					}
				}
				
			else if ($id === 999901) {//밴제거 오류
            	if (!isset ($a[0])) {
					$this->sendUI($player, 12345, $this->OpenUi7());
					return;
					}
				else {
					if($a[0] != ""){
					for($i=0;$i<$this->data["명령어갯수"];$i++){
        	           if($this->cb[$i] == "$a[0]"){
                           $this->cb[$i] = "deleted command";
                           $this->save();
                           return;
                         }
                     }
					$this->sendUI($player, 9996, $this->OpenUi103($a[0]));
					return;
					}
					else {
                    $this->sendUI($player, 999901, $this->OpenUi1010());
                    return;
                      }
					}
				}
			
			else if ($id === 9997){//밴 확인
            	if($a === 0)
            {
            	$this->sendUI($player, 12345, $this->OpenUiF());
            	return;
            }
            else if($a === 1){
           $player->sendMessage("§lExited Sucessfuly");
            return;
              }
          }    
            else if ($id === 9996){//밴 제거 확인
            	if($a === 0)
            {
            	$this->sendUI($player, 12345, $this->OpenUiF());
            	return;
            }
            else if($a === 1){
           $player->sendMessage("§lExited Sucessfuly");
            return;
              }
			}
             else if ($id === 22345) {//메뉴
				
                if ($a === 0) {//채팅
					$this->sendUI($player, 54321, $this->OpenUi2());
					return;
				} 
                
                else if ($a === 1) {//공격
					$this->sendUI($player, 1112, $this->OpenUi3());
					return;
				}
				
				else if ($a === 2) {//블럭
					$this->sendUI($player, 1113, $this->OpenUi4());
					return;
				}
				
				else if ($a === 3) {//움직임
					$this->sendUI($player, 1114, $this->OpenUi5());
					return;
				}
				else if ($a === 4) {//명령어
					$this->sendUI($player, 1120, $this->OpenUi9());
					return;
				}
				else if ($a === 5) {//돌아가기
					$this->sendUI($player, 12345, $this->OpenUiF());
					return;
				}
				else if ($a === 6) {//나가기
					$player->sendMessage("§lExited Sucessfuly");
					return;
				}
              }
              
            else if ($id === 54321){
            	if($a === 2)
            {
            	$player->sendMessage("§lExited Sucessfuly");
            	return;
            }
            else if($a === 0){
         $this->data["채팅"] = "1";
            $this->save();
            $player->sendMessage("§c§lChatting Banned");
            $this->sendUI($player, 12345, $this->OpenUiF());
            return;
             }
            else if($a === 1){
            $this->data["채팅"] = "0";
            $this->save();
            $player->sendMessage("§a§lChatting Opened");
            $this->sendUI($player, 12345, $this->OpenUiF());
            return;
                 }
              }
              
              else if ($id === 1112){
            	if($a === 2)
            {
            	$player->sendMessage("§lExited Sucessfuly");
            	return;
            }
            else if($a === 0){
           $this->data["공격"] = "1";
            $this->save();
            $player->sendMessage("§c§lPVP Locked");
            $this->sendUI($player, 12345, $this->OpenUiF());
            return;
              }
            else if($a === 1){
            $this->data["공격"] = "0";
            $this->save();
            $player->sendMessage("§a§lPVP Opened");
            $this->sendUI($player, 12345, $this->OpenUiF());
            return;
                 }
              }
              
              else if ($id === 1120){
            	if($a === 2)
            {
            	$player->sendMessage("§lExited Sucessfuly");
            	return;
            }
            else if($a === 0){
           $this->data["명령어"] = "1";
            $this->save();
            $player->sendMessage("§c§lLocked Command Use");
            $this->sendUI($player, 12345, $this->OpenUiF());
            return;
              }
            else if($a === 1){
            $this->data["명령어"] = "0";
            $this->save();
            $player->sendMessage("§a§lOpened Command Use");
            $this->sendUI($player, 12345, $this->OpenUiF());
            return;
                 }
              }
              
              else if ($id === 1113){
            	if($a === 2)
            {
            	$player->sendMessage("§lExited Sucessfuly");
            	return;
            }
            else if($a === 0){
            $this->data["블럭"] = "1";
            $this->save();
            $player->sendMessage("§c§lBlock Install Locked");
            $this->sendUI($player, 12345, $this->OpenUiF());
            return;
            }
            else if($a === 1){
            $this->data["블럭"] = "0";
            $this->save();
            $player->sendMessage("§a§lBlock Install Opened");
            $this->sendUI($player, 12345, $this->OpenUiF());
            return;
            }
              }
              
              else if ($id === 1114){
            	if($a === 2)
            {
            	$player->sendMessage("§lExited Sucessfuly");
            	return;
            }
            else if($a === 0){
            $this->data["움직임"] = "1";
            $this->save();
            $player->sendMessage("§c§lMovement Locked");
            $this->sendUI($player, 12345, $this->OpenUiF());
            return;
            }
            else if($a === 1){
            $this->data["움직임"] = "0";
            $this->save();
            $player->sendMessage("§a§lMovement Opened");
            $this->sendUI($player, 12345, $this->OpenUiF());
            return;
            }
              }
              
            else if ($id === 1115) {//공지하기
            	if (!isset ($a[0])) {
					$this->sendUI($player, 1117, $this->OpenUi7());
					return;
					}
				else {
					$this->getServer()->broadcastMessage("§l§a[ §fServer §a]§f ".$a[0]);
					$this->sendUI($player, 1117, $this->OpenUi8($a[0]));
					return;
					}
				}
				
			else if ($id === 1116) {//명령어 밴
 
				if(isset ($a[0])){
					$this->getServer()->broadcastMessage("§l§a[ §c명령어밴 §a]§f ".$a[0]);
					$this->cb[$this->data["명령어갯수"]] = $a[0];
					$this->data["명령어갯수"] = $this->data["명령어갯수"] + 1;
					$this->save();
					$this->sendUI($player, 12345, $this->OpenUiF());
					return;
					}
				}
				
				else if ($id === 1117){//전체공지 확인
            	if($a === 0)
            {
            	$this->sendUI($player, 12345, $this->OpenUiF());
            	return;
            }
            else if($a === 1){
           $player->sendMessage("§lExited Sucessfuly");
            return;
              }
              }
            }
         }
         
         
         
public function onCommand(Commandsender $sender, Command $command, string $label, array $args) : bool{
		if ($command->getName() === "sv") {
			if(!$sender instanceof Player) {
        $sender->sendMessage ("§c§lProhibited in Console" );
        return true;
        }
        if (! $sender->isOp ()) {
        	$sender->sendMessage ("§cNo permission to use this command" );
       return true;
        }
			$this->sendUI($sender, 12345, $this->OpenUiF());
		}
		return true;
	}
	
public function save(){
		$this->onoff->setAll($this->data);
		$this->onoff->save();
		$this->cban->setAll($this->cb);
		$this->cban->save();
	}
}
