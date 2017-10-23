<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class DialyReportRequest extends Request {

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
		if(Request::path() == 'api/dialyreport/insert-woodpieces'){
			$rule = [
				'sawId' => 'required',
				'wood_pieces_incoming' => 'required',
				'wood_sale' => 'required',
				'total' => 'required',
				'losts' => 'required',
				'datetime' => 'required',
			];
		}else if(Request::path() == 'api/dialyreport/wood_pieces'){
			$rule = [
				'sawId' => 'required',
				'dateMonth' => 'required',
				'years' => 'required',
			];
		}else if(Request::path() == 'api/dialyreport/fire_wood'){
			$rule = [
				'sawId' => 'required',
				'dateMonth' => 'required',
				'years' => 'required',
			];
		}else if(Request::path() == 'api/dialyreport/insert-firewood'){
			$rule = [
				'sawId' => 'required',
				'fire_wood_incoming' => 'required',
				'fire_wood_sale' => 'required',
				'datetime' => 'required',
			];
		}else if(Request::path() == 'api/dialyreport/insert-weightoutcoming'){
			$rule = [
				'sawId' => 'required',
				'wood_grades_weight' => 'required',
				'slab_weight' => 'required',
				'sawdust_weight' => 'required',
				'datetime' => 'required',
			];
		}else if(Request::path() == 'api/dialyreport/weight-outcoming'){
			$rule = [
				'sawId' => 'required',
				'dateMonth' => 'required',
				'years' => 'required',
			];
		}else if(Request::path() == 'api/dialyreport/check-woodpieces'){
			$rule = [
				'email' => 'required',
				'dateMonth' => 'required',
				'years' => 'required',
			];
		}else if(Request::path() == 'api/dialyreport/check-firewood'){
			$rule = [
				'email' => 'required',
				'dateMonth' => 'required',
				'years' => 'required',
			];
		}else if(Request::path() == 'api/dialyreport/check-weightoutcoming'){
			$rule = [
				'email' => 'required',
				'dateMonth' => 'required',
				'years' => 'required',
			];
		}
		return $rule;
	}

}
