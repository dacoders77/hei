<?php

namespace App\Http\Controllers\Campaigns;

use App\Classes\LogToFile;
use App\Http\Controllers\Campaigns\SubmissionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Submission;
use SubmissionMeta;
use Validator;
use Campaign;
use Response;
use TriggerMail;
use Vision;
use Timestamp;
use Voucher;
use OCR;
use Exception;
use Imagick;
use Crypt;

class SubmissionController_1 extends SubmissionController
{


	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function store(Request $request, $id)
	{

        //return redirect()->route('dash');
        //return(['message' => '123msg from SubmissionController_1']);
        //return view('campaigns.index_1', ['campaign' => \App\Model\Campaigns\Campaign::find(1)]);
        //return view(['ca' => \App\Model\Campaigns\Campaign::find(1)]);

        //return;

		// Get Campaign Form Fields
		$campaign = Campaign::find($id);
		$campaign_form_fields = $campaign->meta('form_content');

		// Process Data
        //LogToFile::add(__FILE__, json_encode($request, JSON_PRETTY_PRINT));
        //LogToFile::add(__FILE__, print_r($request->all(), true));

        //LogToFile::add(__FILE__, file_get_contents("php://input")); // Show request body as text by PPP
        //parse_str(file_get_contents("php://input"), $a);
        //LogToFile::add(__FILE__, json_encode($a, JSON_PRETTY_PRINT)); // Works good

        $data = $this->process_data( $request->all() );
        LogToFile::add(__FILE__, json_encode($data, JSON_PRETTY_PRINT));

        //$data = $this->process_data($a);

		// Setup Validation Fields
		$validation = [
			'rules' => [],
			'messages' => [],
		];
		$files = [];

		$validate_autocomplete_address = '';

		foreach ($campaign_form_fields as $field) {
			if(!isset($field->name)) continue;

			if( $field->type == 'email' ) {
				$validation['rules'][$field->name][] = 'email';
				$validation['messages'][$field->name.'.email'] = 'Must be a valid email address';
			}
			if( $field->type == 'number' ) {
				$validation['rules'][$field->name][] = 'numeric';
			}
			if( $field->type == 'date' ) {
				$validation['rules'][$field->name][] = 'datepicker:'.implode(',',array_filter([(isset($field->startDate)?'min:'.$field->startDate:null),(isset($field->endDate)?'max:'.$field->endDate:null)]));

				if(isset($data[$field->name])&&$data[$field->name]!=''){
					if( validateDate($data[$field->name],'d/m/Y') ) {
						$data[$field->name] = date('d-m-Y',strtotime(str_replace('/', '-', $data[$field->name])));
					} else if( validateDate($data[$field->name],'d-m-Y') || validateDate($data[$field->name],'m/d/Y') || validateDate($data[$field->name],'Y-m-d') ) {
						$data[$field->name] = date('d-m-Y',strtotime($data[$field->name]));
					}
				}
			}
			if( $field->type !== 'file' && $field->type !== 'autocomplete-address' ) {
				if( isset($field->required) ) {
					$validation['rules'][$field->name][] = 'required';
					$validation['messages'][$field->name.'.required'] = 'This field is required';
				} else {
					$validation['rules'][$field->name][] = 'nullable';
				}
			}
			if( $field->type == 'recaptcha' ) {
				$validation['rules']['g-recaptcha-response'] = ['required'];
				$validation['messages']['g-recaptcha-response.required'] = 'Please confirm you\'re human';
			}

			if( $field->type == 'autocomplete-address' && isset($field->required) ) {
				$validate_autocomplete_address = $field->name;

				$validation['rules'][$field->name.'_line_1'][] = 'required';
				$validation['messages'][$field->name.'_line_1.required'] = 'This field is required';

				$validation['rules'][$field->name.'_suburb'][] = 'required';
				$validation['messages'][$field->name.'_suburb.required'] = 'This field is required';

				$validation['rules'][$field->name.'_state'][] = 'required';
				$validation['messages'][$field->name.'_state.required'] = 'This field is required';

				$validation['rules'][$field->name.'_postcode'] = ['required','numeric','min:0','max:9999'];
				$validation['messages'][$field->name.'_postcode.required'] = 'This field is required';
				$validation['messages'][$field->name.'_postcode.numeric'] = 'Invalid postcode';
			}

			if( preg_match('/_confirm$/', $field->name) ) {
				$testName = preg_replace('/_confirm$/', '', $field->name);
				$validation['rules'][$field->name][] = 'same:'.$testName;
				$validation['messages'][$field->name.'.same'] = 'Confirmation does not match';
			}

			if( $field->type == 'file' ) {
				$preg_search = ['/\s*\,\s*/', '/\//', '/\*/'];
				$preg_replace = ['|^', '\/', '.*'];

				$files[] = (object) [
					'name' => $field->name,
					'accept' => isset($field->accept) && !empty($field->accept) ? '/^'.trim( preg_replace($preg_search, $preg_replace, $field->accept) ).'/i' : false,
					'required' => isset($field->required) ? true : false,
					'maxsize' => 5194304,
				];
			}

		}

		// Honeypot
		$validation['rules']['_mn'] = 'honeypot';
		//$validation['rules']['_mt'] = 'required|honeytime:5';
        $validation['rules']['_mt'] = '';

		// Setup Validator with set fields
		$validator = Validator::make($request->all(), $validation['rules'], $validation['messages']);

		if( $files ) {
			$storage_path = storage_path('app/private').'/campaigns/'.str_pad($id, 2, 0, STR_PAD_LEFT).'/'.date('Y/m').'/';
	        $public_url = '/storage/private/campaigns/'.str_pad($id, 2, 0, STR_PAD_LEFT).'/'.date('Y/m').'/';

			foreach ($files as &$field) {

				if( Input::hasFile($field->name) ) {
					$file = Input::file($field->name);
					if( $file->isValid() && $file->getSize() > 5194304 ) {
						$field->error = 'File size must be less than 5MB.';
					} elseif( isset($field->accept) && !empty($field->accept) && $file->isValid() ){
						if( preg_match($field->accept,$file->getMimeType()) ) {
							$filename = checkFile( $file->getClientOriginalName(), $storage_path );
							$field->mime_type = $file->getMimeType();
			                $file->move($storage_path, $filename);
			                $field->file = $public_url . $filename;
						} else {
							$field->error = 'Invalid file type';
						}
					} elseif( $file->isValid() ) {
						$filename = checkFile( $file->getClientOriginalName(), $storage_path );
						$field->mime_type = $file->getMimeType();
		                $file->move($storage_path, $filename);
		                $field->file = $public_url . $filename;
					} else {
						$field->error = 'File failed to upload';
					}
				} else if( $field->required ) {
					$field->error = 'Upload is required';
				}
			}
		}

		// Additional Validations
		$validator->after(function ($validator) use ($request, $data, $id, $campaign, $files, $validate_autocomplete_address) {

			if( $campaign->hasCaptcha() ) {
				$reCaptcha = new \App\Validators\ReCaptcha();
				$recaptchaResponse = $reCaptcha->validate(null,$request->{'g-recaptcha-response'},null,null);

				if(!$recaptchaResponse) {
					$validator->errors()->add('g-recaptcha-response', 'Oops! Something went wrong');
				}
			}

			if( $files ) {
				foreach ($files as $field) {
					if(isset($field->error)) {
						// Set Error
						$validator->errors()->add($field->name,$field->error);
					}
				}
			}

			/* 08.09.19 Disabled by Boris. Testing */
			/*if($this->exclusionList($data['payer_number'])) {
				$validator->errors()->add('payer_number','Sorry this Customer Number is invalid.');
			} else {
				// Check against mobile
				$checkPayerNumber = Submission::where('campaign_id',$id)
					->whereMetaValue([
						['status','REGEXP','^[1-9]$'],
						['payer_number',$data['payer_number']],
					])->count();

				if($checkPayerNumber >= 4) {
					$validator->errors()->add('payer_number','Limit of 4 entries per Customer Number.');
				}
			}*/


			// Check Invoice Total
            /* 08.09.19 Disabled by Boris. Testing */
			/*if(floatval(preg_replace('/[^0-9\.]+/', '', $data['invoice_total'])) < 250) {
				$validator->errors()->add('invoice_total','Invoice Total needs to be $250 or above');
			}*/

			if($validate_autocomplete_address) {
				foreach ($validator->errors()->toArray() as $key => $value) {
					if(substr( $key, 0, strlen($validate_autocomplete_address)+1 ) === $validate_autocomplete_address.'_') {
						$validator->errors()->add('_'.$validate_autocomplete_address,'This field is required');
						break;
					}
				}

			}

		});

		// If Validation fails
		if ($validator->fails()) {
			if($request->ajax()) {
				return Response::json([
					'success' => false,
					'errors' => $validator->getMessageBag()->toArray()
				], 400);
			} else {
				return redirect()->back()->withErrors($validator)->withInput();
			}
        }

        // Reformat Invoice Total
        /* 08.09.19 Disabled by Boris. Testing */
        /*$data['invoice_total'] = '$'.preg_replace('/([0-9]{2})$/','.$1',intval(floatval(preg_replace('/[^0-9\.]+/', '', $data['invoice_total']))*100));*/

        // Reformat Phone Number
        $data['phone'] = preg_replace('/^04/','+614',preg_replace('/[^0-9]+/', '', $data['phone']));

        // OCR
        $receiptData = [
			'ocr' => null,
			'date' => null,
			'invoice' => null,
			'payer' => null,
			'total' => null,
		];

		try {

			$isDulux = null;
			$isInvoice = null;
			$isPayer = null;
        	$isDate = null;
        	$isTotal = null;
        	$ocrArray = null;

        	if( $files[0]->mime_type !== 'application/pdf' ) {

	        	// Read Image
	    		$ocrResponse = Vision::OCR( storage_path(preg_replace('/^\/storage\//','/app/',$files[0]->file)), 'file', true )?:'';

	    	} else {

	    		// Convert PDF using Imagick
				$im = new Imagick();

				$im->setResolution(300,300);
				$im->readimage( storage_path(preg_replace('/^\/storage\//','/app/',$files[0]->file)) );
				$im->setImageFormat('jpeg');

				$pdfJPG = $im->getimageblob();

				$im->clear();
				$im->destroy();

				// Read Image
	    		$ocrResponse = Vision::OCR( $pdfJPG, 'image', true )?:'';

	    	}

    		if(!$ocrResponse) throw new Exception("Vision could not read invoice.", 1);

    		$receiptData['ocr'] = $ocrResponse['responses'][0]['textAnnotations'][0]['description'];

    		// Convert lines to Array
        	$ocrArray = array_values(array_filter(explode("\n",$receiptData['ocr'])));

        	$ocrTypeOneTest = 0;

        	foreach ($ocrArray as $i => $line) {
        		$sim = similar_text($line, 'Customer address', $perc);
        		if($perc > 60){
        			$ocrTypeOneTest += $perc;
        			continue;
        		}

        		$sim = similar_text($line, 'Docket number', $perc);
        		if($perc > 60){
        			$ocrTypeOneTest += $perc;
        			continue;
        		}

        		$sim = similar_text($line, 'Date invoiced', $perc);
        		if($perc > 60){
        			$ocrTypeOneTest += $perc;
        			continue;
        		}

        		$sim = similar_text($line, 'Project no.', $perc);
        		if($perc > 60){
        			$ocrTypeOneTest += $perc;
        			continue;
        		}

        		$sim = similar_text($line, 'Delivery instructions', $perc);
        		if($perc > 60){
        			$ocrTypeOneTest += $perc;
        			continue;
        		}

        		$sim = similar_text($line, 'Original invoice no.', $perc);
        		if($perc > 60){
        			$ocrTypeOneTest += $perc;
        			continue;
        		}
        	}

        	if($ocrTypeOneTest/6 >= 60) {
        		// Loop Array
	    		foreach ($ocrArray as $i => $line) {

	    			// Trim out empty lines from array
	    			if(trim($line)=='') {
	    				unset($ocrArray[$i]);
	    				continue;
	    			}

	    			if(!$isDulux || !$isInvoice || !$isDate || !$isPayer || !$isTotal) {

		    			// $line = strtolower($line);

		    			// Check
		    			$sim = similar_text($line, 'Dulux', $perc);
		    			if(!$isDulux && $perc > 50){
		    				$isDulux = true;
		    				continue;
		    			}

		    			// Check
		    			$sim = similar_text($line, 'Docket number', $perc);
		    			if(!$isInvoice && $perc > 85 && preg_match('/[0-9]+/', $ocrArray[$i+1])){
	        				$isInvoice = strtoupper( preg_replace('/[^a-z0-9\-]+/i', '', $ocrArray[$i+1]) );
	        				continue;
	        			}

		    			// Check
		    			$sim = similar_text($line, 'Customer no.', $perc);
		    			if(!$isPayer && $perc > 90 && preg_match('/[0-9]+/', $ocrArray[$i+1])){
	        				$isPayer = preg_replace('/[^0-9]+/', '', $ocrArray[$i+1]);
	        				continue;
	        			}

	        			// Check
	        			if(!$isDate && preg_match('/[0-9]+/', $line) && preg_match('/\//',$line)){
	        				if($date = strtotime(str_replace('/','-',preg_replace('/[^0-9\/\s\:]+/', '', $line)))) {
	        					$isDate = date('d-m-Y',$date);
	        				}
	        				continue;
	        			}

	        			// Check
		    			$sim = similar_text($line, 'Account', $perc);
		    			if(!$isTotal && $perc > 90 && preg_match('/[0-9]+/', $ocrArray[$i+1])){
	        				$isTotal = '$'.preg_replace('/[^0-9\.]+/', '', $ocrArray[$i+1]);
	        				continue;
	        			}
	        			if(!$isTotal && $perc > 90 && preg_match('/[0-9]+/', $ocrArray[$i-1])){
	        				$isTotal = '$'.preg_replace('/[^0-9\.]+/', '', $ocrArray[$i-1]);
	        				continue;
	        			}

	        		}
	    		}

	    		if(!$isPayer) {
	    			foreach ($ocrArray as $i => $line) {
	    				if(preg_replace('/[^0-9]+/', '', $line) == $data['payer_number']) {
	    					$isPayer = preg_replace('/[^0-9]+/', '', $line);
	    					break;
	    				}
	    			}
	    		}

	    		if(!$isTotal) {
	    			foreach ($ocrArray as $i => $line) {
	    				if('$'.preg_replace('/[^0-9\.]+/', '', $line) == $data['invoice_total']) {
	    					$isTotal = '$'.preg_replace('/[^0-9\.]+/', '', $line);
	    					break;
	    				}
	    			}
	    		}

	    		if(!$isTotal) {
		    		// Check
		    		$blocks = [];
		    		foreach ($ocrResponse['responses'][0]['fullTextAnnotation']['pages'][0]['blocks'] as $block) {
		    			$paragraphs = [];
		    			foreach ($block['paragraphs'] as $paragraph) {
		    				$words = [];
		    				foreach ($paragraph['words'] as $word) {
		    					$chars = [];
		    					foreach($word['symbols'] as $symbol) {
		    						$chars[] = $symbol['text'];
		    					}
		    					$words[] = implode('', $chars);
		    				}
		    				$paragraphs[] = implode(' ', $words);
		    			}
		    			$blocks[] = implode(' ', $paragraphs);
		    		}

		    		// Check
		    		foreach ($blocks as $i => $block) {
		    			if(!$isTotal && preg_match('/TOTAL INC GST/', $block) && preg_match('/[0-9]+/', $block)) {
		    				$isTotal = '$'.preg_replace('/[^0-9\.]+/', '', $block);
		    				break;
		    			}
		    		}

		    	}

        	} else {
        		// Loop Array
	    		foreach ($ocrArray as $i => $line) {

	    			// Trim out empty lines from array
	    			if(trim($line)=='') {
	    				unset($ocrArray[$i]);
	    				continue;
	    			}

	    			if(!$isDulux || !$isInvoice || !$isDate || !$isPayer) {

		    			// $line = strtolower($line);

		    			// Check
		    			$sim = similar_text($line, 'Dulux', $perc);
		    			if(!$isDulux && $perc > 50){
		    				$isDulux = true;
		    				continue;
		    			}

		    			// Check
		    			$sim = similar_text($line, 'Payer Number:', $perc);
		    			if(!$isPayer && $perc > 65 && preg_match('/[0-9]+/', $line)){
	        				$isPayer = preg_replace('/[^0-9]+/', '', $line);
	        				continue;
	        			}

		    			// Check
	        			$sim = similar_text($line, 'Tax Invoice No.:', $perc);
	        			if(!$isInvoice && $perc > 65 && preg_match('/[0-9]+/', $line)){
	        				$isInvoice = strtoupper(preg_replace('/[^a-z0-9\-]+/i', '', $line));
	        				continue;
	        			}

	        			// Check
	        			$sim = similar_text($line, 'Invoice Date:', $perc);
	        			if(!$isDate && $perc > 50 && preg_match('/[0-9]+/', $line) && preg_match('/\//',$line)){
	        				if($date = strtotime(str_replace('/','-',preg_replace('/[^0-9\/]+/', '', $line)))) {
	        					$isDate = date('d-m-Y',$date);
	        				}
	        				continue;
	        			}

	        		}
	    		}

	    		// Check
	    		$blocks = [];
	    		// error_log(print_r($ocrResponse['responses'][0]['fullTextAnnotation']['pages'][0]['blocks'],1));
	    		foreach ($ocrResponse['responses'][0]['fullTextAnnotation']['pages'][0]['blocks'] as $block) {
	    			$paragraphs = [];
	    			foreach ($block['paragraphs'] as $paragraph) {
	    				$words = [];
	    				foreach ($paragraph['words'] as $word) {
	    					$chars = [];
	    					foreach($word['symbols'] as $symbol) {
	    						$chars[] = $symbol['text'];
	    					}
	    					$words[] = implode('', $chars);
	    				}
	    				$paragraphs[] = implode(' ', $words);
	    			}
	    			$blocks[] = implode(' ', $paragraphs);
	    		}

	    		// error_log(print_r($blocks,1));

	    		// Check
	    		foreach ($blocks as $i => $block) {
	    			$sim = similar_text($block, 'Total Amount Payable Inc GST', $perc);
	    			if(!$isTotal && $perc > 50 && preg_match('/[0-9]+/', $block) && preg_match('/\$/',$block)) {
	    				$isTotal = '$'.preg_replace('/[^0-9\.]+/', '', $block);
	    				break;
	    			}
	    			if(!$isTotal && $perc > 65) {
					    for ($n=1; $n <= 6; $n++) {
					        if(preg_match('/[0-9]+/', $blocks[$i+$n]) && preg_match('/\$/',$blocks[$i+$n])) {
						        $isTotal = '$'.preg_replace('/[^0-9\.]+/', '', $blocks[$i+$n]);
						        break 2;
					        }
					    }
					}
	    		}
        	}

    		// Trim out empty lines from array
    		$ocrArray = array_values(array_filter($ocrArray));


        	$receiptData['date'] = $isDate;
			$receiptData['invoice'] = $isInvoice;
			$receiptData['total'] = $isTotal;
			$receiptData['payer'] = $isPayer;

			if(!$isDulux){
				$data['flag_ocr_dulux'] = 'OCR Error: Could not find Dulux in invoice.';
			}
			if(!$isDate){
				$data['flag_ocr_date'] = 'OCR Error: Could not read Invoice Date.';
			}
			if(!$isInvoice){
				$data['flag_ocr_invoice'] = 'OCR Error: Could not read Invoice Number.';
			}
			if(!$isTotal){
				$data['flag_ocr_total'] = 'OCR Error: Could not read Invoice Total.';
			}
			if(!$isPayer){
				$data['flag_ocr_payer'] = 'OCR Error: Could not read Customer Number.';
			}
        } catch (Exception $e) {
        	$receiptData['ocr'] = 'OCR Error: '.$e->getMessage();
        	// $data['flag_ocr_fail'] = 'OCR Error: '.$e->getMessage();
        }

        // Additional Validations
		if($receiptData['payer'] && $receiptData['payer'] !== $data['payer_number']) {
			$data['flag_payer_number'] = 'OCR Customer Number reads '.$receiptData['payer'].'.';
			$data['flag_color'] = 'red';
		}

		if($receiptData['payer']) {
			$checkPayerNumber = Submission::where('campaign_id',$id)
				->whereMetaValue([
					['status','REGEXP','^[1-9]$'],
					['payer_number',$receiptData['payer']],
				])->count();

			if($checkPayerNumber >= 4) {
				$data['flag_payer_number'] = 'OCR Customer Number reads '.$receiptData['payer'].' and has reached the 4 entry limit.';
				$data['flag_color'] = 'red';
			}
		}

		if($receiptData['payer'] && $this->exclusionList($receiptData['payer'])) {
			$data['flag_payer_number'] = 'OCR Customer Number was found in the exclusion list!';
			$data['flag_color'] = 'red';
		}

		// Check Invoice Total
		if($receiptData['total']){
			if(floatval(preg_replace('/[^0-9\.]+/', '', $receiptData['total'])) < 250) {
				$data['flag_invoice_total'] = 'OCR Invoice Total reads '.$receiptData['total'].', which is below $250.';
				$data['flag_color'] = 'red';
			} else if(floatval(preg_replace('/[^0-9\.]+/', '', $receiptData['total'])) !== floatval(preg_replace('/[^0-9\.]+/', '', $data['invoice_total']))) {
				$data['flag_invoice_total'] = 'OCR Invoice Total reads '.$receiptData['total'].'.';
				$data['flag_color'] = 'red';
			}
		}

		// Check Invoice Date
		if($receiptData['date']){
			if( strtotime($receiptData['date']) < strtotime('26-08-2019') || strtotime($receiptData['date']) > strtotime('20-09-2019') ) {
				$data['flag_invoice_date'] = 'OCR Invoice Date reads '.$receiptData['date'].', which is outside the purchase period.';
				$data['flag_color'] = 'red';
			} else if( $receiptData['date'] !== $data['purchase_date'] ) {
				$data['flag_invoice_date'] = 'OCR Invoice Date reads '.$receiptData['date'].'.';
				$data['flag_color'] = 'red';
			}
		}

		// Check Invoice Number
		if($receiptData['invoice']){
			$checkInvoice = Submission::where('campaign_id',$id)
				->whereMetaValue([
					['status','REGEXP','^[1-9]$'],
					['ocr_invoice',$receiptData['invoice']],
				])->first();

			if($checkInvoice) {
				$data['flag_ocr_invoice'] = 'Invoice #'.$receiptData['invoice'].' used by another submission ('.$checkInvoice->meta('uuid').')';
			}
		}

		// Check against ocr data
		if($receiptData['ocr']) {
			$compareOCR = Submission::where('campaign_id',$id)
				->whereMetaValue([
					['status','REGEXP','^[1-9]$'],
					['ocr_read',$receiptData['ocr']],
				])->first();

			if($compareOCR) {
				$data['flag_ocr_read'] = 'OCR text looks the same as another submission ('.$compareOCR->meta('uuid').')';
			}
		}

		// New submission
		$submission = new Submission;

        $submission->campaign_id = $id;
        $submission->user_id = 0;
        $submission->ip_address = getRealIpAddr();

        // Save
		$submission->save();

		$data['uuid'] = submissionUUID($submission->id);

		$data['ocr_read'] = $receiptData['ocr'];
    	$data['ocr_date'] = $receiptData['date'];
		$data['ocr_invoice'] = $receiptData['invoice'];
		$data['ocr_total'] = $receiptData['total'];
		$data['ocr_payer'] = $receiptData['payer'];

		$data['ocr_fail'] = preg_match('/OCR Error:/', $data['ocr_read'])?1:0;

		$data['status'] = preg_grep('/^flag_/',array_keys($data))?1:2;

		foreach ($files as $field) {
			if(isset($field->file)) {
				$data[$field->name] = $field->file;
			}
		}

		$submission->updateMeta($data);

		// Email
		TriggerMail::send('submissionStatus',$submission);

		// If json returned - no alert is shown in scripts.js
		/*return Response::json([
			'success' => true,
			'data' => $data,
		], 200);*/

		// What was the return type here?

	}

