<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoutesTable extends Migration {

	public function up()
	{
		Schema::create('routes', function(Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
			$table->string('route_id', 100);
			$table->string('agency_id', 100)->nullable();
			$table->string('route_short_name', 100)->nullable();
			$table->string('route_long_name', 100)->nullable();
			$table->text('route_desc')->nullable()->nullable();
			$table->string('route_type', 5);
			$table->string('route_url', 100)->nullable();
			$table->string('route_color', 100)->nullable();
			$table->string('route_text_color', 100)->nullable();
			$table->string('route_sort_order', 100)->nullable();
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('routes');
	}
}
