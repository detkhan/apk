<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product',function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('productId');
			$table->string('product_type',20);
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
		Schema::drop('product');
	}

}
