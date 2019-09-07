<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Backup;

class BackupMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backupmigrate {action : Actions available: exportsql, exportfiles, restore}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CLI used to backup and restore the database and storage files.';

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
        switch ($this->argument('action')) {
            case 'exportsql':
                if( Backup::exportSQL() ){
                    $this->info( 'Database has been exported.' );
                }
                break;
            case 'exportfiles':
                if( Backup::exportFiles() ){
                    $this->info( 'Files have been exported.' );
                }
                break;
            case 'restore':
                $dest = config('backup.path');
                $files = glob($dest.'/*.gz');

                if(!$files)
                {
                    $this->error( 'No backup files found.' );
                    break;
                }

                $filenames = ['Cancel'];
                foreach ($files as $file) {
                    $filenames[] = basename($file);
                }

                $rfile = $this->choice('Please select file to restore:',$filenames);

                if($rfile == 'Cancel')
                {
                    $this->error( 'Restore cancelled.' );
                    break;
                }

                if(preg_match('/\.sql\.gz$/i',$rfile)) {
                    $this->info( 'Restoring DB from: '.$rfile );
                    $this->output->progressStart(10);

                    for ($i=0; $i < 7; $i++) {
                        usleep(200000);
                        $this->output->progressAdvance();
                    }

                    $success = Backup::restoreSQL($rfile);

                    $this->output->progressFinish();

                    if( $success !== true ) {
                        $this->error($success);
                    }

                } else if(preg_match('/\.files\.gz$/i',$rfile)) {
                    $this->info( 'Restoring Files from: '.$rfile );
                    $this->output->progressStart(10);

                    for ($i=0; $i < 7; $i++) {
                        usleep(200000);
                        $this->output->progressAdvance();
                    }

                    $success = Backup::restoreFiles($rfile);

                    $this->output->progressFinish();

                    if( $success !== true ) {
                        $this->error($success);
                    }
                }

                break;
        }
    }
}
