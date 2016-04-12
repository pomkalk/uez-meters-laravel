@extends('admin.layouts.app')


@section('content')



<div class="panel panel-default">
	<div class="panel-heading"><strong>Загрузить файл с показаниями</strong></div>
	<div class="panel-body">
		@if(count($errors)>0)
			<div class="alert alert-danger">
				<ul>
					@foreach($errors->all() as $error)
						<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
		@endif
		<form action="{{ url('admin/database/add') }}" method="post" enctype="multipart/form-data" autocomplete="off">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="name">Название для файла</label>
				<input type="text" name="name" id="name" class="form-control">
			</div>
			<div class="form-group">
				<label for="xml">Укажите xml файл с данными</label>
				<input type="file" name="xml" id="xml" class="form-control">				
			</div>
			<button type="submit" class="btn btn-success">Отправить</button>
		</form>
	</div>
</div>





@stop

@section('script')

@stop