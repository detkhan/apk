<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('action_log',function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('actionId');
			$table->integer('userId');
			$table->enum('event',['login','view','take','search','insert','update','delete']);
			$table->string('function_name',30);
			$table->string('action',200);
			$table->string('value',200);
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
		Schema::drop('action_log');
	}

}
