<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Административная панель</title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/bootstrap-editable.css') }}">
	<link rel="stylesheet" href="{{ asset('css/admin/styles.css') }}">
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/') }}"><span class="glyphicon glyphicon-home"></span></a>
				<a class="navbar-brand" href="{{url('admin')}}">Административная панель</a>
			</div>
		</div>
	</nav>
	
	<div class="container-fluid">
		<div class="row">
			<div id="side-menu" class="col-md-3 col-sm-3">
				<div class="well well-sm">
					 Пользователь: {{Auth::user()->name }} 
					 <br><a href="{{url('admin/changepassword')}}">Сменить пароль</a>
				</div>
				<ul class="nav nav-pills nav-stacked">
					<li role="presentation" class="{{ Request::is('admin')?'active':'' }}"><a href="{{ url('admin') }}">Dashboard</a></li>
					<li role="presentation" class="{{ Request::is('admin/database')?'active':'' }}"><a href="{{ url('admin/database') }}">База данных</a></li>
					<li role="presentation" class="{{ Request::is('admin/feedback')?'active':'' }}"><a href="{{ url('admin/feedback') }}">Отзывы</a></li>
					<li role="presentation" class="{{ Request::is('admin/settings')?'active':'' }}"><a href="{{ url('admin/settings') }}">Настройки</a></li>
					<li role="presentation"><a href="{{ url('admin/logout') }}">Выйти</a></li>
				</ul>
			</div>

			<div class="col-md-9 col-sm-9">
				@yield('content')			
			</div>
		</div>
	</div>

	@include('admin.layouts.footer')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
	<!-- <script type="text/javascript" src="{{ asset('js/flat-ui.min.js') }}"></script> -->
	<script type="text/javascript" src="{{ asset('js/moment.js') }}"></script>	
	<script type="text/javascript" src="{{ asset('js/bootstrap-editable.min.js') }}"></script>
	<script type="text/javascript">
		$(function(){
			$(".navbar-toggle").click(function(){
				$("#side-menu").slideToggle('slow');
			});
		});
	</script>
	@yield('script')
</body>
</html>