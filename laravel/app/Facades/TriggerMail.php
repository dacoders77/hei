<?php

namespace App\Facades;

use App\Http\Controllers\TriggerMail\TriggerMailController as TriggerMailController;

class TriggerMail
{
    /**
     * Send mail class wrapper.
     * Some emails as claim creation notification, etc, are called from SubmissionController_1.php
     *
     * @param $method
     * @param array $args
     * @return mixed
     */
	public static function send($method,$args=[])
	{
		$class = new TriggerMailController();
		return $class->$method($args);
	}
}