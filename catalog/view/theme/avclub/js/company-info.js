
hideLastBranch = function(){
	$('.icomp__branch li').not('.d-none').not('.showmore').last().addClass('d-none');
	var 
	$h_item = $('.icomp__branch li').eq(0).outerHeight(true),
	$h_cont = $('.icomp__branch').outerHeight(),
	$row = 5;
	if($(window).width() < 768) {
		if($h_item * $row < $h_cont) {
			hideLastBranch();
		}
	}


}
changeViewBranch = function(){
	$('.icomp__branch').removeClass('active');

	var 
	$h_item = $('.icomp__branch li').eq(0).outerHeight(true),
	$h_cont = 0,
	$row = 5;


	$('.icomp__branch li').removeClass('d-none');
	$('.cofilter__branch_btn').remove();

	if($(window).width() < 768) {
		$height = $h_item * $row;

		$h_cont = $('.icomp__branch').outerHeight();

		if($height <= $h_cont) {
			$('.icomp__branch ul').append('<li class="cofilter__branch_btn showmore"><svg><use xlink:href="#points" /></svg><svg><use xlink:href="#arr-top" /></svg></li>');
			hideLastBranch();
		}
	}
}
$(function(){

	$(document).on('click', '.icomp__pane .pagination a', function(e){
		e.preventDefault();
		var 
		value = [], 
		page = 1, 
		method = '', 
		company_id = $('.icomp__cont').attr('data-id'),
		container = $(this).closest('.icomp__pane'),
		type = container.attr('data-key'),
		query = $(this).attr('href').split('?');

		if (query[1]) {
			var part = query[1].split('&');
			for (i = 0; i < part.length; i++) {
				var data = part[i].split('=');
				if (data[0] && data[1]) {	value[data[0]] = data[1];	}
			}

			if (value['page']) { page = value['page']; }
		}

		switch(type) {
			case 'event': 
			case 'event_old': 
			case 'event_new': 
			method = 'event';break;
			
			case 'master': 
			case 'mastertobe': 
			case 'masterold': 
			method = 'master';
			break;
			
			case 'expert': method = 'expert';break;
			default: method = 'journal'; break;
		}

		var $data = 'json=1';
		if(company_id) { $data += '&company_id=' + company_id;	}
		if(page) { $data += '&page=' + page;	}
		if(type) { $data += '&type=' + type;	}

		$.ajax({
			url: 'index.php?route=company/content/' + method,
			type: 'get',
			dataType: 'json',
			data: $data,
			beforeSend: function(){
				container.addClass('loading');
			},
			complete: function(){
				setTimeout(function(){
					container.removeClass('loading');
				}, 300);
			},
			success: function(data) {
				if(data['template']) {
					container.html(data['template']);
				}
			},
			error: function(data){
				console.log('error', data);
			}
		});


	})

	if($('.icomp__branch li').length){
		$(window).on('load resize', changeViewBranch);
		$(document).on('click', '.cofilter__branch_btn', function(e){
			e.preventDefault();
			$('.icomp__branch').toggleClass('active');
		})
	}


	$('.icomp__show button').on('click', function(e){
		e.preventDefault();
		$('.icomp__info').addClass('active');
		$('.icomp__more').slideDown(200);
		$('.icomp__show').slideUp(200);
	})

	$('.icomp__text_list_btn button').on('click', function(e){
		e.preventDefault();
		$(this).closest('.icomp__text_list').toggleClass('active');
	})

	$('.icomp__change').on('click', function(e){
		e.preventDefault();
		var $type = $(this).attr('data-type');
		$(this).addClass('active').parent().siblings('li').find('a.active').removeClass('active');
		$('.icomp__pane-' + $type).addClass('active').siblings('.icomp__pane').removeClass('active');

		if($type == 'info') {
			$(window).resize();
			return;
		}

		if(!$(this).hasClass('load')) {
			$(this).addClass('load');

			var
			company_id = $('.icomp__cont').attr('data-id'),
			$child_type = $('.icomp__tabs_content .icomp__pane-' + $type).find('.icomp__change.active'),
			$url = '&company_id=' + company_id;

			if($child_type.length) {
				$type = $child_type.attr('data-type');
				$child_type.addClass('load');
			}

			$url += '&type=' + $type;
			
			var timeStart, timeEnd;
			$.ajax({
				url: 'index.php?route=company/content'+$url,
				type: 'get',
				dataType: 'json',
				beforeSend: function(){
					timeStart = new Date().getTime();
					$('.icomp__inner').addClass('loading');
				},
				complete: function(){
					timeEnd = new Date().getTime();
					setTimeout(function(){
						$('.icomp__inner').removeClass('loading');
					}, 200);


					console.log('SecondWay: ' + (timeEnd - timeStart) + 'ms');
				},
				success: function(data) {
					if(data['template']) {
						$('.icomp__inner .icomp__pane-' + $type).html(data['template']);
					}
				},
				error: function(data){
					console.log('error', data);
				}
			});



		}

	})

})