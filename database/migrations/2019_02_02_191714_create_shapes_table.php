<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShapesTable extends Migration {

	public function up()
	{
		Schema::create('shapes', function(Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
			$table->string('shape_id', 100);
			$table->string('shape_pt_lat', 100);
			$table->string('shape_pt_lon', 100);
			$table->string('shape_pt_sequence', 100);
			$table->string('shape_dist_traveled', 100)->nullable();
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('shapes');
	}
}
