<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumersMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('consumers_meta'))
            Schema::create('consumers_meta', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('consumer_id');
                $table->string('meta_key');
                $table->longText('meta_value')->nullable();
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
        Schema::dropIfExists('consumers_meta');
    }
}
