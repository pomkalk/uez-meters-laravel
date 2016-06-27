@extends('admin.layouts.app')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading"><strong>Просмотр базы</strong> - <a href="{{ url()->previous() }}">Назад</a></div>
	<div class="panel-body">
		<h4>{{ $address }}</h4>
		<table class="table table-responsive table-striped table-hover">
			<thead>
				<tr class="center aligned">
					<th>Счетчик</th>
					<th>Дата последних показаний</th>
					<th>Последние показание</th>
					<th>Новые показания</th>
				</tr>
			</thead>
			<tbody>
				@foreach($meters as $meter)
				
					@if($meter->status_id == -2)
						<tr class="danger">
							<td>{{ $meter->service->name }}</td>
							<td colspan="3">Приостановлен</td>
						</tr>
					@elseif ($meter->status_id == -1)
						<tr class="warning">
							<td>{{ $meter->service->name }}</td>
							<td colspan="3">Заблокирован</td>
						</tr>
					@else
						<tr>
							<td>{{ $meter->service->name }}</td>
							<td>{{ $meter->last_date }}</td>
							<td>{{ $meter->last_value }}</td>
							<td>
								<div>{!! array_key_exists($meter->id, $meter_values)?$meter_values[$meter->id]:'' !!}</div>
							</td>
						</tr>
					@endif
					
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@stop