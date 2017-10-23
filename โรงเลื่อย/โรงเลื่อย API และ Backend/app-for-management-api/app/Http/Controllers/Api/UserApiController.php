<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\Users;
use App\Models\ActionLog;
use Illuminate\Http\Request;
use App\Classes\RevomailClass;
use App\Classes\ActionLogClass;
use DB;
use Exception;
use Response;
use Schema;
use Input;

class UserApiController extends Controller {
	/**
	 * register to use application
	 * use user table in mysql
	 * @return message and status code
	 */
	public function postUserRegister(UserRequest $request)
	{
		$user = Users::where('email','=',$request->email)->first();
		$actionlogClass = new ActionLogClass();
		$response = [];
		if(Schema::hasTable('users')){
			if($user){
				return Response::json(
					[
					'results' => [
						'message' => 'Email is ready to use.',
						'code' => 200 , 
						'msg' => 'OK'
						]
					]);
			}else{
				DB::beginTransaction();
				try {
					$model = new Users;
					$data = array_filter($request->all());
					$data['password'] = sha1($data['password']);
					$data['created_by'] = $request->email;
					$data['updated_by'] = $request->email;
					$model->create($data);
					DB::commit();
					$response = [
								'results' => 
									[
										'message' => 'Register success' , 
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
				$actionData = [
								'email' => $request->email,
								'event' => 'insert',
								'function_name' => 'postUserRegister',
								'action' => 'Register to use application.',
								'value' => $request->email,
							];
				$actionlogClass->postActionLogInApplication($actionData);
				return Response::json($response);
			}
		}else{
			return Response::json(
				[
				'results' => [
					'message' => 'Not found database' , 
					'code' => 401 ,
					 'msg' => 'Unauthorized'
					 ]
				 ]);
		}
	}
	/**
	 * check email and password when user login in backend or application
	 * use user table in mysql
	 * @return message and status code and data
	 */
	public function postUserLogin(UserRequest $request)
	{
		$user = Users::where('email','=',Input::get('email'))->first();
		$password = sha1($request->password);
		$actionlogClass = new ActionLogClass();
		$response = [];
		if(isset($user->email)){
			if(isset($user)){
				//found email in database
				$data = Users::where('email', $user->email)->where('password', $password)->first();
				if(isset($data)){
					//found password in database
					if($data->status != 'off'){
						//check status from database is on or off
						/*
						* off = permission denied
						* on = can use application.
						*/
						$response = [
										[
										 	'results' => 
										 		[
										 			'message' => 'Login success.',
										 			'code' => 200 , 
										 			'msg' => 'OK'
										 		],
							 				'data' => 
							 					[
								 					'email' => $data->email , 
									 				'firstname' => $data->firstname , 
									 				'lastname' => $data->lastname,
									 				'type' => $data->type,
									 				'branch' => explode(",", $data->branch),
									 				'status' => 'true'
								 				]
									 	]
									];
					}else{
						$response = [
										[
											'results' => 
											[
												'message' => 'Response success.', 
												'code' => 200 , 
												'msg' => 'OK'
											],
											'data' => 
											[
												'message' => 'Please contact admin to approve your account.',
												'status' => 'failed'
											]
										]
									];
					}
					$actionData = [
									'email' => $user->email,
									'event' => 'login',
									'function_name' => 'postUserLogin',
									'action' => 'login to use application.',
									'value' => $user->email,
								];
					$actionlogClass->postActionLogInApplication($actionData);
					return Response::json($response);
				}else{
					//not found password in database
					return Response::json(
					[
						[
						'results' => 
							[
								'message' => 'Response success.' ,
								'code' => 200 , 
								'msg' => 'OK'
							],
						'data' =>
							[
								'status' => 'failed',
								'message' => 'Not found email or password.'
							]
						]	
					]);
				}
			}else{
				//not found email in database
				return Response::json(
					[
						[
						'results' => 
							[
								'message' => 'Response success.' ,
								'code' => 200 , 
								'msg' => 'OK'
							],
						'data' =>
							[
								'status' => 'failed',
								'message' => 'Not found email or password.'
							]
					]
				]);
			}
		}else{
			//don't have parameter name email in database
			return Response::json(
				[
					[
					'results' => 
						[
							'message' => 'Response success.' ,
							'code' => 200 , 
							'msg' => 'OK'
						],
					'data' =>
						[
							'status' => 'failed',
							'message' => 'Not found email or password.'
						]
				]
			]);
		}
	}

	/**
	 * change password in backend or application
	 *
	 * @return message
	 */
	public function postChangePassword(PasswordRequest $request)
	{
	 	 $old_password = sha1($request->old_password);
	 	 $user = Users::where('email',$request->email)->where('password',$old_password)->first();
	 	 $response = [];
	 	 $actionlogClass = new ActionLogClass();
	 	 if(isset($user->email)){
	 	 	DB::beginTransaction();
				try {
					$data = $request->all();
					$data['password'] = sha1($data['password']);
					$data['updated_by'] = $user->email;
					$user->update($data);
					DB::commit();
					$response  = [
									'results' => 
									[
										'message' => 'Change password success.',
										'code' => 200 , 
										'msg' => 'OK'
									]
								];
				} catch (Exception $e) {
					$response = [
									'results' => 
									[
										'message' => 'Not request data in database',
										'code' => 400 ,
										'msg' => 'Bad Request'
									]
								];
				}
			$actionData = [
				'email' => $user->email,
				'event' => 'update',
				'function_name' => 'postChangePassword',
				'action' => 'change password in application.',
				'value' => $user->email,
			];
			$actionlogClass->postActionLogInApplication($actionData);
			return Response::json($response);
	 	 }else{
	 	 	return Response::json(
	 	 		[
	 	 			'results' => [
			 	 		'message' => 'Old password is incorrect.',
			 	 		'code' => 200,
			 	 		'msg' => 'OK'
	 	 			]
	 	 		]);
	 	 }
	}

	/**
	 * forget password in backend or application
	 *
	 * @return message
	 */
	public function postForgetPassword(UserRequest $request)
	{
		$user = Users::where('email','=',$request->email)->first();
	    $actionlogClass = new ActionLogClass();
		if($user){
			if(isset($user->email)){
				$key = round(microtime(true));
				$url = sprintf(url('auth/change-password/%s/%s'), $user->email, $key);

				$revomailClass = new RevomailClass();
				$revomailClass->subject('Change Password');
				$revomailClass->content(sprintf('<a href="%s">Click here to change password</a>', $url)); //add link change password in content
				$revomailClass->addAddress($user->email);
			    $result = $revomailClass->sendMail();
			    $data = Users::where('email', $user->email)->first();
			    $response = [];
				if(isset($result) == true){
					$user->forget_token = $key;
					$user->save();
					if($data->status != 'off'){
						$response = [
										[
											'results' => 
											[
												'message' => 'Response success.',
												'code' => 200,
												'msg' => 'OK'
											],
											'data' => 
											[
												'status' => 'true',
												'message' => 'Send email complete.'
											]
										]
									];
					}else{
						$response = [
										[
											'results' =>
											[
												'message' => 'Response success.',
												'code' => 200,
												'msg' => 'OK'
											],
											'data' => 
											[
												'status' => 'failed',
												'message' => 'Please contact admin to approve your account.'
											]
										]
									];
					}
				}else{
					$response = [
									[
										'results' => 
										[
											'message' => 'Bad Request.',
											'code' => 400,
											'msg' => 'Bad Request'
										],
										'data' => 
										[
											'status' => 'failed',
											'message' => 'Email not ready to use'
										]
									]
								];
				}
				$actionData = [
					'email' => $user->email,
					'event' => 'search',
					'function_name' => 'postForgetPassword',
					'action' => 'send to forget password',
					'value' => $user->email,
				];
				$actionlogClass->postActionLogInApplication($actionData);
				return Response::json($response);
			}else{
				return Response::json(
					[
						[
							'results' => 
							[
								'message' => 'Unauthorized.' , 
								'code' => 401 ,
					 			'msg' => 'Unauthorized'
					 		],
					 		'data' =>
					 		[
					 			'status' => 'failed',
					 			'message' => 'Not found table(row) in database.'
							]
						 ]
					 ]);
				}
		}else{
			return Response::json(
				[
					[
						'results' => 
						[
							'message' => 'Response success.' ,
							'code' => 200 ,
							'msg' => 'OK'
			 			],
			 			'data' =>
			 			[
			 				'status' => 'failed',
			 				'message' => 'Not have any email in system.'
			 			]
			 		]
				]);
			}
	}
}
