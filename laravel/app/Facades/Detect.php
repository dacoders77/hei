<?php

namespace App\Facades;

use Detection\MobileDetect;

class Detect
{
	public static function __callStatic($method,$args) {
		$MobileDetect = new MobileDetect;
		return $MobileDetect->{$method}($args);
	}
}