<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionTemp extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transaction_temp',function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('tranId');
			$table->integer('sawId');
			$table->integer('productId');
			$table->string('truck_register_number',10);
			$table->string('weight_no',10);
			$table->string('customer_name_in',50);
			$table->dateTime('datetime_in');
			$table->decimal('weight_in');
			$table->string('customer_name_out',50);
			$table->dateTime('datetime_out');
			$table->decimal('weight_out');
			$table->decimal('weight_net');
			$table->decimal('weight_tare');
			$table->decimal('weight_total');
			$table->decimal('product_price_unit');
			$table->decimal('price_total')->nullable();
			$table->string('type_name',10);
			$table->text('pic_path');
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
		Schema::drop('transaction_temp');
	}

}