	public function redeem(Request $request, $id)
	{
		try {
			if( !$submission = Submission::find(Crypt::decrypt($id)) ) {
				throw new Exception("Error Processing Request", 1);
			}

			if( isset($request->scratch) ) {

				if ( $submission->meta('status') !== '3' ) {
					throw new Exception("Error Processing Request", 1);
				}

				$submission->updateMeta([
					'status' => $submission->meta('is_win')?5:4
				]);

				// Email
				TriggerMail::send('submissionStatus',$submission);

				return Response::json([
					'win' => $submission->meta('is_win')?1:0
				], 200);

			} else {

				$data = $this->process_data( $request->all() );

				// Setup Validation Fields
				$validation = [
					'rules' => [],
					'messages' => [],
				];

				$validation['rules']['retailer'][] = 'required';
				$validation['messages']['retailer.required'] = 'This field is required';

				$validate_autocomplete_address = 'address';

				$validation['rules']['address_line_1'][] = 'required';
				$validation['messages']['address_line_1.required'] = 'This field is required';

				$validation['rules']['address_suburb'][] = 'required';
				$validation['messages']['address_suburb.required'] = 'This field is required';

				$validation['rules']['address_state'][] = 'required';
				$validation['messages']['address_state.required'] = 'This field is required';

				$validation['rules']['address_postcode'] = ['required','numeric','min:0','max:9999'];
				$validation['messages']['address_postcode.required'] = 'This field is required';
				$validation['messages']['address_postcode.numeric'] = 'Invalid postcode';

				// Honeypot
				$validation['rules']['_mn'] = 'honeypot';
				$validation['rules']['_mt'] = 'required|honeytime:5';

				// Setup Validator with set fields
				$validator = Validator::make($request->all(), $validation['rules'], $validation['messages']);

				// Additional Validations
				$validator->after(function ($validator) use ($validate_autocomplete_address) {

					if($validate_autocomplete_address) {
						foreach ($validator->errors()->toArray() as $key => $value) {
							if(substr( $key, 0, strlen($validate_autocomplete_address)+1 ) === $validate_autocomplete_address.'_') {
								$validator->errors()->add('_'.$validate_autocomplete_address,'This field is required');
								break;
							}
						}

					}

				});

				// If Validation fails
				if ($validator->fails()) {
					if($request->ajax()) {
						return Response::json([
							'success' => false,
							'errors' => $validator->getMessageBag()->toArray()
						], 400);
					} else {
						return redirect()->back()->withErrors($validator)->withInput();
					}
		        }

		        $data['prize_chosen'] = 1;

		        $submission->updateMeta($data);

				return Response::json([
					'success' => true,
				], 200);

			}
		} catch (Exception $e) {
			return Response::json([
				'error' => true,
				'message' => $e->getMessage(),
			], 500);
		}
	}

