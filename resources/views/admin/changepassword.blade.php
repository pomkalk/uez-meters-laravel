@extends('admin.layouts.app')

@section('content')

<div class="panel panel-default">
	<div class="panel-heading"><strong>Сменить пароль</strong></div>
	<div class="panel-body">
		@if (session('success'))
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
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

		<form class="form-horizontal" action="{{url('admin/changepassword')}}" method="post">
			{{ csrf_field() }}
			<div class="form-group">
				<label class="col-md-2 control-label">Старый пароль</label>
				<div class="col-md-10">
					<input class="form-control" type="password" name="old_pass" id="new_pass">					
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label">Новый пароль</label>
				<div class="col-md-10">
					<input class="form-control" type="password" name="new_pass" id="new_pass">					
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label">Новый пароль еще раз</label>
				<div class="col-md-10">
					<input class="form-control" type="password" name="new_repeat" id="new_repeat">					
				</div>
			</div>
			<div class="text-right">
				<button type="submit" class="btn btn-success">Изменить</button>
			</div>
		</form>	
	</div>
</div>



@stop