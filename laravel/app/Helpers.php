<?php

if( !function_exists('methodExists') ) {
	function methodExists($controller,$id){
		$controller = str_replace("::", "_{$id}@", $controller);

		// Get callback
        [$class, $method] = \Illuminate\Support\Str::parseCallback($controller);

        if (!class_exists($class)) return false;

        $class = new $class();

        $reflector = new \ReflectionMethod($class, $method);

		if( $reflector->getDeclaringClass()->getName() == get_class($class) ) {
			return $controller;
		} else {
			return false;
		}
	}
}

if( !function_exists('call_func') ) {
	function call_func($controller, $params=[]) {
		if( is_string($params) ) $params = [$params];

        // Get callback
        [$class, $method] = \Illuminate\Support\Str::parseCallback($controller, null);

        // Run callback
        return call_user_func_array([new $class, $method], $params);
    }
}



if( !function_exists('post_status') ) {
	function post_status($num){
		switch ($num) {
			case '1':
				$status = 'Published';
				break;

			case '2':
				$status = 'Removed';
				break;

			default:
				$status = 'Draft';
				break;
		}

		return $status;
	}
}


if( !function_exists('admin_user') ) {
	function admin_user($num){
		switch ($num) {
			case '1':
				$status = 'Super Admin';
				break;

			case '2':
				$status = 'Admin';
				break;

			case '3':
				$status = 'Manager';
				break;

			default:
				$status = 'User';
				break;
		}

		return $status;
	}
}

if( !function_exists('link_active') ) {
	function link_active($route, $strict = false){
		$link = route($route);
		$current = url()->current();

		if ($strict && $current == $link) {
			return 'active';
		} else if (!$strict && substr($current, 0, strlen($link)) === $link) {
			return 'active';
		}
	}
}

if( !function_exists('campaign_from_domain') ) {
	function campaign_from_domain($domain,$slug = '/'){
		if(!$domain) return;

		$id = DB::table('posts')->where([
			['domain', $domain],
			['slug', str_start($slug, '/')],
			['status', 1]
		])->value('id');

		return $id;
	}
}


if( !function_exists('created_at') ) {
	function created_at($created_at, $format = 'F jS, Y'){
		return date($format, strtotime($created_at));
	}
}

if( !function_exists('toAlpha') ) {
	function toAlpha($n,$case = 'upper'){
	    $alphabet   = array('A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z');
	    if($n <= 24){
	        $alpha =  $alphabet[$n-1];
	    } elseif($n > 24) {
	        $dividend   = ($n);
	        $alpha      = '';
	        $modulo;
	        while($dividend > 0){
	            $modulo     = ($dividend - 1) % 24;
	            $alpha      = $alphabet[$modulo].$alpha;
	            $dividend   = floor((($dividend - $modulo) / 24));
	        }
	    }

	    if($case=='lower'){
	        $alpha = strtolower($alpha);
	    }
	    return $alpha;
	}
}

if( !function_exists('userUUID') ) {
	function userUUID($id = 1,$cid = 1){
		return str_pad($cid, 3, toAlpha($cid), STR_PAD_LEFT).'-'.str_pad(toAlpha($id), 6, 0, STR_PAD_LEFT);
	}
}

if( !function_exists('submissionUUID') ) {
	function submissionUUID($id = 1,$cid = 1){
		return str_pad($cid, 3, toAlpha($cid), STR_PAD_LEFT).'-'.str_pad($id, 6, 0, STR_PAD_LEFT);
	}
}

if( !function_exists('countConsumers') ) {
	function countConsumers($status = 'all', $cid){

		$users = User::whereIn('id', function($query) use ($cid) {
	    	return $query->select('user_id')
	    	    ->from('users_meta')
	    	    ->where([
		    		['meta_key','campaign_id'],
		    		['meta_value',$cid],
		    	]);
	    });

		if($status !== 'all') {
			if(is_array($status)){
				$users = $users->whereIn('id', function($query) use ($status) {
			    	return $query->select('user_id')
			    	    ->from('users_meta')
			    	    ->where('meta_key', '=', 'status')
			    	    ->whereIn('meta_value', $status);
			    });
			} else {
				$users = $users->whereIn('id', function($query) use ($status) {
			    	return $query->select('user_id')
			    	    ->from('users_meta')
			    		->where('users_meta.meta_key', '=', 'status')
			    		->where('users_meta.meta_value', '=', $status);
			    });
			}
		}

		return $users->count();

		// return count($users);
	}
}


if( !function_exists('getStatus') ) {
	function getStatus($status){
		$return = null;
		foreach (config('status') as $s) {
			if($s['index'] == $status || $s['label'] == $status){
				$return = $s;
				break;
			}
		}
		return $return;
	}
}

if( !function_exists('_json') ) {
	function _json($key,$object,$default=null){
		if(!is_object($object)) {
			$object = json_decode($object);
			if(json_last_error() !== JSON_ERROR_NONE)
				return $default;
		}
		if(isset($object->{$key}))
			return $object->{$key};
		return $default;
	}
}



