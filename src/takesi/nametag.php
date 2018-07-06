<?php

namespace takesi;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;
use pocketmine\utils\Config;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

//error_reporting(0);
class nametag extends PluginBase implements Listener{

    public function onEnable() {
		$this->getLogger()->notice("これはtakesiによる自作プラグインです。");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!file_exists($this->getDataFolder())){
			mkdir($this->getDataFolder(), 0744, true);
			}
	}

	
	 public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$this->config = new Config($this->getDataFolder()."config.json", Config::JSON);
		if($this->config->exists($player->getName())){
			$player->setDisplayName("[".$this->config->get($player->getName())."§r] ".$player->getName());
			$player->setNameTag("[".$this->config->get($player->getName())."§r] ".$player->getName());
		}
	}

    public function onCommand(CommandSender $sender,Command $command,string $label,array $args) :bool {
		switch($command->getName()){
		case "nametag":
		if(!isset($args[0])){
				$sender->sendMessage("====NameTagコマンドの使用方法======");
				$sender->sendMessage("/nametag <つけるネームタグ>");
				return true;
		}else{
		if(!isset($args[1])){
			$player = $this->getServer()->getPlayer($args[0]);
			if($player == null){
				if(strpos($args[0],'§e公式') === false){
					if(strlen($args[0]) < 50){
						$sender->setDisplayName("[".$args[0]."§r] ".$sender->getName());
						$sender->setNameTag("[".$args[0]."§r] ".$sender->getName());
						$this->config = new Config($this->getDataFolder()."config.json", Config::JSON);
						$this->config->set($sender->getName(),$args[0]);
						$this->config->save();
						$sender->sendMessage("§l§eネームタグ管理システム>>変更完了！");
					}else{
						$sender->sendMessage("§l§eネームタグ管理システム>>25文字以上は禁止です。");
					}
				}else{
					$sender->sendMessage("§l§eネームタグ管理システム>>こちらの称号は使用できません");
				}
			}
			return true;
		}else{
			if($sender->isOp()){
				$player = $this->getServer()->getPlayer($args[0]);
				if($player == null){
					$sender->sendMessage("§l§eネームタグ管理システム>>そのプレイヤーは存在しません");
				}else{
					$player->setDisplayName("[".$args[1]."§r] ".$player->getName());
					$player->setNameTag("[".$args[1]."§r] ".$player->getName());
					$this->config = new Config($this->getDataFolder()."config.json", Config::JSON);
					$this->config->set($player->getName(),$args[1]);
					$this->config->save();
					$sender->sendMessage("§l§eネームタグ管理システム>>変更完了！");
				}
			}else{
				$sender->sendMessage("§l§eネームタグ管理システム>>権限がありません");
			}
			return true;
		}
		return true;
		}
		}
		}	
}