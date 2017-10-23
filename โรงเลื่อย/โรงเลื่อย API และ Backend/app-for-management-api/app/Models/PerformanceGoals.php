<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerformanceGoals extends Model {
	use SoftDeletes;
	protected $table = 'performance_goals';
	protected $primaryKey = 'goal_id';
	protected $fillable = [
		'goal_id',
		'sawId',
		'volume_product',
		'ab',
		'ab_c',
		'datetime',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',
	];
}
