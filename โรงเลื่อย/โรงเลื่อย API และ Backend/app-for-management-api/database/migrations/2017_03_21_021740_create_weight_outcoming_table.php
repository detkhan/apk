<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeightOutcomingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('weight_outcoming',function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('weight_outcoming_id');
			$table->integer('sawId');
			$table->decimal('wood_grades_weight');
			$table->decimal('slab_weight');
			$table->decimal('sawdust_weight');
			$table->dateTime('date');
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
		Schema::drop('weight_outcoming');
	}

}
