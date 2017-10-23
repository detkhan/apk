<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WeightOutcoming extends Model {
	use SoftDeletes;
	protected $table = 'weight_outcoming';
	protected $primaryKey = 'weight_outcoming_id';
	protected $fillable = [
		'weight_outcoming_id',
		'sawId',
		'wood_grades_weight',
		'slab_weight',
		'sawdust_weight',
		'datetime',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',
	];
}
