<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use PDF;
use Imagick;
use ImagickPixel;
use SVGGraph;
use Submission;
use SubmissionMeta;
use Campaign;

class PDFReportsController extends Controller
{
	public function __construct()
    {
    	ini_set('max_execution_time', '9999');
	    ini_set('max_input_time', '9999');
	    ini_set('max_input_vars', '99999');
	    ini_set('memory_limit', '9999M');
	}

	public function base64Image($filename=string,$filetype=string)
	{
	    if ($filename) {
	        $imgbinary = fread(fopen($filename, "r"), filesize($filename));
	        return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
	    }
	}

	public function index()
	{
		$data = [
            'title' => 'Weekly Reports',
            'subtitle' => 'AU and NZ',
        ];

        $startDate = strtotime('04-08-2019');
        $endDate = strtotime('22-09-2019');

        $days = ($endDate - $startDate)/60/60/24;

        $data['weeks'] = $days/7;
        $data['startDate'] = $startDate;

        return view('admin.pdf.index',$data);
	}

	public function generate($filename)
	{
		if(!preg_match('/^WeeklyReport_[0-9]{2}-[0-9]{2}-[0-9]{4}$/', $filename)) {
			return abort(404);
		}

		$endDate = strtotime(preg_replace('/^WeeklyReport_([0-9]{2}-[0-9]{2}-[0-9]{4})$/', '$1', $filename));
		$startDate = strtotime('-6days',$endDate);

		$pilgrimLogo = $this->base64Image(public_path('assets/images/PilgrimCommunicationsLogo-Black.png'),'png');
		$swisseLogo = $this->base64Image(public_path('assets/images/swisse-logo.png'),'png');

        $data = [
            'pilgrimLogo' => $pilgrimLogo,
            'swisseLogo' => $swisseLogo,
            'stylesheet' => file_get_contents(public_path('assets/css/pdf.css')),
            'endDate' => date('d/m/Y',$endDate),
            'startDate' => date('d/m/Y',$startDate),
            'campaigns' => [],
        ];

        for ($campaign_id=1; $campaign_id <= 2; $campaign_id++) {

    		$graph_FREQUENCY_OF_ENTRIES_settings = (object) [
                'values' => $this->getEntriesFreq($campaign_id,$startDate),
    	        // 'values_totals' => 3996,
                'colors' => [
                    '#0073b7',
                    '#00a65a',
                    '#f39c12',
                    '#dd4b39',
                    '#A5007A',
                ]
            ];

            $graph_FREQUENCY_OF_ENTRIES = $this->pngGraph( $graph_FREQUENCY_OF_ENTRIES_settings->values, [
                'type' => 'PieGraph',
                'back_colour' => '#fff',
                'stroke_colour' => '#fff',
                'pad_right' => 2,
                'pad_left' => 2,
                'show_label_key' => false,
                'show_label_amount' => false,
                'show_label_percent' => true,
                'label_colour' => '#fff',
                'label_font' => 'sans-serif',
                'label_font_size' => (12*3.5),
                'label_position' => 0.79,
                'label_percent_decimals' => 1,
                'inner_radius' => 0.6,
                'colors' => $graph_FREQUENCY_OF_ENTRIES_settings->colors,
                'width' => 750,
                'height' => 750,
            ]);

            $graph_DAILY_ENTRIES_settings = (object) [
                'values' => $this->getDailyEntries($campaign_id,$startDate),
                'colors' => [
                    '#1E80F0',
                    '#74B8F6',
                ]
            ];

            $graph_DAILY_ENTRIES = $this->pngGraph( $graph_DAILY_ENTRIES_settings->values, [
                'type' => 'BarGraph',
                'back_colour' => '#fff',
                'stroke_width' => 0,
                'stroke_colour' => '#fff',
                'pad_right' => 2,
                'pad_left' => 2,
                'show_label_key' => true,
                'show_label_amount' => true,
                'show_label_percent' => true,
                'show_bar_labels' => true,
                'bar_label_font' => 'sans-serif',
                'bar_label_font_size' => (11*2.25),
                'bar_label_position' => 'top',
                'show_axis_text_v' => false,
                'label_colour' => '#000',
                'axis_font' => 'sans-serif',
                'axis_font_size' => (11*2.25),
                'label_position' => 0.79,
                'show_axis_v' => false,
                'show_grid_v' => false,
                'decimal_digits_y' => 0,
                'colors' => $graph_DAILY_ENTRIES_settings->colors,
                'width' => 1272,
                'height' => 270,
            ]);

            $graph_HOURLY_ENTRIES_settings = (object) [
                'values' => $this->getHourlyEntries($campaign_id,$startDate),
                'colors' => [
                    '#E3DE12',
                    '#f39c12',
                ]
            ];

            $graph_HOURLY_ENTRIES = $this->pngGraph( $graph_HOURLY_ENTRIES_settings->values, [
                'type' => 'BarGraph',
                'back_colour' => '#fff',
                'stroke_width' => 0,
                'stroke_colour' => '#fff',
                'pad_right' => 2,
                'pad_left' => 2,
                'show_label_key' => true,
                'show_label_amount' => true,
                'show_label_percent' => true,
                'show_bar_labels' => true,
                'bar_label_font' => 'sans-serif',
                'bar_label_font_size' => (11*2.25),
                'bar_label_position' => 'top',
                'show_axis_text_v' => false,
                'bar_label_colour' => '#000',
                'axis_font' => 'sans-serif',
                'axis_font_size' => (9*2.25),
                'label_position' => 0.79,
                'show_axis_v' => false,
                'show_grid_v' => false,
                'decimal_digits_y' => 0,
                'colors' => $graph_HOURLY_ENTRIES_settings->colors,
                'width' => 1272,
                'height' => 270,
            ]);

            $graph_STATE_BREAKDOWN_settings = (object) [
                'values' => $this->getEntriesByState($campaign_id,$startDate),
            ];

            $graph_RETAILER_BREAKDOWN_settings = (object) [
                'values' => $this->getEntriesByRetailer($campaign_id,$startDate),
            ];

            $graph_DESTINATION_BREAKDOWN_settings = (object) [
                'values' => $this->getEntriesByDestination($campaign_id,$startDate),
                'colors' => [
                    '#0073b7',
                    '#00a65a',
                    '#f39c12',
                    '#dd4b39',
                    '#A5007A',
                ]
            ];

            $graph_PRODUCT_TOP10_settings = (object) [
                'values' => $this->getTopProducts($campaign_id,$startDate),
            ];

            $graph_APPROVED_REJECTED_settings = (object) [
                'values' => [
                    'Approved' => $this->getEntriesByStatus($campaign_id,'^(2|3|4)$',$startDate)->count(),
                	'Rejected' => $this->getEntriesByStatus($campaign_id,'^0$',$startDate)->count(),
                    'Pending' => $this->getEntriesByStatus($campaign_id,'^1$',$startDate)->count(),
                ],
    	        // 'values_totals' => 3996,
                'colors' => [
                    '#0073b7',
                	'#dd4b39',
                    '#00a65a',
                ]
            ];

            $graph_APPROVED_REJECTED = $this->pngGraph( $graph_APPROVED_REJECTED_settings->values, [
                'type' => 'PieGraph',
                'back_colour' => '#fff',
                'stroke_colour' => '#fff',
                'pad_right' => 2,
                'pad_left' => 2,
                'show_label_key' => false,
                'show_label_amount' => false,
                'show_label_percent' => true,
                'label_colour' => '#fff',
                'label_font' => 'sans-serif',
                'label_font_size' => (12*3.5),
                'label_position' => 0.79,
                'label_percent_decimals' => 1,
                'inner_radius' => 0.6,
                'colors' => $graph_APPROVED_REJECTED_settings->colors,
                'width' => 750,
                'height' => 750,
            ]);

            $graph_WINNERS_LOSERS_settings = (object) [
                'values' => [
                	'Losers' => $this->getEntriesByStatus($campaign_id,'^(2|3)$',$startDate)->count(),
                	'Winners' => $this->getEntriesByStatus($campaign_id,'^4$',$startDate)->count(),
                ],
    	        // 'values_totals' => 3996,
                'colors' => [
                	'#0073b7',
                    '#f39c12',
                ]
            ];

            $graph_WINNERS_LOSERS = $this->pngGraph( $graph_WINNERS_LOSERS_settings->values, [
                'type' => 'PieGraph',
                'back_colour' => '#fff',
                'stroke_colour' => '#fff',
                'pad_right' => 2,
                'pad_left' => 2,
                'show_label_key' => false,
                'show_label_amount' => false,
                'show_label_percent' => true,
                'label_colour' => '#fff',
                'label_font' => 'sans-serif',
                'label_font_size' => (12*3.5),
                'label_position' => 0.79,
                'label_percent_decimals' => 1,
                'inner_radius' => 0.6,
                'colors' => $graph_WINNERS_LOSERS_settings->colors,
                'width' => 750,
                'height' => 750,
            ]);

            $data['campaigns'][] = [
                'total_entries' => Submission::where([
                    ['campaign_id',$campaign_id],
                    ['created_at','>=', date('Y-m-d 00:00:00', $startDate)],
                    ['created_at','<=', date('Y-m-d 23:59:59', strtotime('+6days',$startDate))]
                ])->whereMetaValue('status','REGEXP','^[0-4]$')->count(),
                'total_unique_entries' => $this->getUniqueEntries($campaign_id,$startDate)->count(),
                'total_opt_in' => $this->getUniqueOptIns($campaign_id,$startDate),
                'graphs' => [
                    [
                        'graph' => $graph_FREQUENCY_OF_ENTRIES,
                        'values' => $graph_FREQUENCY_OF_ENTRIES_settings->values,
                        'colors' => $graph_FREQUENCY_OF_ENTRIES_settings->colors,
                    ],
                    [
                        'graph' => $graph_DAILY_ENTRIES,
                        'values' => $graph_DAILY_ENTRIES_settings->values,
                        'colors' => $graph_DAILY_ENTRIES_settings->colors,
                    ],
                    [
                        'graph' => $graph_HOURLY_ENTRIES,
                        'values' => $graph_HOURLY_ENTRIES_settings->values,
                        'colors' => $graph_HOURLY_ENTRIES_settings->colors,
                    ],
                    [
                        'values' => $graph_STATE_BREAKDOWN_settings->values,
                    ],
                    [
                        'values' => $graph_RETAILER_BREAKDOWN_settings->values,
                    ],
                    [
                        'values' => $graph_DESTINATION_BREAKDOWN_settings->values,
                        'colors' => $graph_DESTINATION_BREAKDOWN_settings->colors,
                    ],
                    [
                        'values' => $graph_PRODUCT_TOP10_settings->values,
                    ],
                    [
                        'values' => $this->getTopProductsByState($campaign_id,$startDate),
                    ],
                    [
                        'values' => $this->getTopProductsByRetailer($campaign_id,$startDate),
                    ],
                    [
                        'graph' => $graph_APPROVED_REJECTED,
                        'values' => $graph_APPROVED_REJECTED_settings->values,
                        'colors' => $graph_APPROVED_REJECTED_settings->colors,
                    ],
                    [
                        'graph' => $graph_WINNERS_LOSERS,
                        'values' => $graph_WINNERS_LOSERS_settings->values,
                        'colors' => $graph_WINNERS_LOSERS_settings->colors,
                    ],
                ]
            ];

        }

		// return view('admin.pdf.invoice',$data);
		$pdf = PDF::loadView('admin.pdf.invoice',$data);
		return $pdf;
	}

