<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Importers\Importer;
use Log;
use Exception;

class RunImporters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:importers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CLI used to run queued importers.';

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
        $this->line( "\nStarting importers" );
        $start = date('d-m-Y H:i:s');

        // Stop if importer active
        if(Importer::where('status','active')->first()) {
            $this->error( "Importer already running!" );
            $this->line( '' );
            return;
        }

        // Get import queue
        $importers = Importer::whereIn('status',['stopped','pending'])->orderBy('status','desc')->get();

        if(!$importers->count()) {
            $this->error( "Nothing to import!" );
            $this->line( '' );
            return;
        }

        $this->line( '' );

        // Loop importers
        foreach ($importers as $importer) {

            $istart = date('d-m-Y H:i:s');

            // Set as active
            $importer->status = 'active';
            $importer->save();

            // If fail set as stopped
            register_shutdown_function(function () use ($importer, $istart) {
                if($importer->status=='active') {
                    $importer->status = 'stopped';
                    $importer->save();

                    $error = "ID {$importer->id}: Stopped import in ".human_time_diff( $istart );

                    $this->error( $error );
                    $this->line( '' );
                    Log::error( $error );
                }
            });

            // Run importer
            $this->call_func( $importer->controller, [$importer] );

            // Done
            $importer->status = 'completed';
            $importer->save();

            $this->info( "ID {$importer->id}: Completed import in ".human_time_diff( $istart ) );

            unlink( storage_path("imports/tmp/{$importer->original_filename}") ) or die("Couldn't delete file");
        }

        $this->line( "\nCompleted all imports in ".human_time_diff( $start ) );
    }

    private function call_func($controller, $params=[])
    {
        // Get callback
        [$class, $method] = \Illuminate\Support\Str::parseCallback($controller, null);

        // Run callback
        return call_user_func_array([new $class, $method], $params);
    }
}
