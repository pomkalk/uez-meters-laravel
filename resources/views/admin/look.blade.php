@extends('admin.layouts.app')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading"><strong>Просмотр базы</strong> - <a href="{{ url('admin/database') }}">Назад</a></div>
	<div class="panel-body">
		<form action="{{ url('admin/database/look') }}" method="get" class="form-inline">
			<div class="form-group">
				<label for="street">Улица</label>
				<input type="text" id="street" name="street" class="form-control input-sm" placeholder="Адрес" value="{{ Request::input('street') }}">
			</div>
			<div class="form-group">
				<label for="building">Дом</label>
				<input type="text" id="building" name="building" class="form-control input-sm" placeholder="Дом" value="{{ Request::input('building') }}">
			</div>
			<div class="form-group">
				<label for="apartment">Квартира</label>
				<input type="text" id="apartment" name="apartment" class="form-control input-sm" placeholder="Квартира" value="{{ Request::input('apartment') }}">
			</div>
			<div class="form-group">
				<label for="ls">Л/С</label>
				<input type="text" id="ls" name="ls" class="form-control input-sm" placeholder="Л/С" value="{{ Request::input('ls') }}">
			</div>		
			<button type="submit" class="btn btn-success">Найти</button>
			@if ($searching)
				<a href="{{ url('admin/database/look') }}" class="btn btn-warning">X</a>
			@endif
		</form>



		<table class="table table-responsive table-striped table-hover">
			<thead>
				<th>Адрес</th>
			</thead>
			<tbody>
				@foreach ($apartments as $apartment)
				<tr>
					<td>
						<a href="{{ url('admin/database/look/') }}/{{ $apartment->ls }}">
						{{ $apartment->building->street->prefix.'. '.$apartment->building->street->name.' д.'.$apartment->building->number.( ($apartment->building->housing)?'/'.$apartment->building->housing:'' ).', кв. '.$apartment->number.( ($apartment->part)?'/'.$apartment->part:'' ) }} ({{ $apartment->ls }})
						</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $apartments->appends(Request::all())->links() }}
	</div>
</div>

@stop