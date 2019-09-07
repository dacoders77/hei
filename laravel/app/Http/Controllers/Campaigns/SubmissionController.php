<?php

namespace App\Http\Controllers\Campaigns;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Submission;
use SubmissionMeta;
use Validator;
use Campaign;
use Response;

class SubmissionController extends Controller
{
	static $uploads;

	/**
	 *
	 * Setup
	 *
	 */
	public function __construct()
	{
		self::$uploads = [
			'tmp' => storage_path('app/public').'/uploads/'.date('Y/m').'/',
			'public' => '/storage/uploads/'.date('Y/m').'/',
		];
	}

	/**
	 *
	 * Removes unwanted variables and returns JSON data
	 *
	 */
	protected function process_data($data)
	{
		// force removal keys
		$removeKeys = [
			'g-recaptcha-response'
		];

		foreach ($data as $key => $value) {

			if(substr($key, 0, 1) === '_' || in_array($key, $removeKeys)) {

				unset($data[$key]);

			} elseif(!is_array($value)&&!is_object($value)) {

				$value = preg_replace('/\s+/',' ',trim($value));

				if(substr($key, 0, 8) === 'secure__') {
					$value = \Crypt::encrypt($value);
				}

				$data[$key] = $value;

			}

		}

		return $data;
	}


    /**
	 *
	 * List all submission in Admin
	 *
	 */
	public function list($id)
	{
		// check if campaign controller exists
		if( $controller = methodExists(__METHOD__, $id) )
		{
			return call_func($controller, $id);
		}

		$campaign = Campaign::find($id);

		$data = [
		    'title' => 'Submissions',
		    'subtitle' => $id . ': ' . $campaign->title,
		    'campaign' => $campaign,
		    'submissions' => $campaign->submissions()->get()
		];

		return view( \View::exists("admin.campaigns.submissions.list_{$campaign->id}") ? "admin.campaigns.submissions.list_{$campaign->id}" : "admin.campaigns.submissions.list" ,$data);
	}

	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function update(Request $request, $id, $sub)
	{
		// check if campaign controller exists
		if( $controller = methodExists(__METHOD__, $id) )
		{
			return call_func($controller, [$request,$id,$sub]);
		}

		$subs = explode(',', $sub);

		foreach ($subs as $subID) {
			// Get Submission
			$submission = Submission::find($subID);

			// Update data array
			$data = $this->process_data(
				array_merge(
					(array) $submission->meta(),
					$request->all()
				)
			);

			// Save Meta
			foreach ($data as $key => $value) {
				$submissionMeta = SubmissionMeta::firstOrNew([
	                'submission_id' => $subID,
	                'meta_key' => $key,
	            ]);
	            $submissionMeta->meta_value = $value;
	            $submissionMeta->save();
			}

		}

		// Redirect
		return redirect()->route('campaigns.submissions.index',[$id,$request->{'_hash'}])->with('success','Successfully updated.');
	}


	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function store(Request $request, $id)
	{

		// check if campaign controller exists
		if( $controller = methodExists(__METHOD__, $id) )
		{
			return call_func($controller, [$request,$id]);
		}

		// Get Campaign Form Fields
		$campaign = Campaign::find($id);
		$campaign_form_fields = $campaign->meta('form_content');

		// Process Data
		$data = $this->process_data( $request->all() );

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
					'maxsize' => 4194304,
				];
			}

		}

		// Honeypot
		$validation['rules']['_mn'] = 'honeypot';
		$validation['rules']['_mt'] = 'required|honeytime:5';

		// Setup Validator with set fields
		$validator = Validator::make($request->all(), $validation['rules'], $validation['messages']);

		if( $files ) {
			$storage_path = storage_path('app/private').'/campaigns/'.str_pad($id, 2, 0, STR_PAD_LEFT).'/'.date('Y/m').'/';
	        $public_url = '/storage/private/campaigns/'.str_pad($id, 2, 0, STR_PAD_LEFT).'/'.date('Y/m').'/';

			foreach ($files as &$field) {

				if( Input::hasFile($field->name) ) {
					$file = Input::file($field->name);
					if( $file->isValid() && $file->getSize() > 4194304 ) {
						$field->error = 'File size must be less than 4MB.';
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
						$validator->errors()->add($field->name,$field->error);
					}
				}
			}

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

		// New submission
		$submission = new Submission;

		foreach ($files as $field) {
			if(isset($field->file)) {
				$data[$field->name] = $field->file;
			}
		}

        $submission->campaign_id = $id;
        $submission->user_id = 0;
        $submission->ip_address = getRealIpAddr();

        // Save
		$submission->save();

		$data['uuid'] = submissionUUID($submission->id);
		$data['status'] = 1;

		// Save Meta
        $submission->updateMeta($data);

        $message = 'Successfully submitted.';

		// Redirect
		if($request->ajax()) {
			return Response::json([
				'success' => true,
				'message' => $message,
				'data' => $request->all(),
			], 200);
		}

		return redirect()->back()->with('success',$message);

	}

}
