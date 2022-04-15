<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalendarsTable extends Migration {

	public function up()
	{
		Schema::create('calendars', function(Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
			$table->string('service_id', 100);
			$table->tinyInteger('monday');
			$table->tinyInteger('tuesday');
			$table->tinyInteger('wednesday');
			$table->tinyInteger('thursday');
			$table->tinyInteger('friday');
			$table->tinyInteger('saturday');
			$table->tinyInteger('sunday');
			$table->string('start_date',50);
			$table->string('end_date',50);
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('calendars');
	}
}
