<?php namespace App\Classes;

use Illuminate\Http\Request;
use App\Http\Requests\ActionLogRequest;
use App\Models\Users;
use App\Models\ActionLog;
use DB;
use Exception;
use Response;
use Schema;
use Input;
class ActionLogClass{

	/**
	 * keep all action in application in to action_log database
	 *
	 * @return no
	 */
	public function postActionLogInApplication(Array $value)
	{
		$user = Users::where('email','=',$value['email'])->first();
		if(isset($user->userId)){
			DB::beginTransaction();
			try{
				$model = new ActionLog;
				$data = array_filter($value);
				$data['userId'] = $user->userId;
				$data['created_by'] = $value['email'];
				$data['updated_by'] = $value['email'];
				$model->create($data);
				DB::commit();
			}catch (Exception $e) {
				DB::rollback();
				$error = $e->getMessage();
			}
		}
	}

}