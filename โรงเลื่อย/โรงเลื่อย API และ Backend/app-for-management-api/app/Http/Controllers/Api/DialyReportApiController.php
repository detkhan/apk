<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\DialyReportRequest;
use Illuminate\Http\Request;
use App\Models\WoodPieces;
use App\Models\FireWood;
use App\Models\Users;
use App\Models\Sawmill;
use App\Models\WeightOutcoming;
use App\Models\Transaction;
use DB;
use Exception;
use Response;
use Schema;
use Input;
use Carbon\Carbon;

class DialyReportApiController extends Controller {
	/**
	 * insert data into database
	 * use wood_pieces table in mysql
	 * @return message and status code
	 */
	public function postInsertWoodpieces (DialyReportRequest $request){
		$response = [];
		DB::beginTransaction();
			try {
				$model = new WoodPieces;
				$data = array_filter($request->all());
				$data['created_by'] = $request->sawId;
				$data['updated_by'] = $request->sawId;
				$model->create($data);
				DB::commit();
				$response = [
								'results' => 
									[
										'message' => 'Insert data success.' , 
										'code' => 200 , 
										'msg' => 'OK'
									]
								];
			} catch (Exception $e){
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
	 * 	post email and dateMonth and years to check it's have any data in database
	 *	use wood_pieces table in mysql
	 * @return data , message and status code
	 */
	public function postCheckWoodpieces(DialyReportRequest $request){
		$response = [];
		$users = Users::where('email','=',$request->email)->first();
		if(isset($users)){
			$branchs = explode(",",$users->branch);
			foreach ($branchs as $key => $branch) {
				$sawmill = Sawmill::where('shortname','=',$branch)
						->select('sawId','shortname','fullname')->first();

				$CheckWoodPieces = WoodPieces::where('sawId','=',$sawmill->sawId)
					->whereMonth('datetime','=',date($request->dateMonth))
					->whereYear('datetime','=',date($request->years))
					->select(
						'sawId'
							)
						->groupBy('sawId')->get();
				foreach ($CheckWoodPieces as $key => $CheckWoodPiece) {
					$response[] = 
						[
							'sawId' => $sawmill->sawId,
							'name' => $sawmill->shortname
						];
				}
			}
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
		 					'message' => 'No results',
		 					'status' => 'failed'
		 				]
		 			]
		 		]);
		}
	}

	/**
	 * 	post sawId and dateMonth (string('1','2','3','4','5')) to get data from database
	 *	use wood_pieces table in mysql
	 * @return data , message and status code
	 */
	public function postWoodPieces(DialyReportRequest $request){
		$response = [];
		$getWoodPieces = WoodPieces::where('sawId','=',$request->sawId)
		->whereMonth('datetime','=',date($request->dateMonth))
		->whereYear('datetime','=',date($request->years))
		->select(
			'datetime',
			DB::raw('SUM(`wood_pieces_incoming`) as wood_pieces_incoming'),
			DB::raw('SUM(`timber_saw`) as timber_saw'),
			DB::raw('SUM(`wood_sale`) as wood_sale'),
			DB::raw('SUM(`total`) as wood_total'),
			DB::raw('SUM(`losts`) as wood_losts')
			)
		->groupBy('datetime')->get();

		foreach ($getWoodPieces as $key => $getWoodPiece) {
			$response[] = [
				'datetime' => explode("-", explode(" ", $getWoodPiece->datetime)[0])[2],
				'wood_pieces_incoming' => $getWoodPiece->wood_pieces_incoming,
				'timber_saw' => $getWoodPiece->timber_saw,
				'wood_sale' =>	$getWoodPiece->wood_sale,
				'wood_total' => $getWoodPiece->wood_total,
				'wood_losts' => $getWoodPiece->wood_losts
			];
		}
		/*$response[] = [
				'datetime' => '99',
				'wood_pieces_incoming' => 98765,
				'timber_saw' => 50,
				'wood_sale' =>	4321,
				'wood_total' => 1,
				'wood_losts' => 2
		];*/
		
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
		 					'message' => 'No results',
		 					'status' => 'failed'
		 				]
		 			]
		 		]);
		}
	}
	/**
	 * insert data into database
	 * use fire_wood table in mysql
	 * @return message and status code
	 */
	public function postInsertFirewood(DialyReportRequest $request){
		$response = [];
		DB::beginTransaction();
			try {
				$model = new FireWood;
				$data = array_filter($request->all());
				$data['created_by'] = $request->sawId;
				$data['updated_by'] = $request->sawId;
				$model->create($data);
				DB::commit();
				$response = [
								'results' => 
									[
										'message' => 'Insert data success.' , 
										'code' => 200 , 
										'msg' => 'OK'
									]
								];
			} catch (Exception $e){
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
	 * 	post email and dateMonth and years to check it's have any data in database
	 *	use fire_wood table in mysql
	 * @return data , message and status code
	 */
	public function postCheckFirewood(DialyReportRequest $request){
		$response = [];
		$users = Users::where('email','=',$request->email)->first();
		if(isset($users)){
			$branchs = explode(",",$users->branch);
			foreach ($branchs as $key => $branch) {
				$sawmill = Sawmill::where('shortname','=',$branch)
						->select('sawId','shortname','fullname')->first();

				$getFireWoods = FireWood::where('sawId','=',$sawmill->sawId)
					->whereMonth('datetime','=',date($request->dateMonth))
					->whereYear('datetime','=',date($request->years))
					->select(
						'sawId'
							)
						->groupBy('sawId')->get();
				foreach ($getFireWoods as $key => $getFireWood) {
					$response[] = 
						[
							'sawId' => $sawmill->sawId,
							'name' => $sawmill->shortname
						];
				}		
			}
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
		 					'message' => 'No results',
		 					'status' => 'failed'
		 				]
		 			]
		 		]);
		}
	}
	/**
	 * post sawId and dateMonth (string('1','2','3','4','5')) to get data from database
	 * use fire_wood table in mysql
	 * @return data ,message and status code
	 */
	public function postFireWood(DialyReportRequest $request) {
		$response = [];
		$getFireWoods = FireWood::where('sawId','=',$request->sawId)
		->whereMonth('datetime','=',date($request->dateMonth))
		->whereYear('datetime','=',date($request->years))
		->select(
			'datetime',
			DB::raw('SUM(`fire_wood_incoming`) as fire_wood_incoming'),
			DB::raw('SUM(`fire_wood_sale`) as fire_wood_sale'),
			DB::raw('SUM(`firewood_total`) as firewood_total'),
			DB::raw('SUM(`firewood_losts`) as firewood_losts')
			)
		->groupBy('datetime')->get();
		foreach ($getFireWoods as $key => $getFireWood) {
			$response[] = [
				'datetime' => explode("-", explode(" ", $getFireWood->datetime)[0])[2],
				'fire_wood_incoming' => $getFireWood->fire_wood_incoming,
				'fire_wood_sale' => $getFireWood->fire_wood_sale,
				'firewood_total' => $getFireWood->firewood_total,
				'firewood_losts' => $getFireWood->firewood_losts
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
		 					'message' => 'No results',
		 					'status' => 'failed'
		 				]
		 			]
		 		]);
		}
	}
	/**
	 * insert data in database
	 * use weight_outcoming table in mysql
	 * @return message and status code
	 */
	public function postInsertWeightoutcoming (DialyReportRequest $request){
		$response = [];
		DB::beginTransaction();
			try {
				$model = new WeightOutcoming;
				$data = array_filter($request->all());
				$data['created_by'] = $request->sawId;
				$data['updated_by'] = $request->sawId;
				$model->create($data);
				DB::commit();
				$response = [
								'results' => 
									[
										'message' => 'Insert data success.' , 
										'code' => 200 , 
										'msg' => 'OK'
									]
								];
			} catch (Exception $e){
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
	 * 	post email and dateMonth and years to check it's have any data in database
	 *	use weight_outcoming table in mysql
	 * @return data , message and status code
	 */
	public function postCheckWeightoutcoming(DialyReportRequest $request){
		$response = [];
		$users = Users::where('email','=',$request->email)->first();
		if(isset($users)){
			$branchs = explode(",",$users->branch);
			foreach ($branchs as $key => $branch) {
				$sawmill = Sawmill::where('shortname','=',$branch)
						->select('sawId','shortname','fullname')->first();

				$getWeightOutcomings = WeightOutcoming::where('sawId','=',$sawmill->sawId)
					->whereMonth('datetime','=',date($request->dateMonth))
					->whereYear('datetime','=',date($request->years))
					->select(
						'sawId'
							)
					->groupBy('sawId')->get();
				foreach ($getWeightOutcomings as $key => $getWeightOutcoming) {
				$response[] = 
					[
						'sawId' => $sawmill->sawId,
						'name' => $sawmill->shortname
					];
				}	
			}
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
		 					'message' => 'No results',
		 					'status' => 'failed'
		 				]
		 			]
		 		]);
		}
	}
	/**
	 * post sawId and dateMonth (string('1','2','3','4','5')) to get data from database
	 * use weight_outcoming table in mysql
	 * @return data , message and status code
	 */
	public function postWeightOutcoming (DialyReportRequest $request){
		$response = [];
		$getWeightOutcomings = WeightOutcoming::where('sawId','=',$request->sawId)
		->whereMonth('datetime','=',date($request->dateMonth))
		->whereYear('datetime','=',date($request->years))
		->select(
			'datetime',
			DB::raw('SUM(`wood_grades_weight`) as wood_grades_weight'),
			DB::raw('SUM(`slab_weight`) as slab_weight'),
			DB::raw('SUM(`sawdust_weight`) as sawdust_weight')
			)
		->groupBy('datetime')->get();
		foreach ($getWeightOutcomings as $key => $getWeightOutcoming) {
			$response[] = [
				'datetime' => explode("-", explode(" ", $getWeightOutcoming->datetime)[0])[2],
				'wood_grades_weight' => $getWeightOutcoming->wood_grades_weight,
				'slab_weight' => $getWeightOutcoming->slab_weight,
				'sawdust_weight' => $getWeightOutcoming->sawdust_weight
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
		 					'message' => 'No results',
		 					'status' => 'failed'
		 				]
		 			]
		 		]);
		}
	}
}
