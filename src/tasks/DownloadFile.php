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

namespace DavidGlitch04\BetterCapes\tasks;

use DavidGlitch04\BetterCapes\utils\CapeUtils;
use Exception;
use GdImage;
use pocketmine\player\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;
use function imagecreatefromstring;

/**
 * Class DownloadFile
 * @package DavidGlitch04\BetterCapes\tasks
 */
class DownloadFile extends AsyncTask {

	/**
	 * @param string $playername
	 * @param string $url
	 */
	public function __construct(
        private string $playername,
        private string $url
    ) {}


	/**
	 * @return void
	 */
	public function onRun() : void {
		$raw = Internet::getURL($this->url)->getBody();
		$this->setResult($raw);
	}


	/**
	 * @return void
	 */
	public function onCompletion() : void {
		$image = imagecreatefromstring($this->getResult());
		try{
			$player = Server::getInstance()->getPlayerByPrefix($this->playername);
			if ($image instanceof GdImage and $player instanceof Player) {
				CapeUtils::setCape($player, $image);
			}
		} catch (Exception $e){
			Server::getInstance()->getLogger()->warning('[BetterCapes]: '.$e->getMessage());
		}
	}
}