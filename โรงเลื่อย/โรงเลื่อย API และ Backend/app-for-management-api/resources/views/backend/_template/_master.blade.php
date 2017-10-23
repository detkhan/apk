<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<meta name="author" content="">
	{{-- <link rel="icon" href="../../favicon.ico"> --}}

	<title>Backend - App for management</title>
	@include('backend._template.stylesheet')
	{!! Html::script('js/ie-emulation-modes-warning.js') !!}

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">
		<div class="header clearfix">
			<nav>
				<ul class="nav nav-pills pull-right">
					<li role="presentation" class="active">
						<div class="dropdown">
							<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								{{ \Auth::user()->firstname . ' ' . \Auth::user()->lastname }} <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
								<li><a href="#" data-toggle="modal" data-target="#modalChangePassword">Change Password <span class="glyphicon glyphicon-wrench pull-right"></span></a></li>
								<li><a href="{{ url('auth/logout') }}">Logout <span class="glyphicon glyphicon-log-out pull-right"></span></a></li>
							</ul>
						</div>
					</li>
				</ul>
			</nav>
			<h3 class="text-muted">App For Management</h3>
		</div>

		@yield('content')

		<div id="modalChangePassword" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">แก้ไขรหัสผ่าน</h4>
					</div>
					<div class="modal-body">
						{!! Form::open(['id' => 'formChangePassword', 'url' => '#']) !!}
						<div class="form-group">
							<label class="control-label" for="password_old">รหัสผ่านเดิม</label>
							<input type="password" class="form-control" id="password_old" name="password_old" placeholder="รหัสผ่านเดิม" onkeypress="return checkPasswordCharacter();">
						</div>
						<div class="form-group">
							<label class="control-label" for="password">รหัสผ่าน</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน" onkeypress="return checkPasswordCharacter();">
						</div>
						<div class="form-group">
							<label class="control-label" for="password_confirmation">ยืนยันรหัสผ่าน</label>
							<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="ยืนยันรหัสผ่าน" onkeypress="return checkPasswordCharacter();">
						</div>
						{!! Form::close() !!}
					</div>
					<div class="modal-footer">
						<button type="button" data-loading-text="กำลังส่งข้อมูล" class="btn btn-primary btn-submit">บันทึก</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<footer class="footer">
			<p>© 2017 REVOLUTION IT & MARKETING COMPANY LIMITED</p>
		</footer>

	</div> <!-- /container -->

	@include('backend._template.javascript')
</body>
</html>