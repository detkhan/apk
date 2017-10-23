<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\UtilityRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Transactiontemp;
use DB;
use Exception;
use Response;
use Schema;
use Input;

class UtilityApiController extends Controller {

	public function postUploadImage(UtilityRequest $request)
	{
		$getTime = time().rand(0,9999);
		$data = base64_decode($request->imagePath);
		$imageName = 'image'.$getTime.'.jpg';
		file_put_contents(__DIR__.'/../../../../public/uploadimage/'.$imageName,$data);
		if(file_exists(__DIR__.'/../../../../public/uploadimage/'.$imageName)){
			$results = [
					[
						'results' => 
						[
							'message' => 'Response success.',
							'code' => 200,
							'msg' => 'OK'
						],
						'data' => 
						[
							'message' => 'Have data.',
							'status' => 'true',
						]
					]
				];
			$response[] =
						[
							'image' => url('/uploadimage/'.$imageName),
						];
			return Response::json([$results,$response]);
		}else{
			return Response::json(
		 		[
		 			[
		 				'results' => 
		 				[
		 					'message' => 'Response success.',
		 					'code' => 200,
		 					'msg' => 'OK'
		 				],
		 				'data' =>
		 				[
		 					'message' => 'Upload Image Failed Please Check your server is available.',
		 					'status' => 'failed'
		 				]
		 			]
		 		]);
		}
	}

	public function postCheckTransaction (UtilityRequest $request){
		$response = [];
		$getCountData = DB::table('transaction_temp')->count();
		if($getCountData == $request->countNumber){
			$selectTranTemps = Transactiontemp::select('sawId',
				'productId',
				'truck_register_number',
				'weight_no',
				'customer_name_in',
				'datetime_in',
				'weight_in',
				'customer_name_out',
				'datetime_out',
				'weight_out',
				'weight_net',
				'weight_tare',
				'weight_total',
				'product_price_unit',
				'price_total',
				'type_name',
				'pic_path',
				'created_by',
				'updated_by',
				'deleted_by',
				'created_at',
				'updated_at',
				'deleted_at')->get();
			foreach ($selectTranTemps as $key => $tranTemp) {
				DB::beginTransaction();
				try {
					$model = new Transaction;
					$data['sawId'] = $tranTemp->sawId;
					$data['productId'] = $tranTemp->productId;
					$data['truck_register_number'] = $tranTemp->truck_register_number;
					$data['weight_no'] = $tranTemp->weight_no;
					$data['customer_name_in'] = $tranTemp->customer_name_in;
					$data['datetime_in'] = $tranTemp->datetime_in;
					$data['weight_in'] = $tranTemp->weight_in;
					$data['customer_name_out'] = $tranTemp->customer_name_out;
					$data['datetime_out'] = $tranTemp->datetime_out;
					$data['weight_out'] = $tranTemp->weight_out;
					$data['weight_net'] = $tranTemp->weight_net;
					$data['weight_tare'] = $tranTemp->weight_tare;
					$data['weight_total'] = $tranTemp->weight_total;
					$data['product_price_unit'] = $tranTemp->product_price_unit;
					$data['price_total'] = $tranTemp->price_total;
					$data['type_name'] = $tranTemp->type_name;
					$data['pic_path'] = $tranTemp->pic_path;
					$data['created_by'] = $tranTemp->created_by;
					$data['updated_by'] = $tranTemp->updated_by;
					$data['deleted_by'] = $tranTemp->deleted_by;
					$data['created_at'] = $tranTemp->created_at;
					$data['updated_at'] = $tranTemp->updated_at;
					$data['deleted_at'] = $tranTemp->deleted_at;
					$model->create($data);
					DB::commit();
					$response = 
								[
									'results' => 
										[
											'message' => 'Response success.' , 
											'code' => 200 , 
											'msg' => 'OK'
										],
									'data' =>
		 								[
		 									'message' => 'Insert data into transaction_table.',
		 									'status' => 'true'
		 								]
								];
				} catch (Exception $e) {
					DB::rollback();
					$error = $e->getMessage();
					$response = 
								[
									'results' => 
										[
											'message' => 'Response success.' , 
											'code' => 200 ,
											'msg' => 'Bad Request'
										],
									'data' =>
		 								[
		 									'message' => $error,
		 									'status' => 'failed'
		 								]
								];
				}
			}
			Transactiontemp::query()->truncate();
			return Response::json([$response]);
		}else{
			Transactiontemp::query()->truncate();
			return Response::json(
		 			[
		 				[
		 					'results' => 
		 					[
		 						'message' => 'Response success.',
		 						'code' => 200,
		 						'msg' => 'OK'
		 					],
		 					'data' =>
		 					[
		 						'message' => 'Data from access database not same in mysql database.',
		 						'status' => 'failed'
		 					]
		 				]
		 			]
		 	);
		}
	}
}
