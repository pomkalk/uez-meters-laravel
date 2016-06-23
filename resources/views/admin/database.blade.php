@extends('admin.layouts.app')


@section('content')



<div class="panel panel-default">
	<div class="panel-heading"><strong>База данных счетчиков</strong>{!! $isTrash?' - Удаленные - <a href="'.url('admin/database').'">Назад</a>':'' !!}</div>
	<div class="panel-body">
		@if (!$isTrash)
		<a href="{{url('admin/database/add')}}" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span>Добавить счетчики</button>
		</a>
		@if ($active_file)
		<a href="{{url('admin/database/download')}}" class="btn btn-primary"><span class="glyphicon glyphicon-download"></span>Скачать данные в CSV</button>
		</a>
		@endif
		@endif
		@if( $trashed>0 )
		<a href="{{url('admin/database/trashed')}}" class="btn btn-warning"><span class="glyphicon glyphicon-trash"></span>Удаленные</button>
		</a>
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
		
		@if( count($files)>0 )
			<table class="table table-responsive table-striped table-hover">
				<thead>
					<th>Название</th>
					<th>Дата загрузки</th>
					<th>Действие</th>
				</thead>
				<tbody>
					@foreach($files as $file)
					<tr class="{{ ($file->active)?'success':''}}">
						<td>{{ $file->name }}</td>
						<td>{{ $file->created_at }}</td>
						<td>
							@if ((!$file->active) && (!$isTrash))
								<a href="{{ url('admin/database/activate') }}/{{ $file->id }}">Активировать</a>|
							@endif
							@if ($isTrash)
							<a href="{{ url('admin/database/restore') }}/{{ $file->id }}">Восстановить</a>
							@else
							<a href="{{ url('admin/database/delete') }}/{{ $file->id }}">Удалить</a>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			{{ $files->links() }}
		@else
		<p>Файлы не загружены</p>
		@endif
	</div>
</div>





@stop

@section('script')
<script src="http://malsup.github.com/jquery.form.js"></script> 
<script type="text/javascript">
	$(function(){

	})
</script>
@stop