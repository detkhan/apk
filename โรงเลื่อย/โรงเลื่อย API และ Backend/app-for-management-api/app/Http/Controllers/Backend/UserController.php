<?php namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Http\Requests\UserRequest;
use App\Models\Sawmill;

class UserController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		if (\Auth::user()->type != 'Super_Admin') {
			\Redirect::to('auth/logout')->send();
		}
	}

	public function getIndex()
	{
		$branchs = Sawmill::all();
		return view('backend.user.index', compact('branchs'));
	}

	public function postDataList(Request $request)
	{
		$tbody = null;
		$users = Users::select('userId', 'email', 'firstname', 'lastname', 'branch', 'status')->where('type', $request->type)->orderBy('email')->get();
		foreach ($users as $user) {
			// set variable
			$status = '<span class="label label-danger">ปิดการใช้งาน</span>';
			if ($user['status'] == 'on') {
				$status = '<span class="label label-success">ปกติ</span>';
			}

			$txt_branch = [];
			if ($user['branch'] != '') {
				$branchs = explode(',', $user['branch']);
				foreach ($branchs as $branch) {
					$branch = $this->data_branch(trim($branch));
					$txt_branch[] = sprintf('<span class="label label-info">%s</span>', $branch->shortname);
				}
			}

			// html
			$tbody .= '<tr>';
			$tbody .= sprintf('<td>%s</td>', $user['email']);
			$tbody .= sprintf('<td>%s %s</td>', $user['firstname'], $user['lastname']);
			$tbody .= sprintf('<td class="text-center">%s</td>', implode(' ', $txt_branch));
			$tbody .= sprintf('<td class="text-center">%s</td>', $status);
			$tbody .= sprintf('<td class="text-center"><button type="button" class="btn btn-primary btn-sm" data-user="%s">แก้ไข</button></td>', $user['userId']);
			$tbody .= '</tr>';
		}

		return ['tbody' => $tbody];
	}

	public function postSave(Request $request)
	{
		$this->validate($request, ($request->userId == '') ? $this->createRule() : $this->updateRule($request), $this->validateMessage());
		$result = ['status' => false];
		if ($request->userId == '') {
			// create user
			$model = new Users;
			$data = array_filter($request->all());
			$data['password'] = sha1($data['password']);
			$data['branch'] = implode(',', $data['branch']);
			$data['created_by'] = \Auth::user()->email;
			$data['updated_by'] = \Auth::user()->email;
			if ($model->create($data)) {
				$result['status'] = true;
			}
		} elseif ($request->userId != '') {
			$user = Users::find($request->userId);
			$data = array_filter($request->all());
			if (isset($data['password'])) {
				$data['password'] = sha1($data['password']);
			}
			$data['branch'] = implode(',', $data['branch']);
			$data['updated_by'] = \Auth::user()->email;
			if ($user->update($data)) {
				$result['status'] = true;
			}
		}
		return $result;
	}

	public function postEdit(Request $request)
	{
		$user = Users::select('userId', 'email', 'firstname', 'lastname', 'type', 'branch', 'status')->where(['userId' => $request->id])->first();
		$user->branch = explode(',', $user->branch);
		return $user;
	}

	public function createRule()
	{
		return [
			'email' => 'required|email|unique:users',
			'password' => 'required|confirmed|min:6',
			'password_confirmation' => 'required',
			'firstname' => 'required',
			'lastname' => 'required',
			'status' => 'required',
			'type' => 'required',
			'branch' => 'required',
		];
	}

	public function updateRule($request)
	{
		$rules = [
			'firstname' => 'required',
			'lastname' => 'required',
			'status' => 'required',
			'type' => 'required',
			'branch' => 'required',
		];

		if ($request->password != '' || $request->password_confirmation != '') {
			$rules['password'] = 'required|confirmed|min:6';
			$rules['password_confirmation'] = 'required';
		}

		return $rules;
	}

	public function changePasswordRule()
	{
		return [
			'password_old' => 'required',
			'password' => 'required|confirmed|min:6',
			'password_confirmation' => 'required',
		];
	}

	public function validateMessage()
	{
		return [
			'email.required' => ' กรุณาป้อนอีเมล',
			'email.email' => ' รูปแบบอีเมลไม่ถูกต้อง',
			'password.required' => ' กรุณาป้อนรหัสผ่าน',
			'password.confirmed' => ' รหัสผ่านไม่ตรงกัน',
			'password.min' => ' รหัสผ่านต้องมากกว่า 6 ตัวอักษร',
			'password_confirmation.required' => ' กรุณายืนยันรหัสผ่าน',
			'firstname.required' => ' กรุณาป้อนชื่อ',
			'lastname.required' => ' กรุณาป้อนนามสกุล',
			'branch.required' => ' กรุณาป้อนสาขา',
			'password_old.required' => ' กรุณาป้อนรหัสผ่านเก่า',
		];
	}

	public function postChangePassword(Request $request)
	{
		$this->validate($request, $this->changePasswordRule(), $this->validateMessage());

		$result = ['status' => false];
		$user = Users::where(['email' => \Auth::user()->email, 'password' => sha1($request->password_old)])->first();
		if ($user) {
			$user->password = sha1($request->password);
			$user->save();
			$result['status'] = true;
		}
		
		return $result;
	}

}
