@extends('admin.layouts.app')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading"><strong>Отзывы</strong></div>
	<div class="panel-body">
		@if (count($feedbacks)>0)
		<table class="table table-responsive table-striped table-hover">
			<thead>
				<th></th>
				<th>Дата</th>
				<th>Адрес</th>
				<th>Отзыв</th>
				
			</thead>
			<tbody>
				@foreach($feedbacks as $feedback)
				<tr{!! (!$feedback->read_at)?' class="info"':'' !!}>
					<td>
					@if ($feedback->answer)
					<span data-toggle="tooltip" data-placement="top" title="Отправлен {{ $feedback->answer->updated_at->format('d.m.Y') }}" class="glyphicon glyphicon-envelope" aria-hidden="true" style="color: {{ $feedback->answer->read_at?'green':'black'  }};"></span>
					@endif
					</td>
					<td>{{ $feedback->created_at->format('d.m.Y') }}</td>
					<td><a href="{{ url('admin/feedbacks') }}/{{ $feedback->id }}">{{ str_replace(' ','&nbsp;',$feedback->address) }}</a></td>
					<td style="width: 100%;">{!! str_limit($feedback->text, 50) !!}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $feedbacks->links() }}
		@else
		Список пуст
		@endif
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('[data-toggle="tooltip"]').tooltip();
	})
</script>
@stop