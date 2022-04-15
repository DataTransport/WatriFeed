<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStopsTable extends Migration
{

    public function up()
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('stop_id', 100);
            $table->string('stop_code', 100)->nullable();
            $table->string('stop_name', 100)->nullable();
            $table->text('stop_desc')->nullable();
            $table->string('stop_lat', 100)->nullable();
            $table->string('stop_lon', 100)->nullable();
            $table->string('zone_id', 100)->nullable();
            $table->string('stop_url', 100)->nullable();
            $table->string('location_type', 100)->nullable();
            $table->string('parent_station', 100)->nullable();
            $table->string('stop_timezone', 100)->nullable();
            $table->string('wheelchair_boarding',2)->nullable();
            $table->string('level_id',100)->nullable();
            $table->string('platform_code',100)->nullable();
            $table->integer('gtfs_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::drop('stops');
    }
}