    public function download($filename)
    {
        return $this->generate($filename)->download("$filename.pdf");
    }

    public function stream($filename)
    {
        return $this->generate($filename)->stream("$filename.pdf");
    }

    public function save($filename)
    {
        // Set Destination Folder
        if (is_dir($destination_directory = storage_path('reports/pdf'))) {
            chmod($destination_directory, 0755);
        } else {
            mkdir($destination_directory, 0755, true);
        }
        return $this->generate($filename)->save("{$destination_directory}/{$filename}.pdf");
    }

    public function show($filename)
    {
        try {
            $destination_directory = storage_path('reports/pdf');
            header("Content-type: application/pdf");
            header("Content-Disposition: attachment; filename={$filename}.pdf");
            readfile("{$destination_directory}/{$filename}.pdf");
        } catch (\Exception $e) {
            abort(404);
        }
    }

	/**
     * Generate Graphs as base64;PNG
     *
     * @see http://www.goat1000.com/svggraph.php
     * @param array  $values
     * @param array  $options
     * @return data:image/png;base64
     */
    private function pngGraph($values=[],$options=[],$outputsvg=false)
    {
        // Setup Defaults
        $defaults = array(
            'back_colour' => '#fff',
            'stroke_width' => (4*3),
            'stroke_colour' => '#000',
            'back_stroke_width' => 0,
            'pad_right' => (8*3),
            'pad_left' => (8*3),
            'show_labels' => true,
            'show_label_key' => false,
            'show_label_amount' => true,
            'show_label_percent' => false,
            'label_font' => 'sans-serif',
            'label_font_size' => (12*3),
            'label_colour' => '#000',
            'sort' => false,
            'start_angle' => -90,
            'width' => 900,
            'height' => 900,
            'colors' => null,
            'type' => 'BarGraph',
        );
        // Override Defaults
        $settings = array_merge($defaults,$options);

        $height = $settings['height'];
        unset($settings['height']);

        $width = $settings['width'];
        unset($settings['width']);

        $colors = $settings['colors'];
        unset($settings['colors']);

        $type = $settings['type'];
        unset($settings['type']);

        // Create Graph
        $graph = new SVGGraph($width, $height, $settings);

        // Set Colors
        $graph->colours = $colors;

        // Set Values
        $graph->Values($values);

        if($outputsvg) return 'data:image/svg+xml;utf8,'.str_replace('"','\'',$graph->Fetch($type,false,false));

        // Convert to PNG
        $image = new IMagick();
        $image->setBackgroundColor(new ImagickPixel('transparent'));
        $image->readImageBlob($graph->Fetch($type));
        $image->setImageFormat("png32");

        // Return Base64
        return 'data:image/png;base64,'.base64_encode($image);
    }

