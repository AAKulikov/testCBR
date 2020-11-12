//изменение шаблона при ресайзе окна в широкоформатной версии

$(window).on('resize', function() {

	$windows_width_new=$(window).width();
	$windows_height_new=$(window).height();
	
	if($.cookie('device')==='computer'){
		
		if($windows_width_new){	
			if(415<=$windows_width_new){
				if($.cookie('style')!='computer'){
					$.cookie('style', 'computer', {expires: 365, path: '/'});
					location.reload();
				}
			}
			
			if($windows_width_new<=414){
				if($.cookie('style')!='phone'){
					$.cookie('style', 'phone', {expires: 365, path: '/'});
					location.reload();
				}
			}
		}
	}
	
});


//постраничная навигация 

$(document).on('click', '.page', function(event) {
	event.preventDefault();
	$page=$(this).attr('data-page');
	if($page!='0'){
		$.ajax({
			type: 'POST',
			url: '/k.it:engine/action/ ',
			data:'action=page&id='+$page,
			dataType: 'JSON',
			success: function(page){
				if(page.code=='200'){
						$('#reload').empty().html(page.res);
						$('html, body').animate({scrollTop: $('main').offset().top},'slow');
				}
			}
		})
	}
});


//фильтрация
var datePattern = /^(19|20)\d\d-(0\d|1[012])-(0\d|1\d|2\d|3[01])$/;

$(document).on('click', 'button.action', function(event) {
	event.preventDefault();
	$action=$(this).attr('data-action');
	
	if($action!=''){
		$('form#'+$action).find('select.required, input.required').each(function(i,e) {
			
			if(!$(this).val()){
				
				if($(this).attr('type')=='date'){
					if(!datePattern.test($(this).val())){
						alert($(this).attr('placeholder'));
						$(this).addClass('error');
						$error=1;
						return false;
					} else {
						$(this).removeClass('error');
						$error=0;
					}
				}
							
				if($(this).attr('data-type')=='select'){
					if($(this).val()==''){
						alert($(this).attr('data-error'));
						$(this).addClass('error');
						$error=1;
						return false;
					} else {
						$(this).removeClass('error');
						$error=0;
					}
				}
				
			} else {
				$.cookie($(this).attr('name'), $(this).val(), {expires: 1, path: '/',});
				$(this).removeClass('error');
				
				if($(this).attr('data-type')=='select'){
					$.cookie($(this).attr('name'), $(this).attr('data-val'), {expires: 1, path: '/',});
				}
				$error=0;
			}
		});
		
	} else {
		$error=0;
	}
	
	if($error=='0'){
		
		$.cookie('action', $action, {expires: 1, path: '/',});
		
		$.ajax({
				type: 'POST',
				url: '/k.it:engine/action/ ',
				data:'action=filter',
				dataType: 'JSON',
				success: function(page){
					if(page.code=='200'){
							$('#reload').empty().html(page.res);
							$('html, body').animate({scrollTop: $('main').offset().top},'slow');
					}
				}
			})
		}	
});


//показ фильтров
$(document).on('click', 'span.show', function(event) {
	event.preventDefault();
	var $box=$(this).attr('data-box');
	var $close=$(this).attr('data-close');
	$('form#'+$box).toggle();
	$('form#'+$close).toggle(false);
});

$(document).on('click', 'button#export-json', function(event) {
	event.preventDefault();
	$load=$('textarea#json').val();
	
	$.ajax({
		type: 'POST',
		url: '/k.it:engine/action/ ',
		data:'action=json&data='+$load,
		dataType: 'JSON',
		success: function(file){
			if(file.code=='200'){
					$('button#export-json').hide();
					$('#show-link-json').empty().html(file.res);
			}
		}
	})
	
});