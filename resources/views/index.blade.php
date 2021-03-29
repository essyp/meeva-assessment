<!DOCTYPE html>
<html lang="en">
<head>
	<title>Newsletter Subscription </title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>
        window.baseURL = "{{ url('/') }}";
    </script>
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{asset('home/images/icons/favicon.ico')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('home/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('home/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('home/fonts/iconic/css/material-design-iconic-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('home/vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('home/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('home/vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('home/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('home/vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('home/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('home/css/main.css')}}">
	<link rel="stylesheet" href="{{asset('home/css/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/waitMe.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{mix('css/app.css')}}">
<!--===============================================================================================-->
</head>
<body id="page">
<div class="limiter">
                <div style="background-image: url('{{asset('home/images/bg-01.jpg')}}');">

  <div id="app">
      <home/>
  </div>
				</div>
</div>

  <div id="dropDownSelect1"></div>
  <script src="{{mix('js/app.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('home/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('home/vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('home/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{asset('home/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('home/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('home/vendor/daterangepicker/moment.min.js')}}"></script>
	<script src="{{asset('home/vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('home/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('home/js/main.js')}}"></script>
	<script src="{{asset('home/js/toastr.min.js')}}"></script>
    <script src="{{asset('home/js/waitMe.min.js')}}"></script>
	<script>
		function open_loader(container) {
			$(container).waitMe({
				effect : 'bounce',
				text : '',
				bg : 'rgba(255,255,255,0.7)',
				color : '#000',
				maxSize : '',
				waitTime : '-1',
				textPos : 'vertical',
				fontSize : '',
				source : '',
				onClose : function() {}
			});
		}

		function close_loader(container) {
			$(container).waitMe("hide");
		}
	</script>

</body>
</html>