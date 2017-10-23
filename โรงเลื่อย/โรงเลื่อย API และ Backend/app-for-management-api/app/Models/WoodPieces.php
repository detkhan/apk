<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WoodPieces extends Model {
	use SoftDeletes;
	protected $table = 'wood_pieces';
	protected $primaryKey = 'wood_pieces_id';
	protected $fillable = [
		'wood_pieces_id',
		'sawId',
		'wood_pieces_incoming',
		'timber_saw',
		'wood_sale',
		'total',
		'losts',
		'datetime',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',
	];
}
