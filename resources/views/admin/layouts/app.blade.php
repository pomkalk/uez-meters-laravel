<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Административная панель</title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/admin/styles.css') }}">
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="{{url('admin')}}">Административная панель</a>
			</div>
		</div>
	</nav>
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-info">
					<div class="panel-heading">Меню</div>
					<div class="panel-body">
						<ul class="nav nav-pills nav-stacked">
							<li role="presentation" class="{{ Request::is('admin')?'active':'' }}"><a href="{{ url('admin') }}">Dashboard</a></li>
							<li role="presentation" class="{{ Request::is('admin/database')?'active':'' }}"><a href="{{ url('admin/database') }}">База данных</a></li>
							<li role="presentation" class="{{ Request::is('admin/feedback')?'active':'' }}"><a href="{{ url('admin/feedback') }}">Отзывы</a></li>
							<li role="presentation" class="{{ Request::is('admin/settings')?'active':'' }}"><a href="{{ url('admin/settings') }}">Настройки</a></li>
							<li role="presentation"><a href="{{ url('admin/logout') }}">Выйти</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-md-9">
				<div class="panel panel-default">
					some content
				</div>
			</div>
		</div>
	</div>

	@include('layouts.footer')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>