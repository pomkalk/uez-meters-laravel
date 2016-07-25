@extends('admin.layouts.app')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading"><strong>Просмотр логов</strong></div>
	<div class="panel-body">
		<div class="list-group">
			@if (count($logs)>0)
			@foreach($logs as $log)
			<a href="{{ url('admin/logs/read')}}/{{$log}}" class="list-group-item">{{$log}}</a>
			@endforeach
			@else
			Список пуст.
			@endif
		</div>	
	</div>
</div>

@stop