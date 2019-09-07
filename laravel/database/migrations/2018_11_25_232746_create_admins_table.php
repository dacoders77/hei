<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');

            // Required details
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Name
            $table->string('first_name');
            $table->string('last_name');

            $table->integer('role')->default(2);

            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('admins')->insert([
            'email' => 'scott@digilante.com.au',
            'password' => Hash::make( 'test123!' ),
            'first_name' => 'Scott',
            'last_name' => 'Windon',
            'role' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
