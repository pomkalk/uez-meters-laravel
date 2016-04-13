@extends('layouts.app')

@section('content')

<div class="ui top attached secondary segment">
	{{ $address }}
</div>
<div class="ui attached segment" style="margin-bottom: 40px">
	<table class="ui very basic selectable table">
		<thead>
			<th>Счетчик</th>
			<th>Дата последних показаний</th>
			<th>Последние показание</th>
			<th>Новые показания</th>
		</thead>
		<tbody>
			@foreach($meters as $meter)
			
			@if($meter->status_id == -2)
				<tr class="warning">
					<td>{{ $meter->service->name }}</td>
					<td colspan="3">Приостановлен</td>
				</tr>
			@elseif ($meter->status_id == -1)
				<tr class="negative">
					<td>{{ $meter->service->name }}</td>
					<td colspan="3">Заблокирован</td>
				</tr>
			@else
				<tr>
					<td>{{ $meter->service->name }}</td>
					<td>{{ $meter->last_date }}</td>
					<td>{{ $meter->last_value }}</td>
					<td>
						<input type="number" step="any">
					</td>
				</tr>
			@endif

			@endforeach
		</tbody>
	</table>
	<a href="{{ url('test') }}">asd</a>
</div>


<script type="text/javascript">


</script>
@stop