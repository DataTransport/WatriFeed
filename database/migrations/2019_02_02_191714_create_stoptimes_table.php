<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoptimesTable extends Migration {

	public function up()
	{
		Schema::create('stoptimes', function(Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
			$table->string('trip_id', 100);
			$table->time('arrival_time')->nullable();
			$table->time('departure_time')->nullable();
			$table->string('stop_id', 100);
			$table->string('stop_sequence', 100);
			$table->string('stop_headsign', 100)->nullable();
			$table->string('pickup_type', 100)->nullable();
			$table->string('drop_off_type', 100)->nullable();
			$table->string('shape_dist_traveled', 100)->nullable();
			$table->string('timepoint', 100)->nullable();
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('stoptimes');
	}
}
