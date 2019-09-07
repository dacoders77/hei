<?php

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Campaigns\CampaignController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Importers\ImporterController;
use App\Model\Importers\Importer;
use Illuminate\Support\Facades\Input;
use View;
use Campaign;
use Validator;
use Submission;
use Crypt;
use Exception;

class CampaignController_1 extends CampaignController
{
	static $importer;

	public function __construct()
    {
        self::$importer = new ImporterController;
    }

	private function isOpen()
	{
		$args = $this->getRouteArgs();

		$campaign = Campaign::find($args->campaign_id);
		$start_date = !config('app.debug')?$campaign->meta('settings_start_date'):null;
		$end_date = $campaign->meta('settings_end_date');

		if( $start_date && strtotime($start_date) > strtotime('today') || $end_date && strtotime($end_date)<strtotime('today') ){
			return false;
		}

		return true;
	}
	/**
	 *
	 * Show campaign in front end
	 *
	 */
	public function show()
	{
        //\App\Classes\LogToFile::add(__FILE__, 'controller_1');

		if( !$this->isOpen() ) {
			return redirect('closed');
		}

		return parent::show();
	}

	public function pages($slug)
	{

		if( $this->isOpen() && $slug == 'closed' ) {
			return redirect('/');
		}

		if( $slug == 'win' || $slug == 't' ) {
			return redirect('/');
		}

		if( $slug == 'privacy' ) {
			return redirect('https://www.dulux.com.au/footer/privacy-policy');
		}

		return parent::pages($slug);
	}

	/**
	 *
	 * TinyURL
	 *
	 */
	public function tinyurl($tinyurl)
	{
		if( $submission = Submission::whereMetaValue('tiny_url',$tinyurl)->first() ) {
			return redirect()->route('campaign_1.win',Crypt::encrypt($submission->id));
		}

		return abort(404);
	}

    /**
	 *
	 * Show extra pages
	 *
	 */
	public function win($id)
	{
		try {
			if( !$submission = Submission::find(Crypt::decrypt($id)) ) {
				throw new Exception("Error Processing Request", 1);
			}

			if( $submission->meta('status') < 3 ) {
				throw new Exception("Error Processing Request", 1);
			}

			return view("campaigns.pages.1.win",[
				'campaign' => Campaign::find(1),
				'id' => $id,
				'submission' => $submission,
			]);
		} catch (Exception $e) {
			return abort(404);
		}
	}


	public function update(Request $request, $id)
    {
        // Check Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required',
            '_vouchers_file' => 'file|mimes:csv,txt',
        ], [
            'title.required' => 'Campaign title is required.',
            'status.required' => 'Status is required.',
        ]);

        // Additional Validations
		$validator->after(function ($validator) use ($request) {
			if( Input::hasFile('_vouchers_file') && empty($request->{'_map_code'}) && empty($request->{'_map_url'}) ) {
				$validator->errors()->add('_vouchers_file','No mapping was selected!');
			}
		});

		// Return on fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get Campaign
        $post = Campaign::find($id);

        // Update Title, Status and Data
        $post->title = $request->title;
		$post->status = $request->status;

		// Save Campaign
		$post->save();

		// Save Campaign Meta
		$post->updateMeta( $this->process_data( $request->all() ) );

		if( Input::hasFile('_vouchers_file') ) {
			$file = Input::file('_vouchers_file');
			if( $file->isValid() ) {
				$mapping = [
					'_map_code' => $request->{'_map_code'},
					'_map_url' => $request->{'_map_url'}
				];

				try {
		            $class = 'App\Http\Controllers\Campaigns\VoucherController';
		            $data = [
		                'campaign_id' => $id,
		                'mapping' => $mapping
		            ];
		            $importer = new ImporterController;
		            if( !$importer->store(
		                "{$class}@process_vouchers",
		                "_vouchers_file",
		                json_encode( $data )
		            ) ){
		                throw new \Exception("Error uploading voucher file", 1);
		            }
		        } catch (\Exception $e) {
		            \Log::error('Error setting up importer: '.$e->getMessage());
		            return redirect()->route('campaigns.index')->with([
		            	'success'=>'Campaign successfully updated.',
		            	'error'=>print_r($e->getMessage(),1)
		            ]);
		        }
			} else {
				\Log::error('Error setting up importer: file not valid');
			}
		}

		// Return on success
    	return redirect()->route('campaigns.index')->with('success','Campaign successfully updated.');
    }

}