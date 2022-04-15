<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrequenciesTable extends Migration {

	public function up()
	{
		Schema::create('frequencies', function(Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
			$table->string('trip_id', 100);
			$table->time('start_time');
			$table->time('end_time');
			$table->string('headway_secs', 100);
			$table->string('exact_times', 100)->nullable();
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('frequencies');
	}
}
