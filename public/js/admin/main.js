$(function(){
	$.fn.editable.defaults.mode="inline";
	$.fn.editable.defaults.showbuttons="bottom";
	
	$('#site-available').on('init',function(e, editable){
		$('#site-available-label').addClass(editable.value==1?'success':'danger');
	});	
	$('#site-available').on('save',function(e, params){
		if (params.newValue == '1'){
			$('#site-available-row').removeClass('danger');
			$('#site-available-row').addClass('success');
		}
		if (params.newValue == '0'){
			$('#site-available-row').removeClass('success');
			$('#site-available-row').addClass('danger');
		}
	});

	$('.settings-fields').editable();


	$(".navbar-toggle").click(function(){
		$("#side-menu").slideToggle('slow');
	});
})