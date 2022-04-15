<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTripsTable extends Migration {

	public function up()
	{
		Schema::create('trips', function(Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
			$table->string('route_id', 100);
			$table->string('service_id', 100);
			$table->string('trip_id', 100);
			$table->string('trip_headsign', 100)->nullable();
			$table->string('trip_short_name', 100)->nullable();
			$table->string('direction_id',100)->nullable();
			$table->string('block_id',100)->nullable();
			$table->string('shape_id', 100)->nullable();
			$table->string('wheelchair_accessible', 100)->nullable();
			$table->string('bikes_allowed', 100)->nullable();
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('trips');
	}
}
