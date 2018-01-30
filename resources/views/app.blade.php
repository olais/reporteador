<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="base_url" content="{{ URL::to('/') }}">
	<title>Reporteador</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="{{ asset('/js/login.js') }}"></script>
	<script src="{{ asset('/js/jquery.hotkeys.js') }}"></script>
	
	
	<script src="{{ asset('/js/jquery.blockUI.js') }}"></script>
	<script src="{{ asset('/js/bootstrap-select.js') }}"></script>
	<script src="{{ asset('/sweetalert/js/sweetalert.min.js') }}"></script>

	
	<script src="{{ asset('/js/jquery.dataTables.js') }}"></script>
	<script src="{{ asset('/js/dataTables.buttons.min.js') }}"></script>
	<script src="{{ asset('/js/jszip.min.js') }}"></script>
	<script src="{{ asset('/js/pdfmake.min.js') }}"></script>
	<script src="{{ asset('/js/vfs_fonts.js') }}"></script>
	<script src="{{ asset('/js/buttons.html5.min.js') }}"></script>
	<script src="{{ asset('/js/buttons.print.min.js') }}"></script>
	
	
   
	

	<link href="{{ asset('/css/bootstrap-select.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/jquery.dataTables.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/sweetalert/css/sweetalert.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/buttons.dataTables.min.css') }}" rel='stylesheet' type='text/css'>

	<script type="text/javascript">

	  var path=$('meta[name="base_url"]').attr('content'); //para el base url
	  var token="{{ csrf_token() }}";

	</script>
	<style type="text/css">
	.fondo{
	    background-color:#000;//#cedff5;
	    background-repeat: no-repeat;
	    background-size: cover;
	    width: 100%;
	    height: 30px;
	    color: red !important;
	   }

	</style>
	
</head>
<body>
      
	<nav class="navbar navbar-default fondo">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div class="collapse navbar-collapse" data-toggle="collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav" >
					<li><a href="{{ url('/') }}">Reporteador</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
					    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
					    <li><a href="{{ url('/auth/register') }}">Soporte</a></li>
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Registro</a></li>

					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Salir</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')
</body>
</html>
