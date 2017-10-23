<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class TransactionRequest extends Request {

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
		if(Request::path() == 'api/transaction/realtime-transaction'){
			$rule = [
				'sawId' => 'required',
			];
		}else if(Request::path() == 'api/transaction/insert-transaction'){
			$rule = [
				'sawId' => 'required',
				'productId' => 'required',
				'truck_register_number' => 'required',
				'weight_no' => 'required',
				'customer_name_in' => 'required',
				'datetime_in' => 'required',
				'weight_in'=> 'required',
				'customer_name_out' => 'required',
				'datetime_out' => 'required',
				'weight_out' => 'required',
				'weight_net' => 'required',
				'weight_tare' => 'required',
				'weight_total' => 'required',
				'product_price_unit' => 'required',
				'price_total' => 'required',
				'type_name' => 'required',
				'pic_path' => 'required',
			];
		}else if(Request::path() == 'api/transaction/transaction-detail'){
			$rule = [
				'tranId' => 'required',
			];
		}else if(Request::path() == 'api/transaction/realtime-report'){
			$rule = [
				'email' => 'required',
			];
		}else if(Request::path() == 'api/transaction/insert-transactiontemp'){
			$rule = [
				'sawId' => 'required',
				'productId' => 'required',
				'truck_register_number' => 'required',
				'weight_no' => 'required',
				'customer_name_in' => 'required',
				'datetime_in' => 'required',
				'weight_in'=> 'required',
				'customer_name_out' => 'required',
				'datetime_out' => 'required',
				'weight_out' => 'required',
				'weight_net' => 'required',
				'weight_tare' => 'required',
				'weight_total' => 'required',
				'product_price_unit' => 'required',
				'price_total' => 'required',
				'type_name' => 'required',
				'pic_path' => 'required',
			];
		}
		return $rule;
	}

}
