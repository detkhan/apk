<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FireWood extends Model {
	use SoftDeletes;
	protected $table = 'fire_wood';
	protected $primaryKey = 'fire_wood_id';
	protected $fillable = [
		'fire_wood_id',
		'sawId',
		'fire_wood_incoming',
		'fire_wood_sale',
		'firewood_total',
		'firewood_losts',
		'datetime',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',
	];
}
