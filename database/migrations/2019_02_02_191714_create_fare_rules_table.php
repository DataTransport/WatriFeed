<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFareRulesTable extends Migration {

	public function up()
	{
		Schema::create('fare_rules', function(Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
			$table->string('fare_id', 100);
			$table->string('route_id', 100)->nullable();
			$table->string('origin_id', 100)->nullable();
			$table->string('destination_id', 100)->nullable();
			$table->string('contains_id', 100)->nullable();
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('fare_rules');
	}
}