	private function exclusionList($payerNumber)
	{
		$exList = ['217328','308769','321253','316798','503742','317932','311576','407934','411578','415154','226437','407148','312723','226692','506184','228076','224733','225852','201282','407599','415655','211180','503685','418432','205365','418988','321814','319235','217885','323393','604833','228561','601363','227553','221656','221471','227041','318780','603391','312346','223548','401658','405000','310096','320699','316179','420209','404893','315524','217526','315539','502063','324042','309333','325081','308234','312268','326920','208162','216173','209513','606237','325668','221506','314827','414516','225932','506312','212949','416756','604001','324986','409031','325961','502834','500889','300261','504916','501406','202220','226796','222846','315607','407655','504826','323937','325450','607465','215530','607258','227174','407175','414751','309314','606198','606704','416172','219212','605716','307867','310719','220066','314402','323869','303693','326926','318279','505507','222042','225884','318081','210027','223044','415093','503802','208804','607017','212501','323595','221841','606242','504646','408920','420758','417843','228756','409409','305842','504161','316247','325274','222219','215003','229815','209404','305717','228418','325800','219669','200609','225092','325671','325920','320677','325449','419451','504597','321695','326507','217137','414697','326687','322097','217106','215925','416787','205859','309547','323683','310742','606459','225566','228090','607299','305152','208540','504541','420100','222501','214065','219911','323790','214511','517044','208169','220392','203684','225887','319782','208472','401991','317567','226916','320925','222184','213911','413009','319072','228550','501614','323955','205762','410395','504768','217044','223986','409308','320988','418228','321375','326218','412821','417665','305709','222581','323306','221979','504647','323644','218119','416611','313010','317193','326296','212646','220105','227538','327064','220836','419132','419592','317085','603952','327189','227502','601497','228532','309838','407500','320120','222094','218352','229051','227361','316738','606737','410283','206988','213868','227300','212217','420082','504675','503595','412791','418241','227722','419328','221616','323701','406324','206596','211565','204262','400303','323917','413921','324003','321467','221455','311282','417080','315878','205697','220573','321071','300689','502521','324764','414493','313311','324056','326512','326399','418500','308323','413273','327057','324190','600917','325727','317842','325522','222027','419385','212484','219041','502289','314017','327703','503006','605921','414139','210954','405159','607540','319362','227588','503851','212587','320224','409745','227069','410699','401612','415480','217168','228245','314091','408872','417506','326667','225947','202693','211633','319232','220622','326921','419167','411100','309217','223198','505315','327085','412396','606261','214403','417662','228454','325742','404695','229221','211134','325314','325886','229538','221886','209378','315597','321266','229495','226167','323786','315442','602256','206708','501483','505977','327642','229193','606222','226078','505804','218927','221236','320295','314705','503727','221158','228274','326256','226700','222070','326971','207530','310084','214127','229541','226846','500085','228698','224728','219861','219528','325913','417215','227153','224447','515592','323592','410235','503936','417325','418535','326145','325921','314708','416331','316392','228283','228451','317869','325782','225076','319587','313894','327432','504670','228014','510888','416401','320866','420805','228710','219310','315566','420370','416999','407793','607283','604421','413872','413696','325821','500483','204070','319013','327678','409715','323134','225970','405587','318866','227635','226178','204582','325187','409215','504735','208244','420146','406693','405537','225783','327135','221295','312933','403932','413848','318312','505125','308108','409109','326060','216628','403801','606531','205935','313294','315422','605798','204854','228744','316072','326474','215545','403434','229331','606502','316428','225823','222277','228493','419039','413702','226894','228017','302529','327405','210770','215856','300382','229608','324128','209594','204496','214037','415835','216174','505158','227859','220507','324771','209964','309794','324114','220055','225674','229325','222493','317219','316552','217474','606826','226659','217307','222550','324815','205359','224763','201469','418016','418878','607680','221728','420749','221927','503325','227937','603714','227650','413214','225659','221843','326558','405023','227794','408760','400584','224477','209307','208839','302041','323839','416194','324212','221880','315271','304694','502797','314529','229282','606041','602999','216621','228431','308631','408581','321677','311914','208862','210962','227049','600026','208204','503017','418078','315779','215298','220393','224678','607503','411036','325987','606467','309845','221539','602936','419748','410904','226545','326528','224578','201482','229762','409265','605994','301988','326634','307778','326032','416762','604203','505536','224757','325697','504568','327890','316633','407807','215951','257000','306464','418383','227848','407282','316729','326690','320945','216891','312753','318985','219145','620631','416834','503904','305639','227218','319933','326554','416210','227501','417537','604643','418174','314587','219792','204707','206498','223442','224515','316637','303498','208716','319085','228785','314114','218324','411860','325811','416741','603730','600850','216806','220861','212130','226584','224848','504695','606305','227594','317242','228231','221460','326422','217655','226849','413459','605577','202886','227713','222940','221097','312608','607285','326556','415732','218766','323971','305624','325573','305206','416491','325859','218449','209738','229688','202889','226136','607169','209640','220146','323185','216873','315665','301906','323944','305420','325085','418776','228053','505951','325333','203605','224703','308581','515135','315797','228354','300115','229273','202480','417988','419267','505659','224616','224777','207523','420806','417783','307022','324892','320274','419225','226881','319598','324916','220758','220719','211487','226880','603518','420013','226076','223255','206930','502848','311472','205357','224549','307416','228103','315454','404228','321402','506367','224531','501172','309982','222289','416812','409244','227385','227986','229432','317431','223006','226325','224843','300160','319606','212834','416000','220240','325662','321792','512745','617663','301936','324239','612220','218176','210386','211309','304704','324135','326173','418054','607190','405837','405619','325933','605885','419056','320177','408474','301505','417864','300097','200731','418007','208835','302264','416176','326334','220278','225631','529272','314874','226035','417622','324743','229740','506348','222472','606581','605892','218149','502318','416302','221638','422764','504989','220424','305461','309016','208333','326263','408926','222931','606065','316081','304426','216803','324840','308587','326242','325307','416125','325038','506320','228793','226011','229706','324508','305940','607507','209726','407540','501336','223023','325807','326683','417211','222481','417680','502193','417458','222284','217613','325645','316113','220372','322098','227991','203779','320601','310251','505780','501736','218968','325659','223139','228321','606412','413743','326684','606143','317579','319998','227514','327758','212859','203241','326317','229300','607275','611671','226007','412787','314764','322735','316555','227417','321190','312987','220117','606608','227138','224502','325685','212200','607143','504828','325099','404739','229474','317585','229721','418541','220220','216879','227094','228205','223385','229481','301195','228633','228514','504635','227304','226838','220526','228518','226812','220791','326461','415041','227878','227661','415688','506347','225184','619355','420552','224533','203709','505256','202120','210171','502890','226170','223408','422498','618122','220936','504466','226519','418165','211933','416419','418961','607383','222494','220434','309900','605887','413047','221836','607267','417894','219228','222033','607385','307905','222116','418041','220227','408455','314358','419522','215744','221473','310005','226026','226770','325518','325344','603780','322717','326105','303172','308327','207021','227724','222186','221969','219424','326063','327098','318998','226426','325540','419015','402285','416145','226457','227947','310099','309552','602170','326470','219030','417309','505816','601109','327236','207903','212178','203765','301425','222217','229382','602320','227178','416197','226966','227207','306576','326967','227295','503672','217858','500905','325927','229405','312437','211364','225665','219273','325189','327772','217386','320371','229186','218412','308865','225843','607463','506185','325563','227755','600599','325068','313681','327303','312053','218244','414450','226493','504284','327145','229472','324611','327648','315243','606833','315530','326059','222141','607735','601143','303536','219093','316737','603578','327857','226874','205377','317555','501611','324453','606640','323948','319359','327476','210880','325707','316478','217936','228736','605916','228250','229742','314382','317694','220488','414008','228678','417886','607411','325487','327238','417954','321069','325657','226249','413694','306176','417425','228322','601855','417641','325467','311599','315485','217367','226112','306188','414941','326131','326451','228242','213604','216316','405743','312507','220433','222834','229728','220643','228484','216623','225638','417678','412814','323608','228379','416680','406467','504896','316176','307408','228768','201008','326289','324897','607422','301351','505528','416946','304962','326280','606915','419096','325469','419203','227703','228214','415668','216092','407186','225494','222860','215615','319395','317535','317080','209514','327328','419242','204627','417318','418442','317073','228779','208753','601981','225078','228663','228117','219213','307103','223186','225830','503459','419190','407270','220536','229843','503553','606069','415593','227452','229164','605126','505711','415444','320462','227902','304599','327084','503074','504530','315293','313007','217058','327567','605095','225979','214307','418212','229617','202903','221924','505562','416191','325399','417947','324429','224783','229117','219991','323920','221964','301212','220185','325585','227078','420569','316469','327331','229426','606468','324924','223100','323566','403904','300042','214389','420488','225305','411904','316164','420546','419257','227877','505311','214754','327134','227159','605760','419002','310023','415850','327776','327878','327347','601486','229465','323998','308195','418221','201001','226418','325040','220705','226151','406308','228135','222752','228364','220545','309959','223536','416910','207616','309773','227758','220251','601169','219300','312416','313847','227766','217353','323884','599736','323709','419344','410915','309560','229772','327286','323885','606497','504533','306398','324240','411619','326362','227378','228824','606868','226174','317931','224924','201990','327450','323739','606428','226448','408412','325642','228829','327460','699736','416867','225914','226670','418474','603861','226714','226140','323332','229806','210533','229319','404236','229398','511112','229752','225888','409922','218599','325755','226788','327487','419731','417881','604775','418716','318237','222479','229268','419710','303060','327452','415493','323999','326708','326340','218710','407031','227543','228564','327220','326181','219211','200849','217954','419908','219507','207160','505056','229407','415818','224727','207156','415061','213719','229722','326494','420550','214742','210920','229457','207696','316452','217185','319255','606360','416006','417607','209505','225708','416996','229683','224458','325640','323780','606451','206385','315355','317314','407725','307742','417918','324911','419479','418093','504264','413852','327686','504536','606079','222486','505199','315177','223433','324352','212569','219859','325809','212527','229275','417633','415972','413713','309642','223101','412851','227882','502734','411940','207470','215988','417857','505522','229415','229511','228447','219923','416115','505543','203803','405247','229567','503051','210557','605365','421587','308461','605958','327342','604339','499780','407499','227895','315351','229795','211898','216408','317786','411710','207916','221833','205926','406020','415656','505381','451096','224278','327246','417847','200289','218874','308856','227849','207199','326677','229642','228119','414340','415204','312175','214465','604014','312216','326359','229266','327568','323831','208448','602891','408534','600344','607717','221514','409216','327158','212840','326109','325366','319837','212729','227151','505973','323606','404673','505965','327396','324968','419321','418011','505251','420064','226610','229726','420725','506322','209779','228667','420489','222101','326002','607322','602911','325990','406159','323923','229309','606823','412140','605940','225788','217839','419796','323143','205336','324857','221980','327253','228206','417070','412572','405751','306617','419007','315770','420134','410010','418807','327139','211807','228385','228267','229098','419486','416768','326550','227105','325146','320139','316308','219302','327091','417246','229574','402305','325992','307535','316267','605867','308993','607013','411661','606453','327393','315093','207037','219710','422023','327018','314104','322262','209682','227313','326434','319882','502597','321166','309969','320226','411245','220595','227925','228704','322021','301140','327666','326155','327704','514527','226105','321609','326103','213858','313978','416380','326530','317726','220900','224410','415492','327671','405232','326496','418265','417299','222450','505474','226590','216824','415788','607654','205596','323109','401327','411468','305604','501389','419386','226511','603603','604434','227141','228389','302780','502944','505361','226808','220604','402971','306387','216297','229594','606769','224692','220179','227359','600043','213716','222901','223432','225954','417498','411330','324295','415905','229585','226924','607505','228765','307355','224605','324016','311648','417225','219277','221643','404672','229674','418675','221273','404109','300189','207058','216699','204859','418069','327313','606063','607754','504146','307087','325423','207291','320470','326801','227708','506040','320458','606677','229646','327054','229459','222700','228400','327817','208249','317982','206391','316067','417324','229044','327067','326504','414835','227289','417917','227717','413698','419695','318363','229804','416457','325139','229404','215187','420555','409365','217597','229419','227176','321430','313022','227600','412410','415791','227160','320190','229601','222563','210878','606921','418060','416497','220327','318718','321778','216042','210628','229663','326432','325217','416117','224517','420387','226840','320390','306216','415643','603767','417688','419288','398769','208640','506074','226676','319971','225684','418382','208059','607066','324674','226648','227611','410830','317989','418123','407118','222867','404358','310453','226051','419278','327314','606713','420131','607444','220460','215970','310207','505227','222613','215790','410820','415402','502029','222168','325275','320244','408685','411576','417615','410731','227033','313240','417594','223012','229619','228272','416571','310293','326154','505040','220347','409122','416645','505055','326623','325379','222301','325436','228105','411561','418834','326202','420579','327207','217830','227454','226525','418032','411107','225639','419372','607425','222295','412580','416307','606562','417762','418828','323680','409290','200836','201906','201998','204039','204153','205033','205872','206020','206094','206322','206537','206923','207644','208031','209229','209600','210002','212767','213918','214054','214892','215182','215605','215994','216851','219327','219602','219927','220417','220736','220785','220809','220950','221373','221633','222736','223241','224390','224818','225615','225761','226028','226193','226223','226499','226557','226810','226833','227199','227563','228154','228424','228485','229320','229386','229999','239130','241966','244140','244325','247999','250665','250815','250895','253816','253817','254249','255429','256985','257723','259111','259204','259964','280231','281437','286091','286097','286105','286114','286131','287466','288008','288061','289320','289750','289965','291500','294944','297998','297999','298000','298163','299130','299500','302796','304086','305746','309164','309654','311227','311873','312877','313153','314059','315619','315747','316595','317012','317292','317342','317528','317669','317710','318291','318934','323003','323121','323541','324246','325166','325549','326122','326446','326959','327072','327495','335312','340026','341383','341564','341811','348578','352282','352283','353787','355366','359380','380620','382355','385814','389965','402263','405086','405366','406751','406899','407972','408391','408599','408770','408961','409912','409996','411075','411078','411326','411569','412805','413405','413430','413467','414189','414769','416903','416961','417024','417239','417666','417744','417873','418039','418238','418464','418892','419527','419722','419785','420259','421210','422595','429999','440520','442351','443209','444998','448065','449352','449398','449585','449753','450566','451480','451506','451834','452273','453821','454445','454802','454887','457111','457988','457997','458956','459216','481091','483014','483016','484244','484544','486009','486011','486030','486032','486797','487017','487074','488178','488905','489965','494751','495174','495874','495906','496873','498000','499099','500375','501578','501933','502826','503147','503677','503796','503947','504444','504705','505756','505942','506314','510327','517246','530000','542521','550000','552900','555792','557595','586066','588130','589414','596381','597166','599869','600142','601233','601410','601606','602458','602507','602600','602909','604100','605653','605713','605725','606225','606302','606590','606620','606761','606891','606906','607148','607249','607346','607542','617744','620175','630000','650953','651837','653524','654005','655268','655281','655528','658058','658617','659728','659747','659765','659927','686041','686046','686050','687252','689709','691368','694490','694491','695842','696970','697728','698139','698299','699594','364023','358308','312953','356942','312951','323417','323418','323419','323421','323422','396166','326891','324537','312950','357671','360591','324099','324165','360920','381301','382532','398646','315257','345678','353281','312954','312955','352215','365108','319118','319124','328002','304781','304782','319127','328003','319142','328004','322273','322275','328038','319195','328013','322143','322146','319214','328016','319220','328017','300411','300410','300401','328021','319101','328022','319131','328023','316283','316275','324668','324669','321014','328020','319171','328008','301816','328014','325760','319149','328005','364935','363101','363103','318715','398045','381597','321461','363985','363986','356941','359559','357108','318016','356100','319239','339424','354090','351715','351716','351717','351584','351585','351582','399852','320043','417036','393813','359249','363080','363081','312956','382055','390562','389785','359407','324525','324845','324832','316228','322404','322166','322164','322167','322147','322201','323416','322347','322402','322384','322401','322163','322162','322158','322237','322128','322171','322178','322183','322142','322144','322145','322539','322175','310077','350003','250005','350005','312015','364091','330000','334340','399222','323860','322220','324415','394293','397950','399816','388231','394122','386173','386167','386148','353873','386185','358057','358241','391848','386691','395951','386151','386150','394453','386177','353820','386149','339522','387653','394925','355827','386186','386184','586064','386166','304261','304262','304264','304266','304273','304274','304275','304276','304277','304278','304279','304280','304281','304282','306827','306828','306829','304263','304265','304267','304268','304269','392061','319842','319844','319846','312515','312517','312518','310252','322563','312139','312125','311580','310254','311587','312138','311586','312151','304270','304271','304272','312504','312514','323529','381040','353000','390580','353500','358897','314406','323706','335484','303551','381770','399699','326878','360919','384672','309228','314287','387880','382580','326078','318204','388491','399999','315017','397998','397999','329999','326604','321999','326232','380231','391326','311430','391327','351225','399500','315466','392099','310280','309267','350352','317964','309477','398000','399099','381603','315570','319117','314289','315872','309457','309417','331474','387390','326806','350611','460547','560117','260035','365183','366018','260084','365190','254728','365121','260078','260094','366019','360454','460530','560127','460290','462188','560135','460247','360484','260038','367069','365086','460525','260043','460505','360467','560124','361853','260050','260077','460279','361351','363829','562669','264636','673946','607085','500475','400132','600568','302179','201497','264485','264421','462861','265463','679960','603673','411977','414012','412519','675784','402087','501763','564336','272886','319889','302680','320735','611040','213066','321651','315823','311652','400150','316584','213070','276850','213418','208675','412521','319888','400151','320493','375935','225222','223624','213419','462826','463587','466398','463643','462881','414231','602969','504280','301581','222754','464730','416713','512599','416712','363957','365131','418303','209077','209161','216963','217005','203191','217633','212451','321237','200513','200799','208380','200985','214920','411662','414978','212541','310481','210416','310082','214436','217138','310379','216985','315380','411859','419103','311105','217346','414331','502192','213554','202677','318828','317029','216994','208818','311604','200275','314536','216996','306150','415067','218137','214447','219475','216556','322259','218223','215065','214577','218830','210497','201408','510636','214957','216973','216988','311732','310394','314727','218098','217014','290580','590580','690580','422783','601733','500239','205200','610228','206861','511257','212256','210868','402813','287880','487880','213940','603865','202211','216839','487556','280460','580460','680460','501993','504053','303163','497998','629999','223063','305557','322844','304728','289004','221303','317450','219361','202077','226570','222839','219209','218896','414346','404624','219624','489130','389130','289130','214632','214733','212830','225605','215609','220464','215622','213055','227775','511346','202807','219357','410749','316582','309583','313823','209869','213060','409302','213203','308183','221652','605888','413270','221977','511956','220150','400968','220826','505813','213428','411307','411310','210468','213214','204974','300278','209568','323140','217018','287390','213400','410779','515187','602221','214303','219112','321904','210321','211436','602289','312597','207984','313497','601876','226685','221372','213698','217301','201847','211915','602298','419671','323398','302846','400715','401994','421669','413865','303583','318822','200482','311617','604306','312393','310901','512741','600750','510331','312644','314277','610218','318203','310887','516584','600232','512496','515218','611497','216919','502606','604486','500368','306476','310488','321468','204010','206959','512320','510322','512500','600717','324312','321899','213649','215923','215154','602238','218148','602188','318775','529106','401868','321666','502647','318391','214637','310074','400191','602287','319198','219254','317745','600426','319257','200285','225426','311935','315917','300082','209179','313660','308814','400498','225175','221123','310059','310740','318835','416568','600596','201517','411304','316280','204734','310735','617582','323323','200966','322484','213337','213547','610177','300066','320539','200407','303709','410826','406349','318988','604170','511277','318687','219863','322523','220815','410795','602193','319278','601294','213744','512642','300068','324841','402630','319333','219690','227491','602269','504616','304288','202433','320604','204103','321930','300081','413330','215198','321705','414433','300090','505783','321255','412259','216905','214029','201624','203181','502961','202235','502229','319256','325463','222877','321101','422983','413417','617489','604119','303436','400774','215068','304556','611737','203640','321287','602241','218591','603814','319382','417742','221816','319787','412542','317283','420016','500127','227012','501377','603552','502233','511092','221071','311536','459404','459402','505720','415698','414743','603688','314075','226111','325866','320804','604585','602896','503990','418083','322939','316783','224374','418148','224152','227856','227026','226034','504200','409408','219047','312363','318465','500158','224409','605660','417747','319951','602914','604248','605538','415587','409359','606211','603898','416658','504721','416610','226308','216861','505690','604167','505064','600285','602869','505219','503585','413072','319376','605883','607074','260861','411226','260762','601047','319733','504852','603949','317186','407143','260704','409914','612136','604114','308343','605590','416595','418374','412692','405028','316521','306472','500330','400914','400279','300057'];

		return in_array($payerNumber, $exList);
	}

}
