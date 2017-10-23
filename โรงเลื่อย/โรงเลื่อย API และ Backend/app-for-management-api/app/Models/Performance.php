<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Performance extends Model {
	use SoftDeletes;
	protected $table = 'performance';
	protected $primaryKey = 'performance_id';
	protected $fillable = [
		'performance_id',
		'sawId',
		'volume_product',
		'performance_type',
		'datetime',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',
	];
}
