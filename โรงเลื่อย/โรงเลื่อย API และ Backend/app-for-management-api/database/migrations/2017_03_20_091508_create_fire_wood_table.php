<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFireWoodTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fire_wood',function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('fire_wood_id');
			$table->integer('sawId');
			$table->decimal('fire_wood_incoming');
			$table->decimal('fire_wood_sale');
			$table->decimal('firewood_total');
			$table->decimal('firewood_losts');
			$table->date('datetime');
			$table->string('created_by', 30);
			$table->string('updated_by', 30);
			$table->string('deleted_by', 30)->nullable();
			$table->dateTime('created_at');
			$table->dateTime('updated_at');
			$table->dateTime('deleted_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fire_wood');
	}

}
