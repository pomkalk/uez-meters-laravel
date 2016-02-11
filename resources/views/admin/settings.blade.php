@extends('admin.layouts.app')

@section('content-title')

@stop
@section('content')

<div class="panel panel-default">
	<div class="panel-heading"><strong>Настройки</strong></div>
	<div class="panel-body no-padding">
		<table class="table table-striped ">
			<thead>
				<tr>
					<th>Параметр</th>
					<th>Значение</th>
				</tr>
			</thead>
			<tbody>
				<tr id="site-available-row">
					<td>Доступ к сайту</td>
					<td><a href="#" id="site-available" class="settings-fields" data-type="select" data-emptytext="Пусто" data-source="[{value:'1',text:'Доступен'},{value:'0',text:'Не доступен'}]" id="site-available" data-pk='site.available' data-url="{{url('admin/settings')}}" data-value="{{ $site_available }}" data-params="{_token:'{{csrf_token()}}'}"></a></td>
				</tr>
				<tr>
					<td>Сообщение, когда отключен доступ к сайту</td>
					<td><a href="#" class="settings-fields" data-type="textarea" data-emptytext="Пусто" data-pk='site.unmessage' data-url="{{url('admin/settings')}}" data-value="{{ $site_unmessage }}" data-params="{_token:'{{csrf_token()}}'}"></a></td>
				</tr>
				<tr>
					<td>День и время <span class="label label-info">начала</span> ввода показаний</td>
					<td><a href="#" class="settings-fields" data-type="combodate" data-emptytext="#ОШИБКА" data-pk="work.startdate" data-url="{{url('admin/settings')}}" data-value="{{ $work_startdate }}" data-format="YYYY-MM-DD HH:mm" data-viewformat="Каждое D число месяца в HH:mm" data-template="День:D Время HH:mm" data-params="{_token:'{{csrf_token()}}'}"></a></td>
				</tr>
				<tr>
					<td>День и время <span class="label label-info">окончания</span> ввода показаний</td>
					<td><a href="#" class="settings-fields" data-type="combodate" data-emptytext="#ОШИБКА" data-pk="work.enddate" data-url="{{url('admin/settings')}}" data-value="{{ $work_enddate }}" data-format="YYYY-MM-DD HH:mm" data-viewformat="По D число месяца в HH:mm" data-template="День:D Время HH:mm" data-params="{_token:'{{csrf_token()}}'}"></a></td>
				</tr>				
				<tr>
					<td>Информационное сообщение, когда услуга не предоставляется</td>
					<td><a href="#" class="settings-fields" data-type="textarea" data-emptytext="Пусто" data-pk='work.unmessage' data-url="{{url('admin/settings')}}" data-value="{{ $work_unmessage }}" data-params="{_token:'{{csrf_token()}}'}"></a></td>
				</tr>				
			</tbody>
		</table>	
	</div>
</div>



@stop