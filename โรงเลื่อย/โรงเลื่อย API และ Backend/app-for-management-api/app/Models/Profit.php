<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Profit extends Model {
	use SoftDeletes;
	protected $table = 'profit_loss';
	protected $primaryKey = 'profit_loss_id';
	protected $fillable = [
		'profit_loss_id',
		'sawId',
		'incoming_total',
		'outcoming_total',
		'gross_profit_total',
		'costs_total',
		'profit_loss_total',
		'datetime',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',
	];
}
