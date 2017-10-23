<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConnectingLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('connecting_log',function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('connId');
			$table->string('sawmill_name',5);
			$table->enum('from',['access','mysql','mssql']);
			$table->string('status',10);
			$table->string('message',250);
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
		Schema::drop('connecting_log');
	}

}
