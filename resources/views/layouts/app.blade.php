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

		#main-grid {
			margin-bottom: 40px;
		}

		@media (max-width: 768px){
			#main-grid {
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
		<div id="help-button" class="ui blue basic button">
			<i class="help icon"></i>
			Справка
		</div>
		&nbsp;&nbsp;&nbsp;
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

<div id="main-grid" class="ui stackable centered grid">
	
		@yield('content')
	
</div>

<div id="help-dialog" class="ui long modal">
	<i class="close icon"></i>
	<div class="header">Справка</div>
	<div class="content">
		@include('help')
	</div>
	<div class="actions">
		<div class="ui basic cancel red button">Закрыть</div>
	</div>
</div>


@include('layouts.footer')
</body>
<script type="text/javascript">
	var help_is_loaded = false;
	$(function(){
		$(window).resize(function(){
			console.log($(window).width());
		});
		$('#help-button').click(function(){
			$('#help-dialog').modal('setting','transition','fade up').modal('show');	
		});
	})

</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter31659071 = new Ya.Metrika({
                    id:31659071,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/31659071" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</html>