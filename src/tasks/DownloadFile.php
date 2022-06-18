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
    /** @var string $playername */
	public string $playername;
    /** @var string $url */
	public string $url;

	/**
	 * @param string $playername
	 * @param string $url
	 */
	public function __construct(string $playername, string $url) {
		$this->playername = $playername;
		$this->url = $url;
	}


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
		$player = Server::getInstance()->getPlayerExact($this->playername);
		if ($image instanceof GdImage and $player instanceof Player) {
			CapeUtils::setCape($player, $image);
		}
	}
}
?>