<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransfersTable extends Migration {

	public function up()
	{
		Schema::create('transfers', function(Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
			$table->string('from_stop_id', 100);
			$table->string('to_stop_id', 100);
			$table->string('transfer_type', 100);
			$table->string('min_transfer_time', 100)->nullable();
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('transfers');
	}
}
