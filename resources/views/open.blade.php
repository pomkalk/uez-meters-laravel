@extends('layouts.app')

@section('content')

<div class="ten wide large screen twelve wide computer fiveteen wide tablet fiveteen wide mobile column">
	<div id="errors-list" class="ui error message transition hidden">
		
	</div>
	<div class="ui top attached secondary segment">
		{{ $address }} (ЛС {{ $apartment->ls }})
	</div>
	<div class="ui attached segment" style="margin-bottom: 40px">
		<table id="data-table" class="ui very basic selectable table">
			<thead>
				<tr class="center aligned">
					<th>Счетчик</th>
					<th>Дата последних показаний</th>
					<th>Последние показание</th>
					<th>Новые показания</th>
				</tr>
			</thead>
			<tbody>
				<form id="meters">
				{{ csrf_field() }}
				<input type="hidden" name="sdata" value="{{ $apartment->id }}:{{ $file_id }}">
				@foreach($meters as $meter)
				
					@if($meter->status_id == -2)
						<tr class="warning center aligned">
							<td>{{ $meter->service->name }}</td>
							<td colspan="3" class="center aligned">Приостановлен</td>
						</tr>
					@elseif ($meter->status_id == -1)
						<tr class="negative center aligned">
							<td>{{ $meter->service->name }}</td>
							<td colspan="3" class="center aligned">Заблокирован</td>
						</tr>
					@else
						<tr class="center aligned">
							<td>{{ $meter->service->name }}</td>
							<td>{{ $meter->last_date }}</td>
							<td>{{ $meter->last_value }}</td>
							<td>
								<div class="ui fluid small input values">
									<input id="meter[{{ $meter->id }}]" name="meter[{{ $meter->id }}]" type="number" step="any" style="text-align: center">
								</div>
							</td>
						</tr>
					@endif
					
				@endforeach
				</form>
			</tbody>
		</table>
		<div class="ui right aligned basic segment">
			<div id="save" class="ui green button">Сохранить</div>
		</div>
	</div>

	@if ($show_info)
	<div class="ui info message" style="text-align: justify;">
		{{ AppConfig::get('work.infometter') }}
	</div>
	@endif
</div>

<script type="text/javascript">
	$(function(){
	@if ($show_info)
		$('.ui.info.message').transition({
    		animation : 'jiggle',
    		duration  : 800,
    		interval  : 200
  		});
	@endif
		$('#meters').submit(false);
		$('#save').click(function(){
			var btn = $(this);
			var form_data = $('#meters').serialize();
			//btn.addClass('disabled loading');
			//$('.ui.input.values').addClass('disabled');

			$.post("{{ url('save') }}", form_data, function(data){
				if (data.response){

				}else{
					var errors = "";
					for (i in data.errors){
						error = data.errors[i];
						errors+="<li>"+error+"</li>";
					}
					$('.ui.input.values').removeClass('error');
					for (i in data.efields){
						$('input[name="meter['+data.efields[i]+']"]').closest('.ui.input.values').addClass('error');
					}
					$('#errors-list').html("<ul>"+errors+"</ul>");
					$('#errors-list').transition('fade in');
					//btn.removeClass('disabled loading');
					//$('.ui.input.values').removeClass('disabled');				
				}
			}, 'json');

		});
	})

</script>
@stop