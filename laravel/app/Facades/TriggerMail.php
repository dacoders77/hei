<?php

namespace App\Facades;

use App\Classes\LogToFile;
use App\Http\Controllers\TriggerMail\TriggerMailController as TriggerMailController;

class TriggerMail
{
    /**
     * Send mail class wrapper.
     * Some emails as claim creation notification, etc, are called from SubmissionController_1.php
     *
     * @param $method
     * @param array $args
     * @param array @text
     * @return mixed
     */
	public static function send($method, $args=[], $text=[])
	{
		$class = new TriggerMailController($text);
		// TriggerMailController->submissionStatus($args)
		return $class->$method($args);
	}
}