if( !function_exists('_jsonsecure') ) {
	function _jsonsecure($key,$object,$default=null){
		try {
			$object = Crypt::decrypt($object);
		} catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
			return $default;
		}

		$object = json_decode($object);
		if(json_last_error() !== JSON_ERROR_NONE)
			return $default;

		if(isset($object->{$key}))
			return $object->{$key};

		return $default;
	}
}


if( !function_exists('_jsondecrypt') ) {
	function _jsondecrypt($object,$default=null){
		try {
			$object = Crypt::decrypt($object);
		} catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
			return $default;
		}

		$object = json_decode($object);
		if(json_last_error() !== JSON_ERROR_NONE)
			return $default;

		return $object;
	}
}



if( !function_exists('checkFile') ) {
	function checkFile($name,$path)
	{
		$actual_name = pathinfo($name,PATHINFO_FILENAME);
        $original_name = $actual_name;
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        $i = 1;
        while ( file_exists($path.$actual_name.".".$extension) ) {
            $actual_name = (string)$original_name.$i;
            $name = $actual_name.".".$extension;
            $i++;
        }

        return $name;
	}
}


// @see https://stackoverflow.com/a/35223390
if( !function_exists('csvToArray') ) {
	function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header){
                    $header = str_replace(' ', '_', $row);
                }else{
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }
}

if( !function_exists('arrayToCSV') ) {
	function arrayToCSV(array $fields) : string {
	    $f = fopen('php://memory', 'r+');
	    foreach ($fields as $field) {
	    	if (fputcsv($f, $field) === false) {
		        return false;
		    }
	    }
	    rewind($f);
	    $csv_line = stream_get_contents($f);
	    return rtrim($csv_line);
	}
}



if( !function_exists('getRealIpAddr') ) {
	function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}


if( !function_exists('totalClaimsAmount') ) {
	function totalClaimsAmount($submissions){
		return array_sum(
	        array_map( function($submission) {
	          return $submission['submission_meta']['claim_amount'];
	        },
	          json_decode(
	            $submissions->toJSON()
	          , true)
	        )
	    );
	}
}


if( !function_exists('shortUrl') ) {
	function shortUrl($v,$c) {
		$characters = str_repeat('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 6);
		$padding = substr(str_shuffle($characters), 0, 6);
		return str_pad(toAlpha($v,'lower') . toAlpha($c), 6, $padding, STR_PAD_RIGHT);
	}
}


if( !function_exists('jsFormHasFile') ) {
	function jsFormHasFile($content){
		if( !$content ) return false;
		if( is_array($content) ) {
			$content = json_encode($content);
		}
		return strpos($content, '"type":"file"') !== false ? true : false;
	}
}


if( !function_exists('shortcode2HTML') ) {
	function shortcode2HTML($shortcode){
		$shortcode = preg_replace('/\[(.+?)(\s.+?)?\](.+?)\[\/(.+?)\]/', '<$1$2>$3</$4>', $shortcode);
		$shortcode = preg_replace('/\[(.+?)(\s.+?)?\]/', '<$1$2>', $shortcode);
		return $shortcode;
	}
}


if( !function_exists('maxDate') ) {
	function maxDate($date1,$date2,$format=false){
		$maxDate = max(strtotime($date1),strtotime($date2));
		if($format) $maxDate = date($format,$maxDate);
		return $maxDate;
	}
}


if( !function_exists('minDate') ) {
	function minDate($date1,$date2,$format=false){
		$minDate = min(strtotime($date1),strtotime($date2));
		if($format) $minDate = date($format,$minDate);
		return $minDate;
	}
}

if( !function_exists('slugify') ) {
	function slugify($string){
		return strtolower(preg_replace('/[^a-z0-9\-]+/i', '-', str_replace('/', '--', $string)));
	}
}


if( !function_exists('human_time_diff') ) {
	function human_time_diff($from,$to=false){
		$seconds_hours = 60*60;
		$hours_days = $seconds_hours*24;

		if(!$to) $to = date('d-m-Y H:i:s');

		$difference = strtotime($to)-strtotime($from);
		if ($difference>$hours_days) {
		    $date1 = new DateTime(substr($to,0,10));
		    $date2 = new DateTime(substr($from,0,10));
		    $since = $date2->diff($date1)->format("%d");
		    if ($since==1) { $since .= ' day'; }
		    else { $since .= ' days'; }
		} else if ($difference>$seconds_hours) {
		    $since = floor($difference/$seconds_hours);
		    if ($since==1) { $since .= ' hour'; }
		    else { $since .= ' hours'; }
		} else if ($difference>60) {
		    $since = floor($difference/60);//mins
		    if ($since==1) { $since .= ' minute'; }
		    else { $since .= ' minutes'; }
		} else {
			$since = $difference;//mins
			if ($since==1) { $since .= ' second'; }
		    else { $since .= ' seconds'; }
		}

		return $since;
	}
}

if( !function_exists('validateDate') ) {
	function validateDate($date, $format = 'd-m-Y') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}