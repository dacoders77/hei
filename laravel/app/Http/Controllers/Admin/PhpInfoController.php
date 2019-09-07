<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PhpInfoController extends Controller
{
	public function __construct()
    {
    	ini_set('max_execution_time', '9999');
	    ini_set('max_input_time', '9999');
	    ini_set('max_input_vars', '99999');
	    ini_set('memory_limit', '9999M');
	}

	public function phpinfo()
	{
		phpinfo();
		die();
	}
}