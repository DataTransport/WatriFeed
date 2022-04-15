<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGtfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gtfs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 150);
            $table->string('password');
            $table->integer('user_id')->unsigned();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gtfs');
    }
}