    private function getUniqueEntries($campaign_id,$startDate)
    {
    	$submissions = SubmissionMeta::whereIn(
    		'submission_id',
    		Submission::where([
    			['campaign_id',$campaign_id],
                ['created_at','>=', date('Y-m-d 00:00:00', $startDate)],
	    		['created_at','<=', date('Y-m-d 23:59:59', strtotime('+6days',$startDate))]
	    	])->pluck('id')
    	)->where([['meta_key','email']])->get()->unique('meta_value')->pluck('meta_value');
    	return $submissions;
    }

    private function getUniqueOptIns($campaign_id,$startDate)
    {
    	$emails = $this->getUniqueEntries($campaign_id,$startDate);
    	$optins = 0;
    	foreach($emails as $email) {
    		if(Submission::whereMetaValue('email',$email)->whereMetaValue('opt_in','yes')->first())
    			$optins++;
    	}
    	return $optins;
    }

    private function getEntriesFreq($campaign_id,$startDate)
    {
    		$submissions = SubmissionMeta::whereIn(
    		'submission_id',
    		Submission::where([
    			['campaign_id',$campaign_id],
                ['created_at','>=', date('Y-m-d 00:00:00', $startDate)],
	    		['created_at','<=', date('Y-m-d 23:59:59', strtotime('+6days',$startDate))]
	    	])->pluck('id')
    	)->where([['meta_key','email']])->pluck('meta_value');

    	$array_count_values = array_count_values( array_count_values( $submissions->toArray() ) );
    	$a = [
    		'Once' => 0,
    		'Twice' => 0,
    		'3—5' => 0,
    		'6—10' => 0,
    		'Over 10' => 0,
    	];
    	foreach ($array_count_values as $key => $value) {
    		if(intval($key)==1) {
    			$a['Once'] += $value;
    		} else if(intval($key)==2) {
    			$a['Twice'] += $value;
    		} else if(intval($key)>=3&&intval($key)<=5) {
    			$a['3—5'] += $value;
    		} else if(intval($key)>=6&&intval($key)<=10) {
    			$a['6—10'] += $value;
    		} else {
    			$a['Over 10'] += $value;
    		}
    	}
    	return $a;
    }

