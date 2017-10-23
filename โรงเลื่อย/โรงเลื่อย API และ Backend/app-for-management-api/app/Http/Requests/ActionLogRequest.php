<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ActionLogRequest extends Request {

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
		return [
			'email' => 'required',
			'event' => 'required',
			'function_name' => 'required',
			'action' => 'required',
			'value' => 'required',
		];
	}

}
