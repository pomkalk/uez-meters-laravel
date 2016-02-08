<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Административная панель</title>
	<link rel="stylesheet" href="{{ asset('css/admin/styles.css') }}">
</head>
<body>
	<div class="wrapper">
		<div class="top-margin">
		</div>
		<div class="header-line">
			<div class="container">
				<div class="header-title">
					<img src="{{ asset('img/admin/login-marker.png') }}" height="45" width="45" alt="marker">
					Административная панель
				</div>
			</div>
		</div>
		<div class="errors">
			<button class="close">Закрыть</button>
			<div class="container">
			</div>
		</div>
		<div class="content">
			<div class="container">
				<div class="form-panel">
					<div class="shadow"></div>
					<div class="form">
						<form action="{{ url('admin/login') }}" method="post">
							{{ csrf_field() }}
							<div class="form-group">
								<label for="email">E-mail</label>
								<input type="email" name="email" id="email" value="{{ old('email') }}" autofocus>
							</div>

							<div class="form-group">
								<label for="password">Пароль</label>
								<input type="password" name="password" id="password">
							</div>

							<div class="form-submit">
								<button type="submit">Войти</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
	<script type="text/javascript">
		$(function(){
			@if (count($errors)>0)
				var errors = {!! json_encode($errors->all()) !!}
			@else
				var errors = undefined;
			@endif
			
			//var errors = undefined;
			if (errors){
				for (i in errors)
				{
					$('<div>',{html:errors[i]}).appendTo('.errors .container');
				}
				$('.errors').fadeIn('slow');
				setTimeout(function(){ $('.errors').fadeOut('slow'); },3000);	
			}

			$('.errors .close').click(function(){
				$('.errors').fadeOut('slow');
			});
		})
	</script>
</body>
</html>