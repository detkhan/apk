<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<link rel="icon" href="../../favicon.ico">

	<title>Login</title>

	<!-- Bootstrap core CSS -->
	{!! Html::style('bower_components/bootstrap/dist/css/bootstrap.min.css') !!}

	<!-- Custom styles for this template -->
	{!! Html::style('css/signin.css') !!}

	<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
	<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
	{!! Html::script('js/ie-emulation-modes-warning.js') !!}

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
	<div class="container">
		{!! Form::open(['url' => 'auth/login', 'class' => 'form-signin']) !!}
			<h2 class="form-signin-heading text-center">กรุณาเข้าสู่ระบบ</h2>
			{!! Html::ul($errors->all(), ['class' => 'alert alert-danger errors', 'style' => 'list-style-type: none;']) !!}
			<label for="email" class="sr-only">อีเมล</label>
			<input type="email" id="email" name="email" class="form-control" placeholder="อีเมล" required="" autofocus="">
			<label for='password' class="sr-only">รหัสผ่าน</label>
			<input type="password" id="password" name="password" class="form-control" placeholder="รหัสผ่าน" required="">
			<button class="btn btn-lg btn-primary btn-block" type="submit">เข้าสู่ระบบ</button>
		{!! Form::close() !!}
	</div> <!-- /container -->

	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	{!! Html::script('js/ie10-viewport-bug-workaround.js') !!}
</body>
</html>