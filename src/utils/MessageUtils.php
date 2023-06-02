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

namespace DavidGlitch04\BetterCapes\utils;

use DavidGlitch04\BetterCapes\Main;
use pocketmine\utils\TextFormat;

/**
 * Class MessageUtils
 * @package DavidGlitch04\BetterCapes\utils
 */
final class MessageUtils {

	/**
	 * @param string $msg
	 * 
	 * @return string
	 */
	public static function getMessage(string $msg) : string {
		$msg = Main::$messages->get($msg, 'nothing');
		return TextFormat::colorize($msg);
	}
}