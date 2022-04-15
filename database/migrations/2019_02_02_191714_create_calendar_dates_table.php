<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalendarDatesTable extends Migration {

	public function up()
	{
		Schema::create('calendar_dates', function(Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
            $table->string('service_id', 50);
            $table->string('date', 20);
            $table->string('exception_type', 20);
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('calendar_dates');
	}
}
