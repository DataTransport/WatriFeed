<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePathwaysTable extends Migration {

	public function up()
	{
		Schema::create('pathways', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('pathway_id', 100);
			$table->string('from_stop_id', 100);
			$table->string('to_stop_id', 100);
			$table->string('pathway_mode', 10);
			$table->tinyInteger('is_bidirectional');
			$table->string('length',50)->nullable();
			$table->string('traversal_time', 100)->nullable();
			$table->string('stair_count', 100)->nullable();
			$table->string('max_slope', 100)->nullable();
			$table->string('min_width', 100)->nullable();
			$table->string('signposted_as', 100)->nullable();
			$table->string('reversed_signposted_as', 100)->nullable();
			$table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('pathways');
	}
}
