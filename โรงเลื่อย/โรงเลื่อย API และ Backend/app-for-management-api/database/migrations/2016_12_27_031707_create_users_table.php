<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users',function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('userId');
			$table->string('email',150);
			$table->string('password',200);
			$table->string('firstname',50);
			$table->string('lastname',50);
			$table->enum('status',['on','off'])->default('off');
			$table->enum('type',['CEO','Manager']);
			$table->string('branch',50);
			$table->string('remember_token', 100)->nullable();
			$table->string('created_by',30);
			$table->string('updated_by',30);
			$table->string('deleted_by',30)->nullable();
			$table->datetime('created_at');
			$table->datetime('updated_at');
			$table->datetime('deleted_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
