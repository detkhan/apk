<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PerformanceRequest;
use Illuminate\Http\Request;
use App\Models\Performance;
use App\Models\PerformanceGoals;
use App\Models\Sawmill;
use App\Models\Users;
use DB;
use Exception;
use Response;
use Schema;
use Input;
use Carbon\Carbon;
class PerformanceApiController extends Controller {

	public function postInsertPerformance (PerformanceRequest $request){
		$response = [];
		DB::beginTransaction();
				try {
					$model = new Performance;
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

	public function postIntensivePerformance(PerformanceRequest $request){
		$users = Users::where('email','=',$request->email)->first();
		if(isset($users)){
			// get goal
			$goals = [];
			$branchs = explode(",",$users->branch);
			foreach ($branchs as $key => $branch) {
				$saw = Sawmill::where('shortname','=',$branch)
					->select('sawId','shortname','fullname')->first();

				$result = PerformanceGoals::where('sawId','=',$saw->sawId)
					->whereMonth('datetime','=',date($request->dateMonth))
					->whereYear('datetime','=',date($request->years))
					->select('volume_product','ab','ab_c')
					->get();
				if (isset($result) != null || !empty($result)){
					foreach ($result as $value) {
						$goals[$saw->shortname] = 
							[
								'Volume_Product/Goals' => $value->volume_product,
								'AB/Goals' => $value->ab,
								'AB+C/Goals' => $value->ab_c
							];
						}
					}
				}
			//get performance				 
			$getPerformanceTypes = Performance::select('performance_type')
				->groupBy('performance_type')->get();
				foreach ($getPerformanceTypes as $key => $getPerformanceType) {
					$response[$getPerformanceType->performance_type] = [];
					foreach ($branchs as $key => $branch) {
						$sawmill = Sawmill::where('shortname','=',$branch)
							->select('sawId','shortname','fullname')->first();
						$data = [];
						$value = Performance::where('sawId','=',$sawmill->sawId)
							->whereMonth('datetime','=',date($request->dateMonth))
							->whereYear('datetime','=',date($request->years))
							->where('performance_type','=',$getPerformanceType->performance_type)
							->select(
							'performance_type',
							DB::raw('SUM(`volume_product`) as volume_product')
							)->first();

					if(isset($value->volume_product) != null){
						$data = 
						[
							'fullname' => $sawmill->fullname,
							'name' => $sawmill->shortname,
							'volume_product' => $value->volume_product,
							'goals' => (string)$goals[$sawmill->shortname][$value->performance_type],
						];
						$response[$value->performance_type][] = $data;
					}
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
			return Response::json([$results,[$response]]);
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

	public function getIntensivePerformance(){
		$currentMonth = Carbon::now()->month;
		$currentYear = Carbon::now()->year;
		$response = [];
		$maxData = [];
		$sawmills = Sawmill::all();
		$getPerformanceTypes = Performance::select('performance_type')
		->groupBy('performance_type')->get();
		foreach ($getPerformanceTypes as $key => $getPerformanceType) {
			$response[$getPerformanceType->performance_type] = [];
			foreach ($sawmills as $key => $Sawmill) {
				$data = [];
				$value = Performance::where('sawId','=',$Sawmill->sawId)
				->whereMonth('datetime','=',date($currentMonth))
				->whereYear('datetime','=',date($currentYear))
				->where('performance_type','=',$getPerformanceType->performance_type)
				->select(
				'performance_type',
				DB::raw('SUM(`volume_product`) as volume_product'),
				DB::raw('SUM(`goals`) as goals')
				)->first();
				if(isset($value->volume_product) != null && isset($value->goals) != null){
						$data = 
						[
							'fullname' => $Sawmill->fullname,
							'name' => $Sawmill->shortname,
							'volume_product' => $value->volume_product,
							'goals' => $value->goals,
						];
					$response[$value->performance_type][] = $data;
				}
			}
		}

		foreach ($response as $key => $type) {
			$volume = max($this->array_col($type, 'volume_product'));
			$goals = max($this->array_col($type, 'goals'));
			$maxData[$key] = max($volume, $goals);
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
			return Response::json([$results,[$response],[$maxData]]);
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

	public function array_col(array $a, $x)
	{
	  return array_map(function($a) use ($x) { return $a[$x]; }, $a);
	}
}
