@extends('layouts.app')

@section('content')

<div class="ui top attached secondary segment">
	Укажите адрес
</div>
<div class="ui attached segment" style="margin-bottom: 40px">
	@if(count($errors)>0)
		<div class="ui error message">
			<ul>
				@foreach($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<form id="client-form" method="post" action="{{ url('/') }}" class="ui form">
	{{ csrf_field() }}
	<div id="streets" class="field">
		<label>Улица</label>
		<div class="ui fluid search selection dropdown">
			<input type="hidden" name="street">
			<i class="dropdown icon"></i>
			<div class="default text">Улица</div>
			<div class="menu">
				@foreach($streets as $street)
				<div class="item" data-value='{{$street->id}}'>{{$street->prefix.' '.$street->name}}</div>
				@endforeach
			</div>
		</div>
	</div>
	<div id="buildings" class="disabled field">
		<label>Номер дома</label>
		<div class="ui fluid search selection dropdown">
			<input type="hidden" name="building">
			<i class="dropdown icon"></i>
			<div class="default text">Номер дома</div>
			<div class="menu">
				@foreach($streets as $street)
				<div class="item" data-value='{{$street->id}}'>{{$street->prefix.' '.$street->name}}</div>
				@endforeach
			</div>
		</div>
	</div>
	<div id="apartments" class="disabled field">
		<label>Номер квартиры</label>
		<div class="ui fluid search selection dropdown">
			<input type="hidden" name="apartment">
			<i class="dropdown icon"></i>
			<div class="default text">Номер квартиры</div>
			<div class="menu">
				@foreach($streets as $street)
				<div class="item" data-value='{{$street->id}}'>{{$street->prefix.' '.$street->name}}</div>
				@endforeach
			</div>
		</div>
	</div>		
	<div id="ls" class="disabled field">
		<label>Лицевой счет</label>
		<input type="text" name="ls" placeholder="Лицевой счет">
	</div>
	<div id="space" class="disabled field">
		<label>Площадь</label>
		<input type="text" name="space" placeholder="Площадь">
	</div>	
	<div class="ui segment">
		<div align="center" class="g-recaptcha" data-sitekey="6LfjFxUTAAAAAIYJ4ljxOk1sXn0dU6nVggqh-3GJ" data-size="compact"></div>
	</div>
	<div class="ui basic center aligned segment">
		<div class="ui toggle checkbox">
			<input type="checkbox" name="remember">
			<label>Запомнить адрес</label>
		</div>		
	</div>
	<button type="submit" class="ui fluid green button">Найти адрес</button>
	</form>
</div>


<script type="text/javascript">
$request = null;
var stop_request = function(){
	if ($request != null)	
		$request.abort();
}
$(function(){

	var onApartmentChange = function(value, text, $item){
		console.log(value+": "+text);
		$('#ls input').val('');
		$('#space input').val('');
		$('#ls').removeClass('disabled');
		$('#space').removeClass('disabled');
	}

	var onBuildingChange = function(value, text, $item){
		$('#apartments .selection.dropdown').dropdown('restore defaults');
		if (value=='') 
			return;
		$('#apartments').addClass('disabled');
		$('#apartments .selection.dropdown').addClass('loading');
		$('#apartments .selection.dropdown').dropdown('restore defaults');

		$('#ls').addClass('disabled');
		$('#ls input').val('');
		$('#space').addClass('disabled');
		$('#space input').val('');

		$.get('{{url("building")}}'+value,function(response){
			$('#apartments .dropdown .menu').html('');
			for (i in response)
			{
				var item = $('<div class="item" data-value="'+response[i].id+'">'+response[i].title+'</div>');
				$('#apartments .dropdown .menu').append(item);
			}
			$('#apartments .selection.dropdown').dropdown('refresh');

			$('#apartments .selection.dropdown').dropdown({
				onChange: onApartmentChange
			});
			$('#apartments .selection.dropdown').removeClass('loading');
			$('#apartments').removeClass('disabled');

		},'json');		
	}

	var onStreetChange = function(value, text, $item){
		if (value=='') 
			return;
		$('#buildings').addClass('disabled');
		$('#buildings .selection.dropdown').addClass('loading');
		$('#buildings .selection.dropdown').dropdown('restore defaults');
		$('#apartments').addClass('disabled');

		$('#ls').addClass('disabled');
		$('#ls input').val('');
		$('#space').addClass('disabled');
		$('#space input').val('');

		$.get('{{url("street")}}'+value,function(response){
			$('#buildings .dropdown .menu').html('');
			for (i in response)
			{
				var item = $('<div class="item" data-value="'+response[i].id+'">'+response[i].title+'</div>');
				$('#buildings .dropdown .menu').append(item);
			}
			$('#buildings .selection.dropdown').dropdown('refresh');

			$('#buildings .selection.dropdown').dropdown({
				onChange: onBuildingChange
			});
			$('#buildings .selection.dropdown').removeClass('loading');
			$('#buildings').removeClass('disabled');

		},'json');
	}




	$('#streets .dropdown').dropdown({
		fullTextSearch:true, 
		onChange: onStreetChange
	});

	$('#submitButton').click(function(){
		var $form = $(this).closest('form');
		
	});

	$('button.green.button').click(function(){
		$(this).addClass('loading');
	});

	@if(count($errors)>0)
		
		$('#streets .dropdown').dropdown('set selected', '{{ old("street") }}');
		
	@endif

})

</script>
@stop