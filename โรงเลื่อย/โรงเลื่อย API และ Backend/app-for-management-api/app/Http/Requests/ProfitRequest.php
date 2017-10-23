<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProfitRequest extends Request {

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
		if(Request::path() == 'api/profit/insert-profit'){
			$rule = [
				'sawId' => 'required',
				'incoming_total' => 'required',
				'outcoming_total' => 'required',
				'gross_profit_total' => 'required',
				'costs_total' => 'required',
				'profit_loss_total' => 'required',
				'datetime' => 'required',
			];
		}else if(Request::path() == 'api/profit/profit-loss'){
			$rule = [
				'sawId' => 'required',
				'dateMonth' => 'required',
				'years' => 'required',
			];
		}else if (Request::path() == 'api/profit/incoming-outcoming'){
			$rule = [
				'email' => 'required',
			];
		}else if (Request::path() == 'api/profit/check-profitloss'){
			$rule = [
				'email' => 'required',
				'dateMonth' => 'required',
				'years' => 'required',
			];
		}
		return $rule;
	}

}
