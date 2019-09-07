<?php

namespace App\Facades;

use App\Http\Controllers\TriggerMail\TriggerMailController as TriggerMailController;

class TriggerMail
{
	public static function send($method,$args=[])
	{
		$class = new TriggerMailController();
		return $class->$method($args);
	}
}