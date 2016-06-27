@extends('layouts.app')

@section('content')
<div class="ui container">
	<div class="ui piled segment">
		<div class="ui horizontal divider">ВНИМАНИЕ</div>
		{{ AppConfig::get('work.unmessage') }}	
		<div class="ui divider"></div>
		<div class="ui basic right aligned segment">
			<a class="" href="http://uez-lk.ru">Вернуться</a>
		</div>
	</div>
</div>

@stop