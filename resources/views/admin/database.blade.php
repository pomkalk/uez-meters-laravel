@extends('admin.layouts.app')


@section('content')

<div class="panel panel-default">
	<div class="panel-heading"><strong>База данных счетчиков</strong></div>
	<div class="panel-body">
		<button id="add-new-meters" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span>Добавить счетчики</button>
		<div>
			
		</div>
	</div>
</div>

<div class="modal fade" id="upload-form">
	<form id="meters-upload-form" action="{{action('AdminController@postDatabaseUpload')}}" method="post" nctype="multipart/form-data">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="modal-title">Загрузка файла</div>
				</div>
				<div class="modal-body">
						{{ csrf_field() }}
						<div class="form-group">
							<label>.zip файл со счетчиками</label>
							<input class="form-control" type="file" name="meters_file" id="meters_file">	
						</div>
						
						<div class="progress">
							<div id="progress" class="progress-bar" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%"></div>
						</div>
				</div>
				<div class="modal-footer">
					<button type="submit"class="btn btn-success">
						<span class="glyphicon glyphicon-upload"></span>
						Загрузить файл
					</button>
					<button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Закрыть</button>				
				</div>
			</div>
		</div>
	</form>		
</div>



@stop

@section('script')
<script src="http://malsup.github.com/jquery.form.js"></script> 
<script type="text/javascript">
	$(function(){

		$('#add-new-meters').click(function(){
			$('#upload-form').modal('show');
			$('#progress').attr('aria-valuenow','0').css('width',$('#progress').attr('aria-valuenow')+'%');
		});

		$('#meters-upload-form').ajaxForm({
			beforeSend:function(){
				$('#progress').attr('aria-valuenow','0').css('width',$('#progress').attr('aria-valuenow')+'%');
			},
			uploadProgress: function(event, pos, total, comp){
				$('#progress').attr('aria-valuenow',comp).css('width',$('#progress').attr('aria-valuenow')+'%');	
			},
			complete: function(data){
				console.log(data);
				if (data.status!=200){
					$('#upload-form').modal('hide');
					$(document).write(data.responseText);
					alert(data.responseText);
				}else{
					$('#upload-form').modal('hide');
				}
			},
			error: function(){
				console.log(arguments);
			}
		});
	})
</script>
@stop