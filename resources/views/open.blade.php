@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('trumbowyg/trumbowyg.min.css') }}">
<script type="text/javascript" src="{{ asset('trumbowyg/trumbowyg.min.js') }}"></script>

<div class="ten wide large screen twelve wide computer fiveteen wide tablet fiveteen wide mobile column">

	<div id="success-message" class="ui success message transition hidden" style="text-align: justify;">
		
	</div>

	<div id="errors-list" class="ui error message transition hidden" style="text-align: justify;">
		
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
									<input id="meter[{{ $meter->id }}]" name="meter[{{ $meter->id }}]" type="number" step="any" style="text-align: center" {!! array_key_exists($meter->id, $meter_values)?'value="'.$meter_values[$meter->id].'"':'' !!}>
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

	<div class="ui basic center aligned segment">
		<div id="feedback-button" class="ui basic button">
			<i class="teal comments outline icon"></i>
			Оставить отзыв
		</div>
		<div id="feedback-list" class="ui basic button{{ ($feedbacks_count==0)?' transition hidden':''}}">
			<i class="teal browser icon"></i>
			Посмотреть оставленные отзывы
		</div>
	</div>
</div>

<div id="feedback-form" class="ui modal">
	<i class="close icon"></i>
	<div class="header">Форма для отзыва</div>
	<div class="content">
		<form id="form-feedback" class="ui form">
			{{ csrf_field() }}
			<input type="hidden" name="owner" value="{{ $apartment->ls }}">
			<div class="field">
				<label>Адрес отправителя</label>
				<input type="text" readonly value="{{ $address }} (ЛС {{ $apartment->ls }})">
			</div>
			<div class="field">
				<label>Текст отзыва</label>
				<textarea id="feedtext" name="feedtext" rows="4"></textarea>
			</div>
		</form>
		<div class="ui error message transition hidden"></div>
	</div>
	<div class="actions">
		<div id="save-feedback" class="ui basic green button">Оставить отзыв</div>
		<div class="ui basic cancel red button">Закрыть</div>
	</div>
</div>

<div id="feedback-table" class="ui modal">
	<i class="close icon"></i>
	<div class="header">Ваши отзывы</div>
	<div class="modal content">

	</div>
	<div class="actions">
		<div class="ui basic cancel red button">Закрыть</div>
	</div>
</div>

@if (count($feedbacks)>0)
<div id="feedback-notify" class="ui modal">
	<i class="close icon"></i>
	<div class="header">Вы получили ответ на отзывы</div>
	<div class="modal content">
		<div class="ui feed">
			@foreach ($feedbacks as $feedback)
			<div class="event">
				<div class="label">
					<i class="pencil icon"></i>
				</div>
				<div class="content">
					<div class="date">{{ $feedback->created_at }}</div>
					<div class="summary">
						{!! $feedback->text !!}
						<div class="ui feed">
							<div class="event">
								<div class="label">
									<i class="green share icon"></i>
								</div>
								<div class="content">
									{!! $feedback->answer->text !!}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>

	</div>
	<div class="actions">
		<div class="ui basic cancel red button">Закрыть</div>
	</div>
</div>
@endif

