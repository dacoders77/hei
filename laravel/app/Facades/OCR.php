<?php

namespace App\Facades;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OCR
{

	public static function init($file)
	{
		$ocr = new TesseractOCR();
		$ocr->image($file);
		return $ocr;
	}

	// ['psm'=>11]

	public static function read($file,$config=[])
	{
		$ocr = self::init($file);
		if($config && is_array($config))
		{
			foreach ($config as $key => $value) {
				$ocr->{$key}($value);
			}
		}
		return $ocr->run();
	}
}