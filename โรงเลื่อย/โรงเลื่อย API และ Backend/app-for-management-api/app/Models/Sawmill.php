<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Sawmill extends Model {
	use SoftDeletes;

	protected $table = 'sawmill';
	protected $primaryKey = 'sawId';
	protected $fillable = [
		'sawId',
		'fullname',
		'shortname',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',
	];
}
