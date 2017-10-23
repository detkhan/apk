<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class PerformanceRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rule = [];
		if(Request::path() == 'api/performance/insert-performance'){
			$rule = [
				'sawId' => 'required',
				'volume_product' => 'required',
				'goals' => 'required',
				'performance_type' => 'required',
				'datetime' => 'required',
			];
		}else if(Request::path() == 'api/performance/intensive-performance'){
			$rule = [
				'email' => 'required',
				'dateMonth' => 'required',
				'years' => 'required',
			];
		}
		return $rule;
	}

}
