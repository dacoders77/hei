<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendVenueCSVs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendvenuecsvs {kit_id : Venue Kit ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CLI used to generate csv reports.';

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
        $venues = \Venue::where('kit',$this->argument('kit_id'))->get();
        $api = new \App\Http\Controllers\Admin\ApiController;
        if($venues) {
            foreach ($venues as $venue) {
                if($venue->meta('venue_email')) {
                    $csv = $api->campaign_1_download_vouchers(['kit_id'=>$venue->kit],false);
                    if( substr_count($csv,"\n") ) {
                        \Mail::send('campaigns.mail.venue_csv', [], function ($message) use ($venue,$csv) {
                            $message->to($venue->meta('venue_email'), $venue->meta('venue_name'));

                            $message->subject('Weekly Report - '.date('d/m/Y'));

                            $filename = 'Heineken Promotion - Weekly Report - '.date('d/m/Y').'.csv';

                            $message->attachData($csv,$filename);
                        });
                    }
                }
            }
        }
    }
}
