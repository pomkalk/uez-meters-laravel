<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Административная панель</title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top">
		<div class="container">
			<div class="navbar-header">
				<a href="{{asset('/')}}" class="navbar-brand"><span class="glyphicon glyphicon-arrow-left"></span></a>
				<a href="{{asset('admin')}}" class="navbar-brand">Административная панель</a>
			</div>
		</div>
	</nav>
	
	<div class="container">
		<div class="row">
			<div class="col-md-offset-3 col-md-6">
				@if (count($errors)>0)
					<div class="alert alert-danger">
						<ul>
							@foreach($errors->all() as $error)
								<li>{{$error}}</li>
							@endforeach
						</ul>
					</div>
				@endif
				<form action="{{ url('admin/login') }}" method="post">
					{{ csrf_field() }}
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
							<input type="email" name="email" id="email" class="form-control" value="{{old('email')}}">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
							<input type="password" name="password" id="password" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-success btn-block">Войти</button>
					</div>		
				</form>
			</div>
		</div>
	</div>

	@include('layouts.footer');

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>