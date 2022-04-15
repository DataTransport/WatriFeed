<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLevelsTable extends Migration {

	public function up()
	{
		Schema::create('levels', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('level_id', 100);
			$table->string('level_index', 100);
			$table->string('level_name', 100)->nullable();
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('levels');
	}
}
