<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgenciesTable extends Migration {

    public function up()
    {
        Schema::create('agencies', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('agency_id', 100)->nullable();
            $table->string('agency_name', 100);
            $table->string('agency_url', 200);
            $table->string('agency_timezone', 100);
            $table->string('agency_lang', 20)->nullable();
            $table->string('agency_phone', 50)->nullable();
            $table->string('agency_fare_url', 200)->nullable();
            $table->string('agency_email', 200)->nullable();
            $table->integer('gtfs_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::drop('agencies');
    }
}
