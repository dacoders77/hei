<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Jackiedo\DotenvEditor\DotenvEditor;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates .env files with required database information';

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
    public function handle(DotenvEditor $editor)
    {
        $APP_NAME = $this->rask('App name? (required)');

        $APP_ENV = $this->rchoice(
            'Environment? (required)',
            ['local', 'staging', 'production'],
            'local'
        );

        $APP_URL = $this->rask('App URL? (required)');

        $editor->setKeys([
            [
                'key' => 'APP_NAME',
                'value' => $APP_NAME
            ],
            [
                'key' => 'APP_ENV',
                'value' => $APP_ENV
            ],
            [
                'key' => 'APP_DEBUG',
                'value' => $APP_ENV=='production'?'false':'true'
            ],
            [
                'key' => 'APP_DEBUG',
                'value' => $APP_URL
            ]
        ]);

        $DB_DATABASE = $this->rask('MySQL Database name? (required)');

        $DB_KEYS = [
            [
                'key' => 'DB_CONNECTION',
                'value' => 'mysql'
            ],
            [
                'key' => 'DB_DATABASE',
                'value' => $DB_DATABASE
            ],
        ];

        $DB_HOST = $this->ask(
            'DB host?',
            '127.0.0.1'
        );
        $DB_KEYS[] = [
            'key' => 'DB_HOST',
            'value' => $DB_HOST
        ];

        $DB_PORT = $this->ask(
            'DB port?',
            '3306'
        );
        $DB_KEYS[] = [
            'key' => 'DB_PORT',
            'value' => $DB_PORT
        ];

        $DB_USERNAME = $this->rask(
            'DB username? (required)'
        );
        $DB_KEYS[] = [
            'key' => 'DB_USERNAME',
            'value' => $DB_USERNAME
        ];

        $DB_PASSWORD = $this->rask(
            'DB password? (required)'
        );
        $DB_KEYS[] = [
            'key' => 'DB_PASSWORD',
            'value' => $DB_PASSWORD
        ];

        $DB_TABLE_PREFIX = $this->ask(
            'DB table prefix? (optional)'
        );
        $DB_KEYS[] = [
            'key' => 'DB_TABLE_PREFIX',
            'value' => $DB_TABLE_PREFIX
        ];

        $editor->setKeys($DB_KEYS);

        $editor->setKey('APP_KEY','base64:'.base64_encode(\Illuminate\Encryption\Encrypter::generateKey($this->laravel['config']['app.cipher'])));

        $editor->save();

        $this->info('.env file is setup');

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
