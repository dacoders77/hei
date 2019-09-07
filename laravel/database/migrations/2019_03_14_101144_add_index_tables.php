<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns_meta', function(Blueprint $table) {
            $table->index('campaign_id');
            $table->index('meta_key');
        });

        Schema::table('submissions_meta', function(Blueprint $table) {
            $table->index('submission_id');
            $table->index('meta_key');
        });

        Schema::table('venues_meta', function(Blueprint $table) {
            $table->index('venue_id');
            $table->index('meta_key');
        });

        Schema::table('vouchers_meta', function(Blueprint $table) {
            $table->index('voucher_id');
            $table->index('meta_key');
        });

        Schema::table('users_meta', function(Blueprint $table) {
            $table->index('user_id');
            $table->index('meta_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
