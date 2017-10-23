<?php namespace App\Http\Requests;


use App\Http\Requests\Request;

class UserRequest extends Request {

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
		if (Request::path() == 'api/user/user-login') {
			$rule = [
				'email' => 'required',
			    'password' => 'required',
			];

		} else if (Request::path() == 'api/user/user-register') {
			$rule = [
				'email' => 'required|email|unique:users',
				'password' => 'required|confirmed|min:6',
				'password_confirmation' => 'required',
				'firstname' => 'required',
				'lastname' => 'required',
				'status' => 'required',
				'type' => 'required',
				'branch' => 'required',
			];
		} else if (Request::path() == 'api/user/forget-password'){
			$rule = [
				'email' => 'required',
			];
		} else if (Request::path() == 'auth/login') {
			$rule = [
				'email' => 'required|email', 
				'password' => 'required',
			];
		} else if (Request::path() == 'auth/change-password') {
			$rule = [
				'password' => 'required|confirmed|min:6',
				'password_confirmation' => 'required',
			];
		}

		return $rule;
	}

}