    private function getDailyEntries($campaign_id,$startDate)
    {
    	$days = [];
    	for ($i=0; $i < 7; $i++) {
    		$date = strtotime("+{$i}days",$startDate);
    		$days[date('D',$date)] = Submission::where([
    			['campaign_id',$campaign_id],
                ['created_at','>=', date('Y-m-d 00:00:00', $date)],
	    		['created_at','<=', date('Y-m-d 23:59:59', $date)]
            ])->count();
    	}
    	return $days;
    }

    private function getHourlyEntries($campaign_id,$startDate)
    {
    	$hours = [];
    	for ($i=0; $i < 7; $i++) {
    		$date = strtotime("+{$i}days",$startDate);

    		for ($i=0; $i < 24; $i++) {
	        	$h = date('ga',strtotime("$i:00:00"));
	        	$count = Submission::where([
	    			['campaign_id',$campaign_id],
	                ['created_at','>=', date("Y-m-d $i:00:00", $date)],
		    		['created_at','<=', date("Y-m-d $i:59:59", $date)]
	            ])->count();
	            if(isset($hours[$h])) {
	            	$hours[$h] += $count;
	            } else {
	            	$hours[$h] = $count;
	            }
	        }
    	}
    	return $hours;
    }

    private function getEntriesByState($campaign_id,$startDate)
    {
    	$states = [];
    	if($campaign_id == 1) {
    		$states = [
    			'NSW' => 0,
				'QLD' => 0,
				'SA' => 0,
				'TAS' => 0,
				'VIC' => 0,
				'WA' => 0,
				'ACT' => 0,
				'NT' => 0,
    		];
    	}
    	if($campaign_id == 2) {
    		$subs = SubmissionMeta::whereIn(
				'submission_id',
				Submission::where([
	    			['campaign_id',$campaign_id],
	            ])->pluck('id')
			)->where([['meta_key','address_state']])->get()->unique('meta_value')->pluck('meta_value');
			foreach($subs as $sub) {
				$states[$sub] = 0;
			}
    	}
    	foreach ($states as $state => $val) {
			$submissions = SubmissionMeta::whereIn(
				'submission_id',
				Submission::where([
	    			['campaign_id',$campaign_id],
	                ['created_at','>=', date("Y-m-d 00:00:00", $startDate)],
		    		['created_at','<=', date("Y-m-d 23:59:59", strtotime('+6days',$startDate))]
	            ])->pluck('id')
			)->where([['meta_key','address_state'],['meta_value',$state]]);
			$states[$state] = $submissions->count();
		}
		arsort($states);
    	return $states;
	}

