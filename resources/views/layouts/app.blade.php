<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Ввод показаний</title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/bootstrap-editable.css') }}">
	<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="{{url('/')}}">Ввод показаний индивидуальных приборов учета</a>
			</div>
		</div>
	</nav>
	<nav class="navbar">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<span class="navbar-brand" href="{{url('/')}}">ООО "УЕЗ ЖКУ г. Ленинска-Кузнецкого"</span>
			</div>
		</div>
	</nav>	
	
	@yield('content')

	@include('layouts.footer')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

</body>
</html>