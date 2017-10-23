<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWoodPiecesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wood_pieces',function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('wood_pieces_id');
			$table->integer('sawId');
			$table->decimal('wood_pieces_incoming');
			$table->decimal('timber_saw');
			$table->decimal('wood_sale');
			$table->decimal('total');
			$table->decimal('losts');
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
		Schema::drop('wood_pieces');
	}

}
