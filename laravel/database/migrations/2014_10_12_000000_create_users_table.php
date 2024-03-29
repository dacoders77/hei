<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('campaign');
            $table->string('uuid',20)->nullable();

            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Name
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            // Contact details
            $table->integer('status')->default(1);
            $table->date('status_change')->nullable();
            $table->longText('data')->nullable();
            $table->longText('secure_data')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
