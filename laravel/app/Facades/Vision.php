<?php

namespace App\Facades;

use Log;
use Exception;

class Vision
{
	public static function OCR($image,$type='file',$fullJSON=false)
	{
	    try {
	    	$detection_type = "TEXT_DETECTION";

	    	if($type=='file'){
				$image = file_get_contents($image);
				$image_base64 = base64_encode($image);
			} else if($type=='image'){
				$image_base64 = base64_encode($image);
			} else if($type=='base64'){
				$image_base64 = $image;
			} else {
				throw new Exception("Image not set", 1);
			}

			$json_request ='{
		        "requests": [
		            {
		              "image": {
		                "content":"' . $image_base64. '"
		              },
		              "features": [
		                  {
		                    "type": "' .$detection_type. '",
		                    "maxResults": 1
		                  }
		              ]
		            }
		        ]
		    }';

		    $response = self::curl($json_request);

		    if ( !isset($response['responses'][0]['textAnnotations'][0]['description']) ) {
				throw new Exception("Google Vision Error: No text found", 1);
			}

			return !$fullJSON?$response['responses'][0]['textAnnotations'][0]['description']:$response;

	    } catch (Exception $e) {
	    	Log::error($e);
	    }

	    return false;
	}

	private static function curl($json_request) {
		$api_key = 'AIzaSyCSIdb4WbrELmGjWKa5C48w8U0-XIYOTIY';
		$endpoint = "https://vision.googleapis.com/v1/images:annotate?key={$api_key}";

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec ($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);

        if ( $status != 200 ) {
			throw new Exception("Google Vision Error: Status code: $status", 1);
			return false;
		}

		return json_decode($output,true);
    }
}