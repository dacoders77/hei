<?php

namespace App\Facades;

use App\Classes\LogToFile;

class SMS
{

	public static function send($destination,$text)
	{
	    LogToFile::add(__FILE__, 'Stopped in SMS.php');
	    return;

		$username = config('sms.username');
	    $password = config('sms.password');
	    $source    = config('sms.source');
	    $ref = substr( date('U').uniqid(), 0, 19 );

	    $content =  'username='.rawurlencode($username).
	                '&password='.rawurlencode($password).
	                '&to='.rawurlencode($destination).
	                '&from='.rawurlencode($source).
	                '&message='.rawurlencode($text).
	                '&ref='.rawurlencode($ref).
	                '&maxsplit='.rawurlencode(3);

	    $smsbroadcast_response = self::curl($content);
	    $response_lines = explode("\n", $smsbroadcast_response);

	    $errors = [];

	    foreach( $response_lines as $data_line){
	        $message_data = "";
	        $message_data = explode(':',$data_line);
	        if($message_data[0] == "OK"){
	            return [
	            	'status' => 200,
	            	'message' => "The message to ".$message_data[1]." was successful, with reference ".$message_data[2]
	            ];
	        }elseif( $message_data[0] == "BAD" ){
	            $errors[] = "The message to ".$message_data[1]." was NOT successful. Reason: ".$message_data[2];
	        }elseif( $message_data[0] == "ERROR" ){
	            $errors[] = "There was an error with this request. Reason: ".$message_data[1];
	        }
	    }

	    if($errors) throw new \Exception(json_encode($errors), 1);
	}

	private static function curl($content) {
        $ch = curl_init(config('sms.endpoint'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec ($ch);
        curl_close ($ch);
        return $output;
    }
}