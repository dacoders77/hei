<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Timestamp;
use Log;
use Exception;

class UpdateTimestamps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:timestamps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $timestamps = Timestamp::where([
            ['timestamp','<=',date('U')],
            ['status',0],
        ])->get();

        if(!$timestamps->count()) {
            $this->error( 'Nothing to update!' );
            return false;
        }

        foreach ($timestamps as $timestamp) {
            $timestamp->status = 1;
            $timestamp->save();
            $this->info( "Updated timestamp #{$timestamp->id}");
        }
    }

}
