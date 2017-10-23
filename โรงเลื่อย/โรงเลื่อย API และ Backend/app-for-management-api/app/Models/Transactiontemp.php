<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactiontemp extends Model {
	use SoftDeletes;
	protected $table = 'transaction_temp';
	protected $primaryKey = 'tranId';
	protected $fillable = [
		'tranId',
		'sawId',
		'productId',
		'truck_register_number',
		'weight_no',
		'customer_name_in',
		'datetime_in',
		'weight_in',
		'customer_name_out',
		'datetime_out',
		'weight_out',
		'weight_net',
		'weight_tare',
		'weight_total',
		'product_price_unit',
		'price_total',
		'type_name',
		'pic_path',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',		
	];
}
