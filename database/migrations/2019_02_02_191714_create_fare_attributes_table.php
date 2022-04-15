<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFareAttributesTable extends Migration {

	public function up()
	{
		Schema::create('fare_attributes', function(Blueprint $table) {
            $table->increments('id');
			$table->timestamps();
			$table->string('fare_id', 100);
			$table->string('price', 100);
			$table->string('currency_type', 100);
			$table->string('payment_method', 100);
			$table->string('transfers', 100);
            $table->string('agency_id', 100)->nullable();
			$table->string('transfer_duration', 100)->nullable();
            $table->integer('gtfs_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('fare_attributes');
	}
}
