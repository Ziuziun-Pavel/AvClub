(function($) {
	$.fn.autocompleteFilter = function(option) {
		return this.each(function() {
			var $this = $(this);
			var $dropdown = $('<ul class="dropdown-menu cofilter__drop" />');
			
			this.timer = null;
			this.items = [];

			$.extend(this, option);

			$this.attr('autocomplete', 'off');

			// Focus
			$this.on('focus', function() {
				this.request();
			});

			// Blur
			$this.on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$this.on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
					this.hide();
					break;
					case 13: // ENTER
					var 
					$list = $this.siblings('.dropdown-menu'),
					$active = $list.find('.active');

					if($active.length) {
						$active.find('a').click();
						this.hide();
						event.preventDefault();
						return false;
					}
					break;
					case 40: // down
					var 
					$list = $this.siblings('.dropdown-menu'),
					$active = $list.find('.active'),
					$first = $list.find('li').first(),
					$last = $list.find('li').last();

					if($active.length) {
						$active.removeClass('active');
						if($active.next().length) {
							$active.next().addClass('active');
						}else{
							$first.addClass('active');
						}
					}else{
						$first.addClass('active');
					}
					break;
					case 38: // up
					var 
					$list = $this.siblings('.dropdown-menu'),
					$active = $list.find('.active'),
					$first = $list.find('li').first(),
					$last = $list.find('li').last();

					if($active.length) {
						$active.removeClass('active');
						if($active.prev().length) {
							$active.prev().addClass('active');
						}else{
							$last.addClass('active');
						}
					}else{
						$last.addClass('active');
					}
					break;
					default:
					this.request();
					break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				var value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $this.position();

				$dropdown.css({
					top: pos.top + $this.outerHeight(),
					left: pos.left
				});

				$dropdown.show();
			}

			// Hide
			this.hide = function() {
				$dropdown.hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {
				var html = '';
				var category = {};
				var name;
				var i = 0, j = 0;

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						// update element items
						this.items[json[i]['value']] = json[i];

						if (!json[i]['category']) {
							// ungrouped items
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						} else {
							// grouped items
							name = json[i]['category'];
							if (!category[name]) {
								category[name] = [];
							}

							category[name].push(json[i]);
						}
					}

					for (name in category) {
						html += '<li class="dropdown-header">' + name + '</li>';

						for (j = 0; j < category[name].length; j++) {
							html += '<li data-value="' + category[name][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[name][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$dropdown.html(html);
			}

			$dropdown.on('click', '> li > a', $.proxy(this.click, this));
			$this.after($dropdown);
		});
	}
})(window.jQuery);

hideLastBranch = function(){
	$('.cofilter__branch').not('.d-none').last().addClass('d-none');
	var 
	$h_item = $('.cofilter__branch').eq(0).outerHeight(true),
	$h_cont = $('.cofilter__bottom').outerHeight(),
	$row = 2;
	if($(window).width() < 768) {	$row = 3;	}

	if($h_item * $row < $h_cont) {
		hideLastBranch();
	}
}
changeViewBranch = function(){
	$('.cofilter__bottom').removeClass('active');


	var 
	$h_item = $('.cofilter__branch').eq(0).outerHeight(true),
	$h_cont = 0,
	$row = 2;

	if($(window).width() < 768) {
		$row = 3;
	}

	$height = $h_item * $row;

	$('.cofilter__branch').removeClass('d-none');
	$('.cofilter__branch_btn').remove();

	$h_cont = $('.cofilter__bottom').outerHeight();

	console.log($height, $h_cont);

	if($height <= $h_cont) {
		$('.cofilter__bottom').append('<div class="cofilter__branch_btn showmore"><svg><use xlink:href="#points" /></svg><svg><use xlink:href="#arr-top" /></svg></div>');

		hideLastBranch();

	}


}	
$(function(){
	if($('.cofilter__branch').length){
		$(window).on('load resize', changeViewBranch);
		$(document).on('click', '.cofilter__branch_btn', function(e){
			e.preventDefault();
			$('.cofilter__bottom').toggleClass('active');
		})
	}

})

companyFilter = function($page = 0){
	var $url = 'index.php?route=company/company/list';
	if($page) {
		$url += '&page=' + $page;
	}
	var
	$form = $('.cofilter__cont');

	$('.cofilter__col').each(function(key, value){
		var $input = $(this).find('input');

		if($input.val().length) {
			$input.closest('.cofilter__col').addClass('active');
		}else{
			$input.closest('.cofilter__col').removeClass('active');
		}
	});

	$.ajax({
		url: $url,
		type: 'get',
		dataType: 'json',
		data: $form.serialize(),
		beforeSend: function(){
			$('.company__content').addClass('loading');
		},
		complete: function(){
			setTimeout(function(){
				$('.company__content').removeClass('loading');
			}, 300);
		},
		success: function(data) {
			console.log(data);
			if(data['template']) {
				$('.company__content').html(data['template']);
			}
			setTimeout(companyBranchView, 200);
		},
		error: function(data){
			console.log('error', data);
		}
	});

}

$(function(){

	$(document).on('click', '.cofilter__clear_all', function(e){
		e.preventDefault();
		$('.cofilter__cont input[name="branch[]"]:checked').prop('checked', false);
		$('.cofilter__cont input[name="alltype"]:checked').prop('checked', false);
		$('.cofilter__cont input[name="type"]').val('');
		// $('.cofilter__cont input[name="tag_id"]').val('');
		$('.cofilter__cont input[name="company"]').val('');
		// $('.cofilter__cont input[name="company_id"]').val('');
		$('.cofilter__cont input[name="brand"]').val('');
		// $('.cofilter__cont input[name="brand_id"]').val('');

		companyFilter();
	})

	$('.cofilter__cont').on('submit', function(){
		companyFilter();
		return false;
	})

	$(document).on('click', '.cofilter__delete', function(e){
		e.preventDefault();
		$(this).siblings('input').val('').change();
	})


	$('input[name="type"], input[name="company"], input[name="brand"], input[name="branch[]"]').on('change', function(){
		companyFilter();
	});
	$('input[name="branch[]"]').on('change', function(){companyFilter();});

	$('input[name="alltype"]').on('change', function(){
		var 
		$self = $(this),
		$id = $self.val(),
		$title = $self.attr('data-title');

		$('.cofilter__cont input[name=\'type\']').val($title);
		// $('.cofilter__cont input[name=\'tag_id\']').val($id);
		companyFilter();

		$instance = $.fancybox.getInstance();
		if($instance){$instance.close();}
	});

	var $url_category = '';
	if($('input[name="company_category_id"]').val().length) {
		$url_category = '&filter_category_id=' + $('input[name="company_category_id"]').val();
	}

	$('.cofilter__cont input[name=\'type\']').autocompleteFilter({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=company/company/autocomplete'+$url_category+'&filter_type=' +  encodeURIComponent(request),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['title'],
							value: item['id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('.alltype-' + item.value).prop('checked', true);
			$('.cofilter__cont input[name=\'type\']').val(item.label);
			// $('.cofilter__cont input[name=\'tag_id\']').val(item.value);
			companyFilter();
		}
	});


	$('.cofilter__cont input[name=\'company\']').autocompleteFilter({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=company/company/autocomplete'+$url_category+'&filter_company=' +  encodeURIComponent(request) + '&filter_type=' +  encodeURIComponent($('.cofilter__cont input[name=\'type\']').val()),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['title'],
							value: item['id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('.cofilter__cont input[name=\'company\']').val(item.label);
			// $('.cofilter__cont input[name=\'company_id\']').val(item.value);
			companyFilter();
		}
	});
	$('.cofilter__cont input[name=\'brand\']').autocompleteFilter({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=company/company/autocomplete'+$url_category+'&filter_brand=' +  encodeURIComponent(request),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['title'],
							value: item['id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('.cofilter__cont input[name=\'brand\']').val(item.label);
			// $('.cofilter__cont input[name=\'brand_id\']').val(item.value);
			companyFilter();
		}
	});



	companyBranchView = function(){
		$('.company__branch-more').remove();
		$('.company__branch ul li.d-none').removeClass('d-none');

		$('.company__branch ul').each(function(key, list){
			var
			$list = $(list),
			$h = $list.find('li').eq(0).outerHeight(true);

			if($list.outerHeight() > $h * 2) {
				$list.append('<li class="company__branch-more"><svg class="ico ico-center"><use xlink:href="#points" /></svg></li>');
				companyBranchHide($list, $h * 2);
			}else{
				$list.addClass('active');
			}
		})
	}
	companyBranchHide = function($list, $height){
		if($list.outerHeight() > $height) {
			$list.find('li').not('.d-none, .company__branch-more').last().addClass('d-none');
			companyBranchHide($list, $height);
		}else{
			$list.addClass('active');
		}
	}

	$(window).on('load resize', companyBranchView);
	$(document).on('click', '.company__content .pagination a', function(e){
		e.preventDefault();
		var 
		value = [], 
		$page = 1, 
		query = $(this).attr('href').split('?');

		if (query[1]) {
			var part = query[1].split('&');
			for (i = 0; i < part.length; i++) {
				var data = part[i].split('=');
				if (data[0] && data[1]) {	value[data[0]] = data[1];	}
			}

			if (value['page']) { $page = value['page']; }
			
		}
		companyFilter($page);
		

	})
})
