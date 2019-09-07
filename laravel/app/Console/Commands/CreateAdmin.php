<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {email : The users email address}  {password=random : Set password for user, otherwise randomly created (min: 6 characters)} {first_name=John : Admins first name} {last_name=Smith : Admins last name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Admin for the Campaign Dashboard';

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
        // First check Email doesn't exist
        try {
            $email = $this->checkEmail();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        // Check password
        if( $this->argument('password') !== 'random' ){
            try {
                $password = $this->checkPassword();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
                return 1;
            }
        } else {
            $password = str_random(12);
        }

        // Create new Campaign in DB
        $id = DB::table('admins')->insertGetId([
            'email' => $email,
            'password' => Hash::make( $password ),
            'first_name' => trim( $this->argument('first_name') ),
            'last_name' => trim( $this->argument('last_name') ),
            'role' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->info("\nAdmin created\nEmail: $email\nPassword: $password\n");
        $this->info("Login here: ".env('APP_URL', 'http://localhost')."/admin/login\n");

        return 0;
    }

    private function checkEmail()
    {
        $email = trim( $this->argument('email') );

        if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            throw new \Exception("Invalid email '$email'");
        }

        $user = DB::table('admins')->where( [
            ['email', $email],
        ] )->first();

        if ( $user ) {
            throw new \Exception("Admin already exists with email '$email'");
        }

        return $email;
    }

    private function checkPassword()
    {
        $password = trim( $this->argument('password') );

        if ( strlen($password) < 6 ) {
            throw new \Exception("Password must be 6 characters or longer.");
        }

        return $password;
    }
}
