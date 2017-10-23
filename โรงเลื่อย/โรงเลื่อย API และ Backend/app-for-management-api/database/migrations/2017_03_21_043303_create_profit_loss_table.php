<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfitLossTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profit_loss',function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('profit_loss_id');
			$table->integer('sawId');
			$table->decimal('incoming_total');
			$table->decimal('outcoming_total');
			$table->decimal('gross_profit_total');
			$table->decimal('costs_total');
			$table->decimal('profit_loss_total');
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
		Schema::drop('profit_loss');
	}

}
