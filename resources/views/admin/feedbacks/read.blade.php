@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('trumbowyg/trumbowyg.min.css') }}">
<script type="text/javascript" src="{{ asset('trumbowyg/trumbowyg.min.js') }}"></script>

<div class="panel panel-default">
	<div class="panel-heading"><strong>Отзыв от {{ $feedback->address }}</strong></div>
	<div class="panel-body">
		<h4>Дата: {{ $feedback->created_at->format('d.m.Y') }}</h4>
		<hr>
		<p>{!! $feedback->text !!}</p>
		<hr>
		@if ($feedback->answer)
		<h4><mark>Ответ на отзыв - <small>23.05.2016 - <a href="{{ url('admin/feedbacks/delete') }}/{{ $feedback->answer->id }}">Удалить ответ</a></small></mark></h4>
		<p>{!! $feedback->answer->text !!}</p>
		@endif
		
		@if(count($errors)>0)
			<div class="alert alert-danger">
				<ul>
					@foreach($errors->all() as $error)
						<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form action="{{ url('admin/feedbacks/save') }}" method="post">
			{{ csrf_field() }}
			<input type="hidden" name="fid" value="{{ $feedback->id }}">
			<div id="editor">Добрый день, </div>
			<button type="submit" class="btn btn-success">Отправить</button>
		</form>
		
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('#editor').trumbowyg();
	});
</script>
@stop