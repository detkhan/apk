<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Sawmill;
use App\Models\Users;
use App\Models\Transactiontemp;
use DB;
use Exception;
use Response;
use Schema;
use Input;
use Carbon\Carbon;

class TransactionApiController extends Controller {
	/**
	 * insert transaction data into database
	 * use transaction table in mysql
	 * @return message and status code
	 */
	public function postInsertTransaction(TransactionRequest $request)
	{
		$response = [];
		DB::beginTransaction();
				try {
					$model = new Transaction;
					$data = array_filter($request->all());
					$data['created_by'] = $request->customer_name_in;
					$data['updated_by'] = $request->customer_name_in;
					$model->create($data);
					DB::commit();
					$response = [
								'results' => 
									[
										'message' => 'Response success.' , 
										'code' => 200 , 
										'msg' => 'OK'
									]
								];
				} catch (Exception $e) {
					DB::rollback();
					$error = $e->getMessage();
					$response = [
									'results' => 
									[
										'message' => $error , 
										'code' => 400 ,
										'msg' => 'Bad Request'
									]
								];
				}
		return Response::json($response);
	}
	/**
	 * insert transaction_temp data into database
	 * use transaction table in mysql
	 * @return message and status code
	 */
	public function postInsertTransactiontemp(TransactionRequest $request)
	{
		$response = [];
		DB::beginTransaction();
				try {
					$model = new Transactiontemp;
					$data = array_filter($request->all());
					$data['created_by'] = $request->customer_name_in;
					$data['updated_by'] = $request->customer_name_in;
					$model->create($data);
					DB::commit();
					$response = [
								'results' => 
									[
										'message' => 'Response success.' , 
										'code' => 200 , 
										'msg' => 'OK'
									],
									'data' =>
	 								[
	 									'message' => 'Have data.',
	 									'status' => 'true'
	 								]
								];
				} catch (Exception $e) {
					DB::rollback();
					$error = $e->getMessage();
					$response = [
									'results' => 
									[
										'message' => $error , 
										'code' => 400 ,
										'msg' => 'Bad Request'
									],
									'data' =>
	 								[
	 									'message' => 'Can not insert data into database.',
	 									'status' => 'failed'
	 								]
								];
				}
		return Response::json($response);
	}
	/**
	 * send 'sawId' and $currentDate to select data in database
	 * use transaction table in mysql 
	 * @return data , message and status code
	 */
	public function postRealtimeTransaction(TransactionRequest $request)
	{
		 $currentDate = Carbon::now()->toDateString();
		 $response = [];
		 $getSawmillname = Sawmill::where('sawId','=',$request->sawId)
		 ->select('fullname')->get()->first();
		 $getRealtimeDatas = Transaction::where('sawId','=',$request->sawId)
		 ->whereDate('datetime_out', '=', $currentDate)
		 ->select(
		 	'datetime_out',
		 	'weight_total',
		 	'product_price_unit',
		 	'price_total',
		 	'tranId',
		 	'datetime_in'
		 	)->get();
		 foreach ($getRealtimeDatas as $key => $getRealtimeData) {
		 	$response[] = 
		 	[
		 		'fullname' => $getSawmillname->fullname,
		 		'datetime' => $getRealtimeData->datetime_out,
		 		'transactionId' => $getRealtimeData->tranId,
		 		'weight_in' => ($getRealtimeData->weight_total),
		 		'product_price_unit' => $getRealtimeData->product_price_unit,
		 		'price_total' => $getRealtimeData->price_total,
		 		'time' => explode(" ", $getRealtimeData->datetime_in)[1]
		 	];
		 }
		 if(!empty($response)){
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
	 					'status' => 'true'
	 				]
	 			]
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
		 					'message' => 'No results.',
		 					'status' => 'failed'
		 				]
		 			]
		 		]);
		 }
	}
	public function postRealtimeReport(TransactionRequest $request){
		$currentDate = Carbon::now()->toDateString();
		$response = [];
		$users = Users::where('email','=',$request->email)->first();
		if(isset($users)){
			$branchs = explode(",",$users->branch);
			foreach ($branchs as $key => $branch) {
				$sawmill = Sawmill::where('shortname','=',$branch)
						->select('sawId','shortname','fullname')->first();
				
				$value = Transaction::where('sawId','=',$sawmill->sawId)
						->whereDate('datetime_out','=',$currentDate)
						->select(
							DB::raw('SUM(`weight_total`) as weight_total'), 
							DB::raw('SUM(`price_total`) as price_total'), 
							DB::raw('COUNT(*) as transaction_count')
						)->first();
				if(isset($value->weight_total) != null && isset($value->price_total) != null)
				{
					//check if price_total = 0 because it's division by zero 
					if (isset($value->price_total) != 0.00){
						$response[] = 
						[
							'sawId' => $sawmill->sawId,
							'fullname' => $sawmill->fullname,
							'name' => $sawmill->shortname,
							'weight_total' => $value->weight_total/1000, //convert to KG.
							'price_total' => $value->price_total,
							'transaction_count' => $value->transaction_count,
							'price_total_per_kg' => $value->weight_total/$value->price_total,
						];	
					}else{
						$response[] = 
						[
							'sawId' => $sawmill->sawId,
							'fullname' => $sawmill->fullname,
							'name' => $sawmill->shortname,
							'weight_total' => $value->weight_total/1000, //convert to KG.
							'price_total' => $value->price_total,
							'transaction_count' => $value->transaction_count,
							'price_total_per_kg' => '0',
						];
					}
				}
			}
		}
		if(!empty($response)){
			$results = 	[
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
							'status' => 'true'
						]
					]
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
							'message' => 'No results.',
							'status' => 'failed'
						]
					]
				]);
		}
	}
	/**
	 * get all data from sawmill (sawId) to show in application
	 * use transaction table and sawmill table in mysql
	 * @return data , message and status code
	 */
	public function getRealtimeReport(){
		$currentDate = Carbon::now()->toDateString();
		$response = [];
		$sawmills = Sawmill::all();
		foreach ($sawmills as $key => $sawmill) {
			$value = Transaction::where('sawId','=',$sawmill->sawId)
						->where('datetime_out','=',$currentDate)
						->select(
							DB::raw('SUM(`weight_total`) as weight_total'), 
							DB::raw('SUM(`price_total`) as price_total'), 
							DB::raw('COUNT(*) as transaction_count')
						)->first();
			if(isset($value->weight_total) != null && isset($value->price_total) != null)
			{
				$response[] = 
				[
					'sawId' => $sawmill->sawId,
					'fullname' => $sawmill->fullname,
					'name' => $sawmill->shortname,
					'weight_total' => $value->weight_total/1000, //convert to KG.
					'price_total' => $value->price_total,
					'transaction_count' => $value->transaction_count,
					'price_total_per_kg' => $value->weight_total/$value->price_total,
				];
			}
		}
		if(!empty($response)){
			$results = 	[
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
							'status' => 'true'
						]
					]
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
							'message' => 'No results.',
							'status' => 'failed'
						]
					]
				]);
		}
	}

	public function postTransactionDetail(TransactionRequest $request){
		$currentDate = Carbon::now()->toDateString();
		$response = [];
		$getTransactionDetails = Transaction::where('tranId','=',$request->tranId)
		->whereDate('datetime_out','=',$currentDate)
		->select('customer_name_in','customer_name_out','truck_register_number','weight_total','price_total','datetime_in','datetime_out','pic_path')->get();
		foreach ($getTransactionDetails as $key => $getTransactionDetail) {
			$response[] = [
				'customername_in' => $getTransactionDetail->customer_name_in,
				'customername_out' => $getTransactionDetail->customer_name_out,
				'weight_total' => $getTransactionDetail->weight_total,
				'truck_number' => $getTransactionDetail->truck_register_number,
				'price_total' => $getTransactionDetail->price_total,
				'datetime_in' => $getTransactionDetail->datetime_in,
				'datetime_out' => $getTransactionDetail->datetime_out,
				'picpath' => $getTransactionDetail->pic_path
			];
		}
		 if(!empty($response)){
		 	$results = 
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
		 					'message' => 'Have data.',
		 					'status' => 'true'
		 				]
		 			]
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
		 					'message' => 'No results.',
		 					'status' => 'failed'
		 				]
		 			]
		 		]);
		 }
	}
	public function getMaxNumber(){
		$response = [];
		$getTransactionDetails = Transaction::select('weight_no')
		->orderBy('weight_no','desc')->take(1)->get();
		foreach ($getTransactionDetails as $key => $getTransactionDetail) {
			$response[] = [
				'weight_no' => $getTransactionDetail->weight_no
			];
		}
		if(!empty($response)){
		 	$results = 
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
		 					'message' => 'Have data.',
		 					'status' => 'true'
		 				]
		 			]
		 		];
		 	return Response::json([$results,$response]);
		 }else{
		 	return Response::json(
		 		[
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
		 						'message' => 'No update.',
		 						'status' => 'failed'
		 					]
		 				]
		 			]
		 		]
		 	);
		 }
	}
}
