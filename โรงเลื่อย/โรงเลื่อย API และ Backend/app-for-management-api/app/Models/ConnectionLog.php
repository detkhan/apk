<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ConnectionLog extends Model {
	
	use SoftDeletes;
	protected $table = 'connecting_log';
	protected $primaryKey = 'connId';
	protected $fillable = [
		'connId',
		'from',
		'status',
		'message',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',
	];
}
