<?php

/*
 *
 * ██████╗░███████╗████████╗████████╗███████╗██████╗░░█████╗░░█████╗░██████╗░███████╗░██████╗
 * ██╔══██╗██╔════╝╚══██╔══╝╚══██╔══╝██╔════╝██╔══██╗██╔══██╗██╔══██╗██╔══██╗██╔════╝██╔════╝
 * ██████╦╝█████╗░░░░░██║░░░░░░██║░░░█████╗░░██████╔╝██║░░╚═╝███████║██████╔╝█████╗░░╚█████╗░
 * ██╔══██╗██╔══╝░░░░░██║░░░░░░██║░░░██╔══╝░░██╔══██╗██║░░██╗██╔══██║██╔═══╝░██╔══╝░░░╚═══██╗
 * ██████╦╝███████╗░░░██║░░░░░░██║░░░███████╗██║░░██║╚█████╔╝██║░░██║██║░░░░░███████╗██████╔╝
 * ╚═════╝░╚══════╝░░░╚═╝░░░░░░╚═╝░░░╚══════╝╚═╝░░╚═╝░╚════╝░╚═╝░░╚═╝╚═╝░░░░░╚══════╝╚═════╝░
 * This plugin for PocketMine-MP: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author DavidGlitch04
 * @link https://github.com/Taylor-pm-pl/BetterCapes
 *
 *
*/

declare(strict_types=1);

namespace DavidGlitch04\BetterCapes\command;

use DavidGlitch04\BetterCapes\Main;
use DavidGlitch04\BetterCapes\tasks\DownloadFile;
use DavidGlitch04\BetterCapes\utils\CapeUtils;
use DavidGlitch04\BetterCapes\utils\MessageUtils;
use Exception;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

use function file_exists;
use function filter_var;
use function imagecreatefrompng;
use function substr;

/**
 * Class Capes
 * @package DavidGlitch04\BetterCapes\command
 */
class Capes extends Command implements PluginOwned {
    /** @var Main $plugin */
	protected Main $plugin;

	/**
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
		parent::__construct(MessageUtils::getMessage('command'));
		$this->setDescription(MessageUtils::getMessage('description'));
		$this->setPermission('bettercapes.command.allow');
	}

	/**
	 * @return Plugin
	 */
	public function getOwningPlugin() : Plugin {
		return $this->plugin;
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * 
	 * @return void
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args): void {
		if ($sender instanceof Player) {
			if (!isset($args[0])) {
				$sender->sendMessage(MessageUtils::getMessage('usage'));
				return;
			}
			if (filter_var($args[0], FILTER_VALIDATE_URL)) {
				if(!$sender->hasPermission(Main::$messages->get('link-cape-perm')) && !$this->isOp($sender)){
					$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command!");
					return;
				}
				$extension = substr($args[0], -4);
				if ($extension === ".png") {
					Server::getInstance()->getAsyncPool()->submitTask(new DownloadFile($sender->getName(), $args[0]));
				} else {
					$sender->sendMessage(MessageUtils::getMessage('invalid-link'));
				}
			} elseif(!$this->testCapePermission($sender, $args[0]) && !$this->isOp($sender)){
				$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command!");
			} else {
                try{
                    $path = $this->plugin->getDataFolder() . $args[0] . ".png";
                    if (!file_exists($path)) {
                        $sender->sendMessage(MessageUtils::getMessage('invalid-cape'));
                        return;
                    }
                    $image = imagecreatefrompng($path);
                    CapeUtils::setCape($sender, $image);
                } catch (Exception $e){
                    $sender->sendMessage(MessageUtils::getMessage('error'));
                    $this->plugin->getLogger()->warning($e->getMessage());
                }
            }
		} else {
			$sender->sendMessage('Please use this command in-game');
		}
	}

	public function isOp(Player $player): bool{
		return Server::getInstance()->isOp($player->getName());
	}

	public function testCapePermission(Player $player, string $capeName): bool{
		$permission = str_replace(
			'{capename}', 
			$capeName, 
			Main::$messages->get('default-cape-perm')
		);
		return $player->hasPermission($permission);
	}
}