<script type="text/javascript">
	$(function(){
	@if ($show_info)
		$('.ui.info.message').transition({
    		animation : 'jiggle',
    		duration  : 800,
    		interval  : 200
  		});
	@endif
		@if (count($feedbacks)>0)
		$('#feedback-notify').modal('setting','transition','fade up').modal('show');
	@endif

		$('#meters').submit(false);
		$('#form-feedback').submit(false);
		// $('#feedtext').trumbowyg({
		// 	lang: 'ru',
		// 	btns:['bold','italic','underline'],
		// 	autogrow: true,
		// 	semantic: true,
		// });
		$('#save').click(function(){
			var btn = $(this);
			if (!btn.hasClass('loading')){
				var form_data = $('#meters').serialize();
				btn.addClass('disabled loading');
				$('.ui.input.values').addClass('disabled');

				$.post("{{ url('save') }}", form_data, function(data){
					if (data.success){
						if (data.empty){
							$('.ui.input.values').removeClass('error');
							$('#errors-list').transition('hide');
							btn.removeClass('disabled loading');
							$('.ui.input.values').removeClass('disabled');
						}else{
							$('#errors-list').transition('hide');
							btn.hide();
							$('.ui.input.values').removeClass('error');
							$('.ui.input.values').removeClass('disabled');
							$('.ui.input.values input').prop('readonly',true);
							$('#success-message').html(data.message);
							$('#success-message').transition('fade in');												
						}

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
						btn.removeClass('disabled loading');
						$('.ui.input.values').removeClass('disabled');				
					}
				}, 'json');
			}
		});

		$('#feedback-button').click(function(){
			$('#feedtext').trumbowyg('empty');
			$('#feedback-form').modal('setting','transition','fade up').modal('show');
		});

		$('#save-feedback').click(function(){
			var btn = $(this);
			if (!btn.hasClass('loading')){
				$('#feedback-form .ui.error.message').transition('hide');
				btn.addClass('loading');
				$.post('{{ url("savefeedback") }}', $('#form-feedback').serialize(), function(data){
					if (data.success){
						$('#feedback-form .ui.error.message').html("<ul>"+errors+"</ul>");
						$('#feedback-list').transition('show');
						$('#feedback-form').modal('setting','transition','fade up').modal('hide');
					}else{
						var errors = "";
						for (i in data.errors){
							error = data.errors[i];
							errors+="<li>"+error+"</li>";
						}
						$('#feedback-form .ui.error.message').html("<ul>"+errors+"</ul>");
						$('#feedback-form .ui.error.message').transition('fade in');
					}
					btn.removeClass('loading');
				}, 'json');
			}
		});

		var load_feed = function(){
			$.get('{{ url("feedbacks") }}?ls={{ $apartment->ls }}&page='+$(this).data('page'),function(data){
				if (data.success){
						var feeds = '<div class="ui feed">';
						for (i in data.data){
							item = data.data[i];
							feeds+='<div class="event"><div class="label"><i class="pencil icon"></i></div><div class="content"><div class="date">';
							feeds+=item.date;
							feeds+='</div><div class="summary">';
							feeds+=item.text;
							if (item.answer)
								feeds+='<div class="ui feed"><div class="event"><div class="label"><i class="green share icon"></i></div><div class="content">'+item.answer+'</div></div></div>';
							feeds+='</div></div></div>';
						}
						feeds+='</div>';
						$('#feedback-table').find('.modal.content').html(feeds);
						if (data.lastPage>1){
							$('#feedback-table').find('.modal.content').append('<div class="ui pagination menu">');
							for (i=1;i<=data.lastPage;i++){
								if (i == data.currentPage){
									$('#feedback-table').find('.modal.content .ui.pagination').append('<div class="active link item">'+i+'</div>');
								}else{
									$('#feedback-table').find('.content .ui.pagination').append('<div class="feedpage link item" data-page="'+i+'">'+i+'</div>');
								}
							}
							$('.feedpage').click(load_feed);
						}
				}
			}, 'json');
		}

		$('#feedback-list').click(function(){ 
			var btn = $(this);
			if (!btn.hasClass('loading')){
				btn.addClass('loading');
				$.get('{{ url("feedbacks") }}?ls={{ $apartment->ls }}',function(data){
					if (data.success){
						var feeds = '<div class="ui feed">';
						for (i in data.data){
							item = data.data[i];
							feeds+='<div class="event"><div class="label"><i class="pencil icon"></i></div><div class="content"><div class="date">';
							feeds+=item.date;
							feeds+='</div><div class="summary">';
							feeds+=item.text;
							if (item.answer)
								feeds+='<div class="ui feed"><div class="event"><div class="label"><i class="green share icon"></i></div><div class="content">'+item.answer+'</div></div></div>';							
							feeds+='</div></div></div>';
						}
						feeds+='</div>';
						$('#feedback-table').find('.modal.content').html(feeds);
						if (data.lastPage>1){
							$('#feedback-table').find('.modal.content').append('<div class="ui pagination menu">');
							for (i=1;i<=data.lastPage;i++){
								if (i == data.currentPage){
									$('#feedback-table').find('.modal.content .ui.pagination').append('<div class="active link item">'+i+'</div>');
								}else{
									$('#feedback-table').find('.content .ui.pagination').append('<div class="feedpage link item" data-page="'+i+'">'+i+'</div>');
								}
							}
							$('.feedpage').click(load_feed);
						}
						$('#feedback-table').modal('setting','transition','fade up').modal('show');
					}else{
						var errors = "";
						for (i in data.errors){
							error = data.errors[i];
							errors+="<li>"+error+"</li>";
						}
						$('#errors-list').html("<ul>"+errors+"</ul>");
						$('#errors-list').transition('fade in');
					}
					btn.removeClass('loading');
				}, 'json');
			}
		});
	})

</script>
@stop