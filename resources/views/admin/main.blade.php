@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Посещения</strong></div>
				<div class="panel-body">
					<img src="{{ asset('img/loading.gif') }}" alt="">
				</div>
			</div>			
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading"><strong>Статистика показаний</strong></div>
				<div class="panel-body">
					<img src="{{ asset('img/loading.gif') }}" alt="">
				</div>
			</div>			
		</div>		
	</div>
</div>



@stop