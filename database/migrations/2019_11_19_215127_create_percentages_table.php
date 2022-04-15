<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('percentages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('row_total');
            $table->integer('row_current');
            $table->integer('percentage');
            $table->integer('gtfs_id');
            $table->string('file_name');
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
        Schema::dropIfExists('percentages');
    }
}