	private function getEntriesByRetailer($campaign_id,$startDate)
    {
    	$form_content = Campaign::find($campaign_id)->meta('form_content');

    	$retailers = [];
    	foreach($form_content[16]->values as $retailer) {
			$retailers[$retailer->value] = 0;
		}

    	foreach ($retailers as $retailer => $val) {
			$submissions = SubmissionMeta::whereIn(
				'submission_id',
				Submission::where([
	    			['campaign_id',$campaign_id],
	                ['created_at','>=', date("Y-m-d 00:00:00", $startDate)],
		    		['created_at','<=', date("Y-m-d 23:59:59", strtotime('+6days',$startDate))]
	            ])->pluck('id')
			)->where([['meta_key','retailer'],['meta_value',$retailer]]);
			$retailers[$retailer] = $submissions->count();
		}
		arsort($retailers);
    	return $retailers;
	}

	private function getEntriesByDestination($campaign_id,$startDate)
    {
    	$form_content = Campaign::find($campaign_id)->meta('form_content');

    	$destinations = [];
    	foreach($form_content[23]->values as $destination) {
			$destinations[$destination->value] = 0;
		}

    	foreach ($destinations as $destination => $val) {
			$submissions = SubmissionMeta::whereIn(
				'submission_id',
				Submission::where([
	    			['campaign_id',$campaign_id],
	                ['created_at','>=', date("Y-m-d 00:00:00", $startDate)],
		    		['created_at','<=', date("Y-m-d 23:59:59", strtotime('+6days',$startDate))]
	            ])->pluck('id')
			)->where([['meta_key','destination'],['meta_value',$destination]]);
			$destinations[$destination] = $submissions->count();
		}
		arsort($destinations);
    	return $destinations;
	}

