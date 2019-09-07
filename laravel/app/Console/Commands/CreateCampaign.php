<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CreateCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Campaign';

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
        // First check URL is valid
        try {
            $campaign_url = $this->checkUrl( $this->rask('Campaign URL? (required)') );
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        // Title of the campaign
        $campaign_title = $this->ask('Campaign title? (optional)','Untitled Campaign');

        // Should we create a blade template for this?
        // $create_blade = $this->rchoice(
        //     'Create blade template? (optional)',
        //     ['no', 'yes'],
        //     'no'
        // );

        // Should we create a controller for this?
        // $create_controller = $this->rchoice(
        //     'Create custom controller? (optional)',
        //     ['no', 'yes'],
        //     'no'
        // );

        // Create new Campaign in DB
        $id = DB::table('campaigns')->insertGetId([
            'title' => $campaign_title,
            'url' => $campaign_url,
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Create view file
        // if($create_blade == 'yes') {
        //     file_put_contents(resource_path('views') . "/campaigns/index_$id.blade.php","@extends('campaigns.layouts.wrapper')\n@section('content')\n@endsection");

        //     $this->info("Blade file 'index_$id.blade.php' created");
        // }

        // Create Controller File
        // if($create_controller == 'yes') {
        //     $exitCode = Artisan::call('make:controller', ['name' => 'Campaigns/CampaignController_'.$id]);

        //     // Get Controller File URI
        //     $controllerFile = app_path('Http/Controllers/Campaigns/CampaignController_'.$id.'.php');

        //     // Read the file in an array
        //     $original = file($controllerFile);

        //     // Insert
        //     array_splice( $original, 6, 0, array(
        //         "use App\Model\Campaigns\Campaign;\n",
        //         "use Illuminate\Support\Facades\Input;\n",
        //         "use Validator;\n",
        //     ) );

        //     // Save
        //     file_put_contents($controllerFile, implode("", $original));

        //     $this->info("Controller 'CampaignController_$id' created");
        // }

        // Thank you
        $this->info("Campaign '$id' created for '$campaign_url'");
        return 0;
    }

    private function checkUrl($url)
    {
        $url = rtrim( $url, '/' );

        if ( !filter_var( $url, FILTER_VALIDATE_URL ) ) {
            throw new \Exception("Invalid URL '$url'");
        }

        $campaign = DB::table('campaigns')->where( [
            ['url', $url],
            ['status', '!=', 3]
        ] )->first();

        if ( $campaign ) {
            throw new \Exception("Campaign already exists with URL '$url'");
        }

        return $url;
    }

    private function rask($q,$d=null)
    {
        $a = $this->ask($q,$d);
        if( !$a ) {
            $this->error('required');
            return $this->rask($q,$d);
        } else {
            return $a;
        }
    }

    private function rchoice($q,$c,$d=null)
    {
        $a = $this->choice($q,$c,$d);
        if( !$a ) {
            $this->error('required');
            return $this->rchoice($q,$c,$d);
        } else {
            return $a;
        }
    }
}
