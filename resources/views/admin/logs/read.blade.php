@extends('admin.layouts.app')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading"><strong>Просмотр лога</strong> - <a href="{{ url('admin/logs') }}">Назад</a></div>
	<div class="panel-body">
		<pre style="word-wrap: break-word; white-space: pre-wrap;">
		{{ $content }}
		</pre>

		@if (isset($file))
		<a href="{{ url('admin/logs/delete') }}/{{$file}}" class="btn btn-danger">Удалить данный файл</a>
		@endif
	</div>
</div>

@stop