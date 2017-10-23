<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UtilityRequest extends Request {

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
		if(Request::path() == 'api/utility/upload-image'){
			$rule = [
				'imagePath' => 'required',
			];
		}else if(Request::path() == 'api/utility/check-transaction'){
			$rule = [
				'countNumber' => 'required',
			];
		}
		return $rule;
	}

}
