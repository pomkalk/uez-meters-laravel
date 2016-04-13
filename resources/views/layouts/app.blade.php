<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ввод показаний УЕЗ ЖКУ г. Ленинска-Кузнецкого</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('semantic/semantic.css')}}">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.js"></script>
    <script type="text/javascript" src="{{asset('semantic/semantic.js')}}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <style type="text/css">
        body{
            background-color: #DADADA;
        }


		@media screen and (max-height: 575px){
			#rc-imageselect, .g-recaptcha {
				transform:scale(0.77);
				-webkit-transform:scale(0.77);
				transform-origin:0 0;
				-webkit-transform-origin:0 0;
			}
		}

		.ui.stackable.grid{
			margin-bottom: 40px;
		}

		@media (max-width: 768px){
			.ui.stackable.grid{
				margin-bottom: 80px;
			}			
		}
	</style>

</head>
<body>
<div class="ui borderless stackable menu">
	<div class="header item">
		Ввод показаний индивидуальных приборов учета
	</div>
	<div class="right item">
		<a href="http://uez-lk.ru" class="ui basic button">
			<i class="left arrow icon"></i>
			Вернтуться на сайт
		</a>
	</div>
</div>
<div class="ui secondary menu">
	<div class="item">
		ООО "УЕЗ ЖКУ г. Ленинска-Кузнецкого"
	</div>
</div>

<div class="ui stackable centered grid">
	<div class="six wide column">
		@yield('content')
	</div>
</div>



@include('layouts.footer')
</body>
<script type="text/javascript">
	$(function(){
		$(window).resize(function(){
			console.log($(window).width());
		});
	})
</script>
</html>