	private function getTopProducts($campaign_id,$startDate)
    {
    	$submissions = SubmissionMeta::whereIn(
			'submission_id',
			Submission::where([
    			['campaign_id',$campaign_id],
                ['created_at','>=', date("Y-m-d 00:00:00", $startDate)],
	    		['created_at','<=', date("Y-m-d 23:59:59", strtotime('+6days',$startDate))]
            ])->pluck('id')
		)->where([['meta_key','product']])->pluck('meta_value');
		$array_count_values = array_count_values( $submissions->toArray() );

		arsort($array_count_values);

		$a = [];
		foreach($array_count_values as $key => $array_count_value) {
			if(count($a) >= 10) break;
			$a[$key] = $array_count_value;
		}

    	return $a;
	}

	private function getTopProductsByState($campaign_id,$startDate)
    {
    	$states = [];
    	if($campaign_id == 1) {
    		$states = [
    			'NSW' => 0,
				'QLD' => 0,
				'SA' => 0,
				'TAS' => 0,
				'VIC' => 0,
				'WA' => 0,
				'ACT' => 0,
				'NT' => 0,
    		];
    	}
    	if($campaign_id == 2) {
    		$subs = SubmissionMeta::whereIn(
				'submission_id',
				Submission::where([
	    			['campaign_id',$campaign_id],
	            ])->pluck('id')
			)->where([['meta_key','address_state']])->get()->unique('meta_value')->pluck('meta_value');
			foreach($subs as $sub) {
				$states[$sub] = 0;
			}
    	}
    	foreach ($states as $state => $val) {
			$submissions = SubmissionMeta::whereIn(
				'submission_id',
				SubmissionMeta::whereIn(
					'submission_id',
					Submission::where([
		    			['campaign_id',$campaign_id],
		                ['created_at','>=', date("Y-m-d 00:00:00", $startDate)],
			    		['created_at','<=', date("Y-m-d 23:59:59", strtotime('+6days',$startDate))]
		            ])->pluck('id')
				)->where([['meta_key','address_state'],['meta_value',$state]])->pluck('submission_id')
			)->where([['meta_key','product']])->pluck('meta_value');
			$array_count_values = array_count_values( $submissions->toArray() );
			arsort($array_count_values);
			$a = [];
			foreach($array_count_values as $key => $array_count_value) {
				if(count($a) >= 10) break;
				$a[$key] = $array_count_value;
			}
			$states[$state] = count($a)?$a:['No products bought'=>''];
		}

    	return $states;
	}

	private function getTopProductsByRetailer($campaign_id,$startDate)
    {
    	$form_content = Campaign::find($campaign_id)->meta('form_content');

    	$retailers = [];
    	foreach($form_content[16]->values as $retailer) {
			$retailers[$retailer->value] = 0;
		}

    	foreach ($retailers as $retailer => $val) {
			$submissions = SubmissionMeta::whereIn(
				'submission_id',
				SubmissionMeta::whereIn(
					'submission_id',
					Submission::where([
		    			['campaign_id',$campaign_id],
		                ['created_at','>=', date("Y-m-d 00:00:00", $startDate)],
			    		['created_at','<=', date("Y-m-d 23:59:59", strtotime('+6days',$startDate))]
		            ])->pluck('id')
				)->where([['meta_key','retailer'],['meta_value',$retailer]])->pluck('submission_id')
			)->where([['meta_key','product']])->pluck('meta_value');
			$array_count_values = array_count_values( $submissions->toArray() );
			arsort($array_count_values);
			$a = [];
			foreach($array_count_values as $key => $array_count_value) {
				if(count($a) >= 10) break;
				$a[$key] = $array_count_value;
			}
			$retailers[$retailer] = count($a)?$a:['No products bought'=>''];
		}

    	return $retailers;
	}

	private function getEntriesByStatus($campaign_id,$status,$startDate)
    {
    	$submissions = Submission::where([
			['campaign_id',$campaign_id],
            ['created_at','>=', date("Y-m-d 00:00:00", $startDate)],
    		['created_at','<=', date("Y-m-d 23:59:59", strtotime('+6days',$startDate))]
        ])->whereMetaValue('status','REGEXP',$status);
		return $submissions;
    }
}