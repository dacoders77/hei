<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('controller');
            $table->string('original_filename');
            $table->string('status')->default('pending');
            $table->integer('row_count')->default(0);
            $table->integer('row_offset')->default(0);
            $table->longText('request')->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('importers');
    }
}
