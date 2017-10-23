<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Users extends Model implements AuthenticatableContract{
	use Authenticatable, SoftDeletes;

	protected $table = 'users';
	protected $primaryKey = 'userId';
	protected $fillable = [
		'email',
		'password',
		'firstname',
		'lastname',
		'status',
		'type',
		'branch',
		'remember_token',
		'forget_token',
		'created_by',
		'updated_by',
		'deleted_by',
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $hidden = ['password', 'remember_token'];

}
