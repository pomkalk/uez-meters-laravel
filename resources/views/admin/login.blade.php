<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Административная панель</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

<div class="container">	
	<div class="row">
		<div class="col-md-offset-4 col-md-4 login-form-header">
			<h4 class="text-center">
				<strong>Административная панель</strong>
			</h4>
		</div>	
	</div>
	<div class="row">
		<div class="col-md-offset-4 col-md-4  login-form">
			<form action="{{ url('admin/login') }}" method="post">
				{{ csrf_field() }}
				<div class="form-group">
					<label for="email">Электронная почта</label>
					<input type="email" id="email" name="email" class="form-control" placeholder="Электронная почта" value="{{ old('email') }}">
					
				</div>
				<div class="form-group">
					<label for="password">Пароль</label>
					<input type="password" id="password" name="password" class="form-control" placeholder="Пароль">
				</div>
				<div class="text-right">
					<button class="btn btn-primary btn-block" type="submit">Войти</button>
				</div>
			</form>
		</div>
	</div>	
</div>		
<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>	
<script src="{{ asset('js/bootstrap-notify.min.js') }}"></script>
<script type="text/javascript">
	$(function(){
		@if (count($errors)>0)
var errors = {!! json_encode($errors->all()) !!};
		@else
var errors = undefined;
		@endif
		
	  	for (i in errors){
			$.notify({
				message: errors[i]
			},{
				type: 'danger',
				delay: 5000,
				timer: 1000,
				placement:{
					from: 'bottom',
					align: 'center'
				},
				animate: {
					enter: 'animated fadeInDown',
					exit: 'animated fadeOutUp'
				}
			});
		}
	})
</script>
</body>
</html>