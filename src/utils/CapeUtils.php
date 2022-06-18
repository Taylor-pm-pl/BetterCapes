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

namespace DavidGlitch04\BetterCapes\utils;

use GdImage;
use pocketmine\entity\Skin;
use pocketmine\player\Player;
use function chr;
use function imagecolorat;
use function imagesx;
use function imagesy;
use function is_null;
use function strlen;

/**
 * Class CapeUtils
 * @package DavidGlitch04\BetterCapes\utils
 */
final class CapeUtils {

	/**
	 * @param Player $player
	 * @param GdImage $image
	 * 
	 * @return void
	 */
	public static function setCape(Player $player, GdImage $image) : void {
		$rgba = "";
		for ($y = 0; $y < imagesy($image); $y++) {
			for ($x = 0; $x < imagesx($image); $x++) {
				$argb = imagecolorat($image, $x, $y);
				$rgba .= chr(($argb >> 16) & 0xff) . chr(($argb >> 8) & 0xff) . chr($argb & 0xff) . chr(((~((int) ($argb >> 24))) << 1) & 0xff);
			}
		}
		if (strlen($rgba) !== 8192) {
			$player->sendMessage(MessageUtils::getMessage('invalid-cape'));
			return;
		}
		$oldSkin = $player->getSkin();
		$newSkin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $rgba, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
		$player->setSkin($newSkin);
		$player->sendSkin();
		$player->sendMessage(MessageUtils::getMessage('change-success'));
	}
}