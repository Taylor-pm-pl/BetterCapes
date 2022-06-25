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
 * @link https://github.com/DavidGlitch04/BetterCapes
 *
 *
*/

declare(strict_types=1);

namespace DavidGlitch04\BetterCapes\command;

use DavidGlitch04\BetterCapes\Main;
use DavidGlitch04\BetterCapes\tasks\DownloadFile;
use DavidGlitch04\BetterCapes\utils\CapeUtils;
use DavidGlitch04\BetterCapes\utils\MessageUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
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
				$extension = substr($args[0], -4);
				if ($extension === ".png") {
					Server::getInstance()->getAsyncPool()->submitTask(new DownloadFile($sender->getName(), $args[0]));
				} else {
					$sender->sendMessage(MessageUtils::getMessage('invalid-link'));
				}
			} else {
				$path = $this->getOwningPlugin()->getDataFolder() . $args[0] . ".png";
				if (!file_exists($path)) {
					$sender->sendMessage(MessageUtils::getMessage('invalid-cape'));
					return;
				}
				$image = imagecreatefrompng($path);
				CapeUtils::setCape($sender, $image);
			}
		} else {
			$sender->sendMessage('Please use this command in-game');
		}
	}
}
