<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<link rel="icon" href="../../favicon.ico">

	<title>Change Password</title>

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
		{!! Form::open(['url' => 'auth/change-password', 'class' => 'form-signin']) !!}
			<h2 class="form-signin-heading text-center">เปลี่ยนรหัสผ่าน</h2>
			{!! Html::ul($errors->all(), ['class' => 'alert alert-danger errors', 'style' => 'list-style-type: none;']) !!}
			<input type="hidden" name="email" value="{{ $data['email'] }}">
			<input type="hidden" name="key" value="{{ $data['key'] }}">

			<label for="password" class="sr-only">รหัสผ่านใหม่</label>
			<input type="password" id="password" name="password" class="form-control" placeholder="รหัสผ่านใหม่" required="" autofocus="" style="margin-bottom: -1px; border-bottom-right-radius: 0; border-bottom-left-radius: 0; border-top-left-radius: 4px; border-top-right-radius: 4px;" onkeypress="return checkPasswordCharacter();">
			<label for='password_confirmation' class="sr-only">ยืนยันรหัสผ่าน</label>
			<input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="ยืนยันรหัสผ่าน" required="" onkeypress="return checkPasswordCharacter();">
			<button class="btn btn-lg btn-primary btn-block" type="submit">บันทึก</button>
		{!! Form::close() !!}
	</div> <!-- /container -->

	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	{!! Html::script('js/ie10-viewport-bug-workaround.js') !!}
	<script>
		function checkPasswordCharacter() {
		    var specialChar = [33, 64, 35, 36, 37, 38];
		    if (event.keyCode >= 48 && event.keyCode <= 57 || 
		        event.keyCode >= 65 && event.keyCode <= 90 || 
		        event.keyCode >= 97 && event.keyCode <= 122 ||
		        specialChar.indexOf(event.keyCode) >= 0
		    ) {
		        return true;
		    } else {
		        return false;
		    }
		}
	</script>
</body>
</html>