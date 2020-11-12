$(window).on('resize', function() {

	$windows_width=$(window).width();
	$windows_height=$(window).height();

	if($.cookie('device')==='computer'){
		
		if($windows_width){	
			if(415<=$windows_width){
				if($.cookie('style')!='computer'){
					$.cookie('style', 'computer', {expires: 365, path: '/'});
					location.reload();
				}
			}

			
			if($windows_width<=414){
				if($.cookie('style')!='phone'){
					$.cookie('style', 'phone', {expires: 365, path: '/'});
					location.reload();
				}
			}
		}
	}
});