<?php

namespace App\Http\Controllers\Admin;

use App\Classes\LogToFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Timestamp;
use Voucher;
use Submission;
use Campaign;
use TriggerMail;
use Crypt;
use App\Http\Controllers\Importers\ImporterController;
use App\Model\Importers\Importer;
use Log;

class ApiController extends Controller
{
    static $importer;

    public function __construct()
    {
        ini_set('max_execution_time', '9999');
        ini_set('max_input_time', '9999');
        ini_set('max_input_vars', '99999');
        ini_set('memory_limit', '9999M');

        self::$importer = new ImporterController;
    }

    /**
     * Make sure method: campaigns_datatable_submissions
     * exists
     *
     * @param Request $request
     * @param $func
     */
	public function api(Request $request, $func)
	{
		if( method_exists( '\App\Http\Controllers\Admin\ApiController', $func ) )
		{
			$class = '\App\Http\Controllers\Admin\ApiController';
			$class = new $class();
			return $class->$func();
		}

		return abort(404);
	}

    public function submission_approve()
    {
        $submission = Submission::find(request()->id);
        if(!$submission) return redirect()->back()->with('error','Something went wrong!');

        $campaign_id = $submission->campaign_id;

        $data = [
            'status' => 3
        ];

        if( intval(preg_replace('/[^0-9\.]+/', '', $submission->meta('invoice_total'))) >= 500 ) {
            $data['kayo'] = 1;
        }

        $submission->updateMeta($data);

        // Email
        TriggerMail::send('submissionStatus',$submission);

        return redirect()->back()->with('success','Approval successfull: '.$submission->meta('uuid'));
    }

    public function submission_reject()
    {
        if(request()->ids == 'all') {
            $submissions = Submission::where('campaign_id',request()->campaign_id)->whereMetaValue('status',request()->status)->get();
        } else {
            $ids = explode(',',request()->ids);
            $submissions = Submission::where('campaign_id',request()->campaign_id)->whereIn('id',$ids)->get();
        }

        if(!$submissions->count()) return redirect()->back()->with('error','Something went wrong!');

        $uuids = [];

        foreach($submissions as $submission){
            $data = [
                'status' => 0,
                'status_comment' =>request()->comment
            ];

            $submission->updateMeta($data);

            // Email
            TriggerMail::send('submissionStatus',$submission);

            $uuids[] = $submission->meta('uuid');
        }

        return redirect()->back()->with('success','Rejection successfull: '.implode(', ',$uuids));
    }

