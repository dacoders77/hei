<?php

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Campaign;
use CampaignMeta;
use User;
use UserMeta;
use Validator;
use View;
use TriggerMail;
use Response;

class CampaignController extends Controller
{
	static $globals;
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

	protected static function getRouteArgs()
	{
		return (object) request()->route()->defaults;
	}


	/**
	 *
	 * Removes unwanted variables and returns JSON data
	 *
	 */
	protected function process_data($data)
	{
		// force removal keys
		$removeKeys = [];

		foreach ($data as $key => $value) {

			if(substr($key, 0, 1) === '_' || in_array($key, $removeKeys)) {

				unset($data[$key]);

			} elseif(!is_array($value)&&!is_object($value)) {

				$value = preg_replace('/\s+/',' ',trim($value));

				if(substr($key, 0, 8) === 'secure__') {
					$value = \Crypt::encrypt($value);
				}

				$data[$key] = $value;

			} elseif($key == 'form_content') {
            	$value = json_decode($value,true);
            	array_walk_recursive($value, function(&$v) { $v = strip_tags($v); });
            	$value = json_encode($value);

            	$data[$key] = $value;
            }

		}

		return $data;
	}

	/**
	 *
	 * List all campaigns in Admin
	 *
	 */
	protected function list()
	{
		$campaigns = Campaign::where('status','!=',2)->get();

		$data = [
            'title' => 'Campaigns',
            'subtitle' => 'All',
            'campaigns' => $campaigns
        ];

        return view('admin.campaigns.list',$data);
	}


	/**
	 *
	 * Edit campaign in Admin
	 *
	 */
	protected function edit($id)
	{
		if (\Auth::user()->role >= 3) {
			$data = [
	            'title' => 'Restricted',
	            'subtitle' => 'This area is off limits',
	        ];
			return view('admin.restricted',$data);
		}
		// check if campaign controller exists
		if( $controller = methodExists(__METHOD__, $id) )
		{
			return call_func($controller, $id);
		}
		// else
		$campaign = Campaign::find($id);

		$data = [
            'title' => $id . ': ' . $campaign->title,
            'subtitle' => 'Settings',
            'campaign' => $campaign,
        ];

        return view( View::exists("admin.campaigns.edit_{$campaign->id}") ? "admin.campaigns.edit_{$campaign->id}" : "admin.campaigns.edit" ,$data);
	}


	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function update(Request $request, $id)
    {
    	// check if campaign controller exists
		if( $controller = methodExists(__METHOD__, $id) )
		{
			return call_func($controller, [$request,$id]);
		}

        // Check Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required',
        ], [
            'title.required' => 'Campaign title is required.',
            'status.required' => 'Status is required.',
        ]);

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

		// Return on success
    	return redirect()->route('campaigns.index')->with('success','Campaign successfully updated.');
    }


	/**
	 *
	 * Show campaign in front end
	 *
	 */
	public function show()
	{
        //\App\Classes\LogToFile::add(__FILE__, 'controller');

		$args = $this->getRouteArgs();

		return view( View::exists("campaigns.index_{$args->campaign_id}") ? "campaigns.index_{$args->campaign_id}" : "campaigns.index" ,[
			'campaign' => Campaign::find($args->campaign_id),
		]);
	}


	/**
	 *
	 * Show extra pages
	 *
	 */
	public function pages($slug)
	{
		$args = $this->getRouteArgs();

		$bladeSlug = str_replace('/', '.', $slug);

		if( View::exists("campaigns.pages.{$args->campaign_id}.{$bladeSlug}") ) {
			return view("campaigns.pages.{$args->campaign_id}.{$bladeSlug}",[
				'campaign' => Campaign::find($args->campaign_id),
			]);
		} else {
			return abort(404);
		}
	}

	/**
     *
     * Contact Us Email
     *
     */
    public function contactus(Request $request)
    {
        // Check Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'comment' => 'required',
        ], [
            'first_name.required' => 'This field is required.',
            'last_name.required' => 'This field is required.',
            'phone.required' => 'This field is required.',
            'email.required' => 'This field is required.',
            'comment.required' => 'This field is required.',
        ]);

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

        $mail = TriggerMail::send('contact_us_1',[
            'request' => $request,
            'campaign_id'=> $request->{'_campaign'}
        ]);

        if(!$mail) return abort(500);

        TriggerMail::send('contact_us_2',[
            'to' => [
                'address' => $request->email,
                'name' => $request->first_name . ' ' . $request->last_name,
            ],
            'request' => $request,
            'campaign_id'=> $request->{'_campaign'}
        ]);

        if($request->ajax()) {
            return Response::json([
                'success' => true,
                'message' => 'Thanks, your enquiry has been received and we will respond within 2 business days.'
            ], 200);
        } else {
            return redirect()->back()->with('success','Thanks, your enquiry has been received and we will respond within 2 business days.');
        }
    }

}