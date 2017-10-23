<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerformanceGoalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('performance_goals', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('goal_id');
			$table->integer('sawId');
			$table->integer('volume_product');
			$table->integer('ab');
			$table->integer('ab_c');
			$table->date('datetime');
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
		Schema::drop('performance_goals');
	}

}
