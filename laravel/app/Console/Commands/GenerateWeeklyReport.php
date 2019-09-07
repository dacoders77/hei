<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\PDFReportsController as PDF;
use Log;
use Mail;

class GenerateWeeklyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:weeklyreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CLI used to generate weekly report as PDF and send via email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	try {
    		$filename = 'WeeklyReport_'.date('d-m-Y',strtotime('-1day'));

    		$pdf = new PDF;
    		$pdf->save($filename);
    		$this->info( 'Report generated' );

    		$this->sendEmail($filename);

    	} catch (\Exception $e) {
    		Log::error('Error generating PDF: '.$e->getMessage());
    		$this->error( 'Error generating PDF: '.$e->getMessage() );
    	}
    }

    private function sendEmail($filename)
    {
    	$recipients = [
    		[
				'address' => 'scott@digilante.com.au',
				'name' => 'Scott Windon',
			],
			[
				'address' => 'andy@pilgrimcommunications.com.au',
				'name' => 'Andy Burns',
			],
			[
				'address' => 'carla@noisybeast.com',
				'name' => 'Noisy Beast',
			],
    	];

    	foreach ($recipients as $recipient) {
    		$args = [
				'to' => $recipient,
				'from' => config('mail.from'),
				'reply' => config('mail.reply'),
				'subject' => 'Swisse | Weekly Report '.date('d-m-Y',strtotime('-1day')),
				'campaign_id' => 1,
			];

			try {
				Mail::send('campaigns.mail.weekly_report', $args, function ($message) use ($args,$filename) {

					$message->to($args['to']['address'], $args['to']['name']);

				    $message->sender($args['from']['address'], $args['from']['name']);
				    $message->replyTo($args['reply']['address'], $args['reply']['name']);

				    $message->subject($args['subject']);

				    $destination_directory = storage_path('reports/pdf');
				    $message->attach("{$destination_directory}/{$filename}.pdf");

				});
			} catch (\Exception $e) {
				Log::error('Error sending email: '.$e->getMessage());
			}
    	}
    }

}