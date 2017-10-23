<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSawmillTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sawmill',function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('sawId');
			$table->enum('fullname',['Master Sadao','Master Nathavee','Master Satun','APK']);
			$table->enum('shortname',['MSD','MNT','MST','APK']);
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
		Schema::drop('sawmill');
	}

}
