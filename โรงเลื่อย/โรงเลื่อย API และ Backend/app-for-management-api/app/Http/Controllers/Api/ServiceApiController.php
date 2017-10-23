<?php namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ConnectionLog;
use App\Http\Requests\ServiceRequest;
use Illuminate\Http\Request;
use DB;
use Exception;
use Response;
use Schema;
use Input;
class ServiceApiController extends Controller {
	/**
	 * insert data when connect with visual basic (connecting program)
	 * from (access , mysql , mssql)
	 * status (failed , success)
	 * message (if error ?)
	 * @return message and status code
	 */
	public function postConnectionLog(ServiceRequest $request)
	{
		if(Schema::hasTable('connecting_log')){
			DB::beginTransaction();
			try {
				$model = new ConnectionLog;
				$data = array_filter($request->all());
				$data['created_by'] = 'Revo create';
				$data['updated_by'] = 'Revo updated';
				$model->create($data);
				DB::commit();
				return Response::json(
						[
						'results' => [
							'message' => 'Insert Complete.' , 
							'code' => 200 , 
							'msg' => 'OK'
							]
						]);
			}catch (Exception $e) {
				DB::rollback();
				$error = $e->getMessage();
					return Response::json(
						[
						'results' => [
							'message' => $error , 
							'code' => 400 ,
							'msg' => 'Bad Request'
							]
						]);
			}
		}else{
			return Response::json(
				[
				'results' => [
					'message' => 'Not found database.' , 
					'code' => 401 ,
					 'msg' => 'Unauthorized'
					 ]
				 ]);
		}
	}

}
