@extends('layouts.app')

@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-md-offset-3 col-md-6">
			<div class="panel">
				<div>
					{{ AppConfig::get('work.unmessage') }}	
				</div>
				<div class="text-right">
					<a href="http://uez-lk.ru">Вернуться на сайт УЕЗ ЖКУ</a>
				</div>
			</div>			
		</div>
	</div>
</div>

@stop