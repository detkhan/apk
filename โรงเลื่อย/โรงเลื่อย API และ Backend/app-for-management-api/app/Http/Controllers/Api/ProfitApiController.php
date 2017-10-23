<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfitRequest;
use Illuminate\Http\Request;
use App\Models\Profit;
use App\Models\Sawmill;
use App\Models\Users;
use DB;
use Exception;
use Response;
use Schema;
use Input;
use Carbon\Carbon;
class ProfitApiController extends Controller {
	
	public function postInsertProfit(ProfitRequest $request){
		$response = [];
		DB::beginTransaction();
				try {
					$model = new Profit;
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

	public function postCheckProfitloss(ProfitRequest $request){
		$response = [];
		$users = Users::where('email','=',$request->email)->first();
		if(isset($users)){
			$branchs = explode(",",$users->branch);
			foreach ($branchs as $key => $branch) {
				$sawmill = Sawmill::where('shortname','=',$branch)
						->select('sawId','shortname','fullname')->first();

				$getProfitandLosses = Profit::where('sawId','=',$sawmill->sawId)
					->whereMonth('datetime','=',date($request->dateMonth))
					->whereYear('datetime','=',date($request->years))
					->select('sawId')
					->groupBy('sawId')->get();
				foreach ($getProfitandLosses as $key => $getProfitandLoss){
				$response[] =
					[
					'sawId' => $sawmill->sawId,
					'shortname' => $sawmill->shortname,
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
							'status' => 'true',
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

	public function postProfitLoss(ProfitRequest $request){
		$response = [];
		$getProfitandLosses = Profit::where('sawId','=',$request->sawId)
		->whereMonth('datetime','=',date($request->dateMonth))
		->whereYear('datetime','=',date($request->years))
		->select('datetime','incoming_total','outcoming_total','gross_profit_total','costs_total','profit_loss_total')
		->orderBy('datetime')->get();
		foreach ($getProfitandLosses as $key => $getProfitandLoss){
			$response[] = [
				'datetime' => explode("-", explode(" ", $getProfitandLoss->datetime)[0])[2],
				'incoming' => $getProfitandLoss->incoming_total,
				'outcoming' => $getProfitandLoss->outcoming_total,
				'grossProfit' => $getProfitandLoss->gross_profit_total,
				'costs' => $getProfitandLoss->costs_total,
				'netProfit' => $getProfitandLoss->profit_loss_total
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
							'status' => 'true',
							'sawId' => $request->sawId
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
	public function postIncomingOutcoming(ProfitRequest $request){
		$currentMonth = Carbon::now()->month;
		$currentYear = Carbon::now()->year;
		$response = [];
		$users = Users::where('email','=',$request->email)->first();
		if(isset($users)){
			$branchs = explode(",",$users->branch);
			foreach ($branchs as $key => $branch) {
				$sawmill = Sawmill::where('shortname','=',$branch)
						->select('sawId','shortname','fullname')->first();

				$value = Profit::where('sawId','=',$sawmill->sawId)
				->whereMonth('datetime','=',date($currentMonth))
				->whereYear('datetime','=',date($currentYear))
				->select(
					DB::raw('SUM(`profit_loss_total`) as profit_loss_total')
					)
				->first();
				if(isset($value->profit_loss_total) != null){
					$response[] = [
						'sawId' => $sawmill->sawId,
						'fullname' => $sawmill->fullname,
						'name' => $sawmill->shortname,
						'profit_total' => $value->profit_loss_total,
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
							'message' => 'No results.',
							'status' => 'failed'
						]
					]
				]);
		}
	}

	public function getIncomingOutcoming (){
		$currentMonth = Carbon::now()->month;
		$currentYear = Carbon::now()->year;
		$response = [];
		$sawmills = Sawmill::all();
		foreach ($sawmills as $key => $sawmill) {
			$value = Profit::where('sawId','=',$sawmill->sawId)
				->whereMonth('datetime','=',date($currentMonth))
				->whereYear('datetime','=',date($currentYear))
				->select(
					DB::raw('SUM(`profit_loss_total`) as profit_loss_total')
					)
				->first();
			if(isset($value->profit_loss_total) != null){
				$response[] = [
					'sawId' => $sawmill->sawId,
					'fullname' => $sawmill->fullname,
					'name' => $sawmill->shortname,
					'profit_total' => $value->profit_loss_total,
				];
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
							'message' => 'No results.',
							'status' => 'failed'
						]
					]
				]);
		}
	}
}
