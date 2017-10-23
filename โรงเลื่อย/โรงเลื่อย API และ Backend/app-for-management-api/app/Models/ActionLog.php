<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ActionLog extends Model {

	use SoftDeletes;
	protected $table = 'action_log';
	protected $primaryKey = 'actionId';
	protected $fillable = [
		'actionId',
		'userId',
		'event',
		'function_name',
		'action',
		'value',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',
	];

}
