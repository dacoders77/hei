<?php

namespace App\Facades;

use Illuminate\Support\Str;
use Illuminate\Contracts\Container\Container;

class Test
{

	public static function go($callback,$params=[])
	{
		[$class, $method] = Str::parseCallback($callback, 'test');
		return call_user_func_array([new $class, $method], $params);
	}
}