<?php namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Http\Requests\UserRequest;
// use Illuminate\Contracts\Auth\Guard;


class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	// use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
	}

	public function getLogin()
	{
		return view('auth.login');
	}

	public function postLogin(UserRequest $request)
	{
		$user = Users::where(['email' => $request->email, 'password' => sha1($request->password), 'status' => 'on'])->whereIn('type', ['Admin', 'Super_Admin'])->first();
		if ($user && $this->loginUser($user))
		{
			$user->forget_token = '';
			$user->save();
			
			return redirect()->intended('/backend');
		}

		return redirect($this->loginPath())
					->withInput($request->only('email'))
					->withErrors([
						'email' => $this->getFailedLoginMessage(),
					]);
	}

	public function loginUser($user)
	{
		\Auth::login($user, true);
		return true;
	}

	public function getLogout()
	{
		\Auth::logout();
		return redirect($this->loginPath());
	}

	protected function getFailedLoginMessage()
	{
		return 'ไม่สามารถเข้าสู่ระบบได้ กรุณาตรวจสอบอีเมลและรหัสผ่านหรือติดต่อผู้ดูแลระบบ';
	}

	protected function getFailedChangePasswordMessage()
	{
		return 'ไม่สามารถแก้ไขรหัสผ่านได้ กรุณาติดต่อผู้ดูแลระบบ';
	}

	public function loginPath()
	{
		return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
	}

	public function getChangePassword($email, $key)
	{
		$data = ['email' => $email, 'key' => $key];
		return view('auth.change_password', compact('data'));
	}

	public function postChangePassword(UserRequest $request)
	{
		$user = Users::where(['email' => $request->email, 'forget_token' => $request->key])->first();
		if ($user) {
			$user->password = sha1($request->password);
			$user->forget_token = '';
			$user->save();
			return redirect($this->loginPath());
		}

		return redirect($this->loginPath())
					->withErrors([
						'email' => $this->getFailedChangePasswordMessage(),
					]);
	}

}