    /**
     * Show filter table
     * Returns data table contents to admin panel.
     * Columns are set in list_1.blade.php
     * In order to add a column in admin panel - it should be added in list_1.blade.php
     * Then
     *
     * @return array
     */
    public function campaigns_datatable_submissions()
    {

        // Add these 3 values to the array and get the array sent in GET method
        $data = (object) array_merge([
            'draw' => 1,
            'start' => 1,
            'length' => 10,
        ],$_GET);

        $vouchers = Submission::where('id','!=',null);

        //LogToFile::add(__FILE__, json_encode($_GET, JSON_PRETTY_PRINT));
        //foreach ($vouchers as $v)
        //    LogToFile::add(__FILE__, json_encode($v));


        foreach ($data->columns as $column) {
            if(empty($column['search']['value'])) continue;

            switch ($column['name']) {
                case 'campaign_id':
                    if( substr( $column['search']['value'], 0, 2 ) === "!=" ) {
                        $d = '!=';
                        $v = substr( $column['search']['value'], 2 );
                    } else if( strpos($column['search']['value'],',') >= 0 ) {
                        $d = 'REGEXP';
                        $v = '^'.str_replace(',','$|^', $column['search']['value']).'$';
                    } else if( is_array($column['search']['value']) ) {
                        $d = 'REGEXP';
                        $v = '^'.implode('$|^', $column['search']['value']).'$';
                    } else {
                        $d = '=';
                        $v = $column['search']['value'];
                    }
                    $vouchers = $vouchers->where('campaign_id',$d,$v);
                    break;

                case 'uuid':
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key',$column['name']],
                      ['meta_value',$column['search']['value']],
                    ]);
                    break;

                case 'flagged':
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key','REGEXP','^flag_'],
                    ]);
                    break;

                case 'created_at':
                    switch ($column['search']['value']) {
                        case '-7days':
                        case '-30days':
                            $vouchers = $vouchers->where([
                                ['created_at','>=', date('Y-m-d 00:00:00', strtotime($column['search']['value']))]
                            ]);
                            break;
                        
                        default:
                            $vouchers = $vouchers->where([
                                ['created_at','>=', date('Y-m-d 00:00:00', strtotime($column['search']['value']))],
                                ['created_at','<', date('Y-m-d 00:00:00', strtotime('+1month', strtotime($column['search']['value'])))]
                            ]);
                            break;
                    }
                    break;

                case 'status':
                    if( substr( $column['search']['value'], 0, 2 ) === "!=" ) {
                        $d = '!=';
                        $v = substr( $column['search']['value'], 2 );
                    } else if( strpos($column['search']['value'],',') >= 0 ) {
                        $d = 'REGEXP';
                        $v = '^'.str_replace(',','$|^', $column['search']['value']).'$';
                    } else if( is_array($column['search']['value']) ) {
                        $d = 'REGEXP';
                        $v = '^'.implode('$|^', $column['search']['value']).'$';
                    } else {
                        $d = 'LIKE';
                        $v = '%'.$column['search']['value'].'%';
                    }
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key',$column['name']],
                      ['meta_value',$d,$v],
                    ]);
                    break;

                default:
                    $d = 'LIKE';
                    $v = '%'.$column['search']['value'].'%';
                    if( substr( $column['search']['value'], 0, 2 ) === "!=" ) {
                        $d = '!=';
                        $v = substr( $column['search']['value'], 2 );
                    }
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key',$column['name']],
                      ['meta_value',$d,$v],
                    ]);
                    break;
            }
        }

        $vouchers_total = $vouchers->count();

        $json = [
            'draw' => $data->draw,
            'recordsTotal' => $vouchers_total,
            'recordsFiltered' => $vouchers_total,
            'data' => [],
        ];

        if($data->length) $vouchers = $vouchers->limit( $data->length );
        if($data->start) $vouchers = $vouchers->offset( $data->start );

        $rows = $vouchers->get();

        foreach ($rows as $voucher) {

            // Submissions reords!!! Works good
            // $meta = (object) $voucher->meta();
            // LogToFile::add(__FILE__, json_encode($meta, JSON_PRETTY_PRINT));

            $keys = [];

            // Run trough all records in model and assign values
            foreach ($data->columns as $column) {

                switch ($column['name']) {
                    case 'ip_address':
                        $keys[] = $voucher->ip_address;
                        break;

                    case 'created_at':
                        $keys[] = $voucher->created_at->format('d/m/Y h:ia') ;
                        break;

                    case 'purchase_date':
                    case 'ocr_date':
                        $keys[] = $voucher->meta($column['name'])?created_at($voucher->meta($column['name']),'d/m/Y'):null;
                        break;

                    case 'receipt':
                        $keys[] = '<a href="'.$voucher->meta('receipt').'" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-image"></i> View</a>';
                        break;

                    // Boris
                    // Only address full is returned.
                    // No postal_address_line_1 and other fields is returned
                    case 'address':
                        $keys[] = $voucher->meta('address_full');
                        break;

                    // Boris
                    // Add new fields
                    //    address_line_1
                    case 'address_line_1':
                        $keys[] = $voucher->meta('postal_address_line_1');
                        break;

                    case 'address_line_2':
                        $keys[] = $voucher->meta('postal_address_line_2');
                        break;

                    case 'address_suburb':
                        $keys[] = $voucher->meta('postal_address_suburb');
                        break;

                    case 'address_state':
                        $keys[] = $voucher->meta('postal_address_state');
                        break;

                    case 'address_postcode':
                        $keys[] = $voucher->meta('postal_address_postcode');
                        break;



                    case 'flagged':
                        $flags = $voucher->meta('^flag_([^c]+[^o]+[^l]+[^o]+[^r]+|.{1,4}|.{6,})?$',true);
                        $keys[] = $flags ? '<span class="label bg-'.($voucher->meta('flag_color')?:'orange').'" data-toggle="tooltip" data-placement="top" data-html="true" title="<p>• '.implode("</p>• ",$flags).'</p>"><i class="fa fa-flag"></i>&nbsp;&nbsp;Flagged</span>' : null;
                        break;

                    case 'bulk_action':
                        if($voucher->meta('status') > 1) {
                            $keys[] = null;
                        } else {
                            $keys[] = '<input type="checkbox" name="bulkcheck" value="'.$voucher->id.'">';
                        }
                        break;

                    case 'approve_reject':
                        // if($voucher->meta('status')==1){
                            $keys[] = '<div class="btn-group"><button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">&nbsp;Update Status&nbsp;&nbsp;<span class="fa fa-caret-down"></span>&nbsp;</button><ul class="dropdown-menu"><li><a href="javascript:void(0);" onclick="SubmissionApprove(this);" data-id="'.$voucher->id.'" data-href="'.route('ajax.api','campaign_1_bulk_approve').'" class="update-approve"><small class="text-green">Approve</small></a></li><li><a href="javascript:void(0);" onclick="SubmissionReject(this);" data-id="'.$voucher->id.'" data-href="'.route('ajax.api','submission_reject').'" class="update-reject"><small class="text-danger">Reject</small></a></li></ul></div>';
                        break;

                    case 'edit':
                        $editItems = '';
                        // $editItems = '<li><a href="javascript:void(0);" onclick="SubmissionEdit(this);" data-id="'.$voucher->id.'" data-href="'.route('ajax.api','submission_update').'">Edit Submission</a></li>';
                        $status = getStatus($voucher->meta('status'));
                        if($status&&preg_match('/^Pending/',$status['label'])) {
                            $editItems .= '<li><hr class="margin"></li>';
                            $editItems .= '<li><a href="javascript:void(0);" onclick="SubmissionApprove(this);" data-id="'.$voucher->id.'" data-href="'.route('ajax.api','campaign_1_bulk_approve').'" class="update-approve text-green">Approve</a></li>';
                            $editItems .= '<li><a href="javascript:void(0);" onclick="SubmissionReject(this);" data-id="'.$voucher->id.'" data-href="'.route('ajax.api','submission_reject').'" class="update-reject text-red">Reject</a></li>';
                        }
                        if($status&&preg_match('/^Pending/',$status['label'])) {
                            $keys[] = '<div class="btn-group btn-block"><button type="button" class="btn btn-primary btn-xs btn-block dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button><ul class="dropdown-menu pull-right">'.$editItems.'</ul></div>';
                        } else {
                            $keys[] = null;
                        }
                        break;

                    case 'ocr_fail':
                    $keys[] = $voucher->meta('ocr_fail')=='1'?'<a class="label label-default" data-ocr="'.(htmlspecialchars($voucher->meta('ocr_read'))).'" onclick="openOCRModal(this);"><i class="fa fa-eye"></i></a> <span class="label bg-red"><i class="fa fa-close"></i> No</span>':($voucher->meta('ocr_fail')=='0'?'<a class="label label-default" data-ocr="'.(htmlspecialchars($voucher->meta('ocr_read'))).'" onclick="openOCRModal(this);"><i class="fa fa-eye"></i></a> <span class="label bg-green"><i class="fa fa-check"></i> Yes</span>':null);
                        break;

                    case 'status':
                        $status = getStatus($voucher->meta('status'));
                        if($status) {
                            if($status['label']=='Rejected') {
                                $keys[] = '<span class="label bg-'.$status['color'].'" data-toggle="tooltip" data-placement="top" data-html="true" title="<p>'.$voucher->meta('status_comment').'</p>">'.$status['label'].'</span>';
                            } else {
                                $keys[] = '<span class="label bg-'.$status['color'].'">'.$status['label'].'</span>';
                            }
                        } else {
                            $keys[] = null;
                        }
                        break;

                    case 'kayo':
                        if($voucher->meta('kayo')) {
                            $keys[] = '<span class="label bg-fuchsia-active">Kayo Winner</span>';
                        } else {
                            $keys[] = null;
                        }
                        break;

                    // case 'prize_chosen':
                    //     if($voucher->meta('prize_chosen')) {
                    //         $keys[] = $voucher->meta('retailer');
                    //     } else {
                    //         $keys[] = null;
                    //     }
                    //     break;

                    case 'claim_url':
                        if(intval($voucher->meta('status')) >= 3 ) {
                            $keys[] = '<a href="'.route('campaign_1.win',Crypt::encrypt($voucher->id)).'" class="btn btn-default btn-xs" target="_blank"><i class="fa fa-globe"></i> Open</a>';
                        } else {
                            $keys[] = null;
                        }
                        break;

                    default:
                        $keys[] = $voucher->meta($column['name']);
                        break;
                }
            }

            $json['data'][] = $keys;
        }

        // Sort
        array_multisort(array_column($json['data'], $data->order[0]['column']),  $data->order[0]['dir'] == 'desc' ? SORT_DESC : SORT_ASC,
                array_column($json['data'], $data->order[0]['column']), $data->order[0]['dir'] == 'desc' ? SORT_DESC : SORT_ASC,
                $json['data']);


        //LogToFile::add(__FILE__, json_encode($keys, JSON_PRETTY_PRINT));
        return $json;
    }

    public function campaign_download_submissions($get=[],$download=true)
    {
        $data = !empty($_GET) ? $_GET : $get;

        $headers = [
            'uuid',
            'first_name',
            'last_name',
            'email',
            'phone',
            'address_line_1',
            'address_line_2',
            'address_suburb',
            'address_state',
            'address_postcode',
            'purchase_date',
            'invoice_total',
            'payer_number',
            'status',
            'kayo_winner',
            'kayo_voucher',
            'prize_type',
            'prize_chosen',
            'tracking_code',
            'created_at',
        ];

        $rows = [];

        $vouchers = Submission::where('id','!=',null);

        foreach ($data as $key => $value) {
            if(empty($value)) continue;

            $column = [
                'name' => $key,
                'search' => [
                    'value' => $value
                ]
            ];

            switch ($column['name']) {
                case 'campaign_id':
                    $vouchers = $vouchers->where('campaign_id',$column['search']['value']);
                    break;

                case 'uuid':
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key',$column['name']],
                      ['meta_value',$column['search']['value']],
                    ]);
                    break;

                case 'flagged':
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key','REGEXP','^flag_'],
                    ]);
                    break;

                case 'created_at':
                    switch ($column['search']['value']) {
                        case '-7days':
                        case '-30days':
                            $vouchers = $vouchers->where([
                                ['created_at','>=', date('Y-m-d 00:00:00', strtotime($column['search']['value']))]
                            ]);
                            break;
                        
                        default:
                            $vouchers = $vouchers->where([
                                ['created_at','>=', date('Y-m-d 00:00:00', strtotime($column['search']['value']))],
                                ['created_at','<', date('Y-m-d 00:00:00', strtotime('+1month', strtotime($column['search']['value'])))]
                            ]);
                            break;
                    }
                    break;

                case 'status':
                    if( substr( $column['search']['value'], 0, 2 ) === "!=" ) {
                        $d = '!=';
                        $v = substr( $column['search']['value'], 2 );
                    } else if( strpos($column['search']['value'],',') >= 0 ) {
                        $d = 'REGEXP';
                        $v = '^'.str_replace(',','$|^', $column['search']['value']).'$';
                    } else if( is_array($column['search']['value']) ) {
                        $d = 'REGEXP';
                        $v = '^'.implode('$|^', $column['search']['value']).'$';
                    } else {
                        $d = 'LIKE';
                        $v = '%'.$column['search']['value'].'%';
                    }
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key',$column['name']],
                      ['meta_value',$d,$v],
                    ]);
                    break;

                default:
                    $d = 'LIKE';
                    $v = '%'.$column['search']['value'].'%';
                    if( substr( $column['search']['value'], 0, 2 ) === "!=" ) {
                        $d = '!=';
                        $v = substr( $column['search']['value'], 2 );
                    }
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key',$column['name']],
                      ['meta_value',$d,$v],
                    ]);
                    break;
            }
        }

        foreach ($vouchers->get() as $voucher) {
            $row = [];
            foreach($headers as $header) {
                switch ($header) {
                    case 'address':
                        $row[] = $voucher->meta('address_full');
                        break;

                    case 'status':
                        $status = getStatus($voucher->meta('status'));
                        $row[] = $status?$status['label']:null;
                        break;

                    case 'created_at':
                        $row[] = $voucher->created_at->format('d-m-Y');
                        break;

                    case 'kayo_winner':
                        $row[] = $voucher->meta('kayo')?'yes':null;
                        break;

                    case 'prize_type':
                        $row[] = $voucher->meta('retailer')?$voucher->meta('prize'):null;
                        break;

                    case 'prize_chosen':
                        $row[] = $voucher->meta('retailer');
                        break;

                    default:
                        $row[] = $voucher->meta($header);
                        break;
                }
            }
            $rows[] = $row;
        }

        array_unshift($rows, $headers);

        $filename = str_slug(\Campaign::find(1)->title,'-').'-submissions_'.uniqid().'.csv';

        if($download) {
            return response()->streamDownload( function() use ($headers,$rows){
                echo arrayToCSV($rows);
            }, $filename);
        } else {
            return arrayToCSV($rows);
        }
    }

    /**
     * Approve submissions in admin panel.
     * Called from list_1.blade.php
     * All paraveters are sent in an associative array.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function campaign_1_bulk_approve()
    {
        $ids = explode(',',request()->ids);
        $uuids = [];

        if (request()->ids == 'all') {
            $ids = Submission::whereMetaValue('status','REGEXP','^[1-2]$')->pluck('id');
        }

        foreach ($ids as $id) {
            $submission = Submission::find($id);

            if(!$submission) continue;

            $uuids[] = $submission->meta('uuid');

            $data = [];

            //if( floatval(preg_replace('/[^0-9\.]+/', '', $submission->meta('invoice_total'))) >= 500 ) {

                /*if( !Submission::whereMetaValue([
                    ['payer_number',$submission->meta('payer_number')],
                    ['kayo',1],
                ])->count() ) {*/
                    if( $voucher = Voucher::whereMeta([
                        ['meta_key','campaign_id'],
                        ['meta_value',1]
                    ])->whereMeta([
                        ['meta_key','status'],
                        ['meta_value','0']
                    ])->first() ) {

                        $data['kayo'] = 1;
                        $data['kayo_voucher'] = $voucher->code;
                        $data['kayo_link'] = $voucher->meta('prize');

                        $voucher->updateMeta(['status'=>1]);

                    } else {
                        $data['flag_kayo'] = 'No more vouchers available';
                    }
                //}
            //}

            $n = Submission::whereMetaValue('status','REGEXP','^[3-9]$')->count();

            if( $n % 3 == 0 ) {
                $data['is_win'] = 1;

                $campaign = Campaign::find(1);

                $prizeCount = [
                    'gift_cards_20' => intval($campaign->meta('gift_cards_20')?:0),
                    'gift_cards_50' => intval($campaign->meta('gift_cards_50')?:0),
                    'gift_footballs' => intval($campaign->meta('gift_footballs')?:0),
                ];

                $rand = rand(1,array_sum($prizeCount));

                if($rand <= $prizeCount['gift_cards_20']) {
                    $data['prize'] = '$20 Gift Card';
                    $prizeCount['gift_cards_20']--;
                } else if($rand <= $prizeCount['gift_cards_20']+$prizeCount['gift_cards_50']) {
                    $data['prize'] = '$50 Gift Card';
                    $prizeCount['gift_cards_50']--;
                } else {
                    $data['prize'] = 'Football';
                    $prizeCount['gift_footballs']--;
                }

                $campaign->updateMeta($prizeCount);
            }

            $data['status'] = 3;
            $data['tiny_url'] = shortUrl($submission->id,1);

            $submission->updateMeta($data);

            // Email
            TriggerMail::send('submissionStatus',$submission);
        }

        if (request()->ids == 'all') {
            if ($uuids) {
                return redirect()->back()->with('success','Bulk approval successfull for all "Pending" submissions');
            } else {
                return redirect()->back()->with('error','No "Pending" submissions');
            }
        } else if($uuids) {
            return redirect()->back()->with('success','Bulk approval successfull: '.implode(', ',$uuids));
        } else {
            return redirect()->back()->with('error','Something went wrong!');
        }
    }

    public function generateTimestamps()
    {
        $request = request();

        if( Timestamp::all()->count() ) {
            return Response::json([
                'error' => true,
                'message' => 'Sorry, timestamps already generated.'
            ], 400);
        }

        if( !empty($request) && (empty($request->start) || empty($request->end) || empty($request->num)) ) {
            return Response::json([
                'error' => true,
                'message' => 'Missing required fields.'
            ], 400);
        }

        // Generate Array of timestamps
        $dates = $this->f_rr_resultset_num($request->num, strtotime($request->start.' 00:00:00'), strtotime($request->end.' 23:59:59'),false);

        // Sort by date
        sort($dates);

        // Create array of [timestamp, date, time]
        foreach ($dates as &$date) {
            $timestamp = Timestamp::create([
                'campaign_id' => $request->campaign_id,
                'timestamp' => $date,
            ]);
            $date = array(
                'timestamp' => $date,
                'date' => date('d/m/Y',$date),
                'time' => date('H:i:s',$date),
                'status' => 'Pending',
            );
        }

        // Return array
        return $dates;
    }

    private function f_rr_resultset_num($nbr, $min, $max, $distinct=true) {

        $result = array();

        // Unique
        if ($distinct) {
            $pick = array();
            for ($i=$min;$i<=$max;$i++) {
                $pick[$i] = $i;
            }
        }

        // Loop
        for ($i=1;$i<=$nbr;$i++) {
            if ($max<$min) break; // break if $distinct=true
            // Pick a number in a range.
            $r = mt_rand($min, $max);
            // Unique
            if ($distinct) {
                $z = $r;
                $r = $pick[$z];
                for ($j=$z;$j<$max;$j++) {
                    $pick[$j] = $pick[$j+1];
                }
                unset($pick[$max]);
                $max--;
            }
            $result[] = $r;
        }

        // Return Array
        return $result;

    }

    public function campaign_datatable_vouchers()
    {
        $data = (object) array_merge([
            'draw' => 1,
            'start' => 1,
            'length' => 10,
        ],$_GET);

        $vouchers = \Voucher::where('code','!=',null);

        foreach ($data->columns as $column) {
            if($column['search']['value']=='' || !isset($column['search']['value'])) continue;

            switch ($column['name']) {

                case 'code':
                    $d = 'LIKE';
                    $v = '%'.$column['search']['value'].'%';
                    if( substr( $column['search']['value'], 0, 2 ) === "!=" ) {
                        $d = '!=';
                        $v = substr( $column['search']['value'], 2 );
                    }
                    $vouchers = $vouchers->where($column['name'],$d,$v);
                    break;

                case 'uuid':
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key',$column['name']],
                      ['meta_value',$column['search']['value']],
                    ]);
                    break;

                case 'flagged':
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key','REGEXP','^flag_'],
                    ]);
                    break;

                case 'created_at':
                    switch ($column['search']['value']) {
                        case '-7days':
                        case '-30days':
                            $vouchers = $vouchers->where([
                                ['created_at','>=', date('Y-m-d 00:00:00', strtotime($column['search']['value']))]
                            ]);
                            break;
                        
                        default:
                            $vouchers = $vouchers->where([
                                ['created_at','>=', date('Y-m-d 00:00:00', strtotime($column['search']['value']))],
                                ['created_at','<', date('Y-m-d 00:00:00', strtotime('+1month', strtotime($column['search']['value'])))]
                            ]);
                            break;
                    }
                    break;

                case 'status':
                    if( substr( $column['search']['value'], 0, 2 ) === "!=" ) {
                        $d = '!=';
                        $v = substr( $column['search']['value'], 2 );
                    } else if( strpos($column['search']['value'],',') >= 0 ) {
                        $d = 'REGEXP';
                        $v = '^'.str_replace(',','$|^', $column['search']['value']).'$';
                    } else if( is_array($column['search']['value']) ) {
                        $d = 'REGEXP';
                        $v = '^'.implode('$|^', $column['search']['value']).'$';
                    } else {
                        $d = 'LIKE';
                        $v = '%'.$column['search']['value'].'%';
                    }
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key',$column['name']],
                      ['meta_value',$d,$v],
                    ]);
                    break;

                default:
                    $d = 'LIKE';
                    $v = '%'.$column['search']['value'].'%';
                    if( substr( $column['search']['value'], 0, 2 ) === "!=" ) {
                        $d = '!=';
                        $v = substr( $column['search']['value'], 2 );
                    }
                    $vouchers = $vouchers->whereMeta([
                      ['meta_key',$column['name']],
                      ['meta_value',$d,$v],
                    ]);
                    break;
            }
        }

        $vouchers_total = $vouchers->count();

        $json = [
            'draw' => $data->draw,
            'recordsTotal' => $vouchers_total,
            'recordsFiltered' => $vouchers_total,
            'data' => [],
        ];

        if($data->length) $vouchers = $vouchers->limit( $data->length );
        if($data->start) $vouchers = $vouchers->offset( $data->start );

        $rows = $vouchers->get();

        foreach ($rows as $voucher) {

            // $meta = (object) $voucher->meta();

            $keys = [];
            foreach ($data->columns as $column) {

                switch ($column['name']) {
                    case 'code':
                        $keys[] = $voucher->code;
                        break;

                    case 'status':
                        switch ($voucher->meta($column['name'])) {
                            case '1':
                                $keys[] = 'Redeemed';
                                break;

                            default:
                                $keys[] = 'Open';
                                break;
                        }
                        break;

                    default:
                        $keys[] = $voucher->meta($column['name']);
                        break;
                }
            }

            $json['data'][] = $keys;
        }

        // Sort
        array_multisort(array_column($json['data'], $data->order[0]['column']),  $data->order[0]['dir'] == 'desc' ? SORT_DESC : SORT_ASC,
                array_column($json['data'], $data->order[0]['column']), $data->order[0]['dir'] == 'desc' ? SORT_DESC : SORT_ASC,
                $json['data']);

        return $json;
    }

    public function campaign_datatable_timestamps()
    {
        $data = (object) array_merge([
            'draw' => 1,
            'start' => 1,
            'length' => 10,
        ],$_GET);

        $vouchers = \Timestamp::where('timestamp','!=',null);

        foreach ($data->columns as $column) {
            if($column['search']['value']=='' || !isset($column['search']['value'])) continue;

            switch ($column['name']) {

                case 'created_at':
                    switch ($column['search']['value']) {
                        case '-7days':
                        case '-30days':
                            $vouchers = $vouchers->where([
                                ['created_at','>=', date('Y-m-d 00:00:00', strtotime($column['search']['value']))]
                            ]);
                            break;
                        
                        default:
                            $vouchers = $vouchers->where([
                                ['created_at','>=', date('Y-m-d 00:00:00', strtotime($column['search']['value']))],
                                ['created_at','<', date('Y-m-d 00:00:00', strtotime('+1month', strtotime($column['search']['value'])))]
                            ]);
                            break;
                    }
                    break;

                default:
                    $vouchers = $vouchers->where($column['name'],$column['search']['value']);
                    break;
            }
        }

        $vouchers_total = $vouchers->count();

        $json = [
            'draw' => $data->draw,
            'recordsTotal' => $vouchers_total,
            'recordsFiltered' => $vouchers_total,
            'data' => [],
        ];

        if($data->length) $vouchers = $vouchers->limit( $data->length );
        if($data->start) $vouchers = $vouchers->offset( $data->start );

        $rows = $vouchers->get();

        foreach ($rows as $voucher) {

            $keys = [];
            foreach ($data->columns as $column) {

                switch ($column['name']) {
                    case 'timestamp_date':
                        $keys[] = date('d-m-Y',$voucher->timestamp);
                        break;
                    case 'timestamp_time':
                        $keys[] = date('h:i:s a',$voucher->timestamp);
                        break;
                    case 'status':
                        switch ($voucher->{$column['name']}) {
                            case '1':
                                $keys[] = 'Active';
                                break;

                            case '2':
                                $keys[] = 'Won';
                                break;
                            
                            default:
                                $keys[] = 'Open';
                                break;
                        }
                        break;

                    default:
                        $keys[] = $voucher->{$column['name']};
                        break;
                }
            }

            $json['data'][] = $keys;
        }

        // Sort
        array_multisort(array_column($json['data'], $data->order[0]['column']),  $data->order[0]['dir'] == 'desc' ? SORT_DESC : SORT_ASC,
                array_column($json['data'], $data->order[0]['column']), $data->order[0]['dir'] == 'desc' ? SORT_DESC : SORT_ASC,
                $json['data']);

        return $json;
    }


    public function tracking_bulk()
    {
        $validator = \Validator::make(request()->all(), [
            'campaign_id' => 'required|numeric|exists:campaigns,id',
            '_map_csv' => 'required|file|mimes:csv,txt',
            '_map_uuid' => 'required',
            '_map_tracking_code' => 'required',
        ]);

        // Basic form validation - thanks Laravel
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors());
        }

        $mapping = [];

        foreach (request()->all() as $key => $value) {
            if(!preg_match('/^_map_/i', $key)||$key=='_map_csv') continue;
            $mapping[$key] = $value;
        }

        try {
            $class = get_class($this);
            $data = [
                'campaign_id' => request()->campaign_id,
                'mapping' => $mapping
            ];
            if( !self::$importer->store(
                "{$class}@process_tracking_bulk",
                "_map_csv",
                json_encode( $data )
            ) ){
                throw new \Exception("Error uploading file", 1);
            }
        } catch (\Exception $e) {
            Log::error('Error setting up importer: '.$e->getMessage());
            return redirect()->back()->with('error',print_r($e->getMessage(),1));
        }

        return redirect()->back()->with('success', 'Records imported and updated successfully');
    }

    public function process_tracking_bulk(Importer $importer)
    {
        $request = json_decode($importer->request);

        // Convert the CSV to a usable array
        $csv = csvToArray( storage_path("imports/tmp/{$importer->original_filename}") );

        for ($i=$importer->row_offset; $i < $importer->row_count; $i++) {

            if(!isset($csv[$i])) {
                $importer->row_offset++;
                $importer->save();
                continue;
            }

            $row = $csv[$i];

            if( !$submission = Submission::whereMetaValue([
                ['uuid',$row[$request->mapping->{'_map_uuid'}]],
                ['status',5]
            ])->first() ) {
                $importer->row_offset++;
                $importer->save();
                continue;
            }

            $submission->updateMeta([
                'status' => 6,
                'tracking_code' => $row[$request->mapping->{'_map_tracking_code'}]
            ]);

            TriggerMail::send('submissionStatus',$submission);

            $importer->row_offset++;
            $importer->save();

        }
    }

}