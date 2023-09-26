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

expertFilter = function($page = 1){
	var $url = 'index.php?route=expert/list/list&json=1';
	if($page) {
		$url += '&page=' + $page;
	}

	if($('.cosort__list').length) {
		$('.cosort__list').each(function(key, item) {
			var input = $(item).find('input:checked');
			$url += '&' + input.attr('name') + '=' + input.val();
		})
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

	console.log($url);

	$.ajax({
		url: $url,
		type: 'get',
		dataType: 'json',
		data: $form.serialize(),
		beforeSend: function(){
			$('.explist__list').addClass('loading');
		},
		complete: function(){
			setTimeout(function(){
				$('.explist__list').removeClass('loading');
			}, 300);
		},
		success: function(data) {
			if(data['template']) {
				$('.explist__list').html(data['template']);
			}
			setTimeout(function(){
				$('html, body').animate({scrollTop: $('.cofilter__cont').offset().top - $('.section_fixed').outerHeight()}, 800);
			}, 50);
		},
		error: function(data){
			console.log('error', data);
		}
	});

}

$(function(){

	$(document).on('click', '.cosort__btn', function(e){
		e.preventDefault();
		var 
		label = $(this),
		span = label.find('span'),
		input = label.find('input');

		console.log(input.val());

		if(input.val() === 'asc') {
			label.removeClass('--asc').addClass('--desc');
			input.val('desc');
			span.text(label.attr('data-desc'));
		}else{
			label.removeClass('--desc').addClass('--asc');
			input.val('asc');
			span.text(label.attr('data-asc'));
		}

		expertFilter();
	})

	$(document).on('click', '.cofilter__clear_all', function(e){
		e.preventDefault();
		$('.cofilter__cont input[type="text"]').val('');
		expertFilter();
	})

	$('.cofilter__cont').on('submit', function(){
		expertFilter();
		return false;
	})

	$(document).on('click', '.cofilter__delete', function(e){
		e.preventDefault();
		$(this).siblings('input').val('').change();
	})


	$('.cofilter__cont .cofilter__input').on('change', function(){
		expertFilter();
	});

	$('.cofilter__input--auto').each(function(key, input){

		$(input).autocompleteFilter({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=expert/list/autocomplete&' + $(input).attr('name') + '=' +  encodeURIComponent(request),
					dataType: 'json',
					error: function(json) {
						console.log(json);
					},
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['text'],
								value: item['text']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$(input).val(item.value);
				expertFilter();
			}
		});

	})

	$(document).on('change', '.cosort__list input', expertFilter)

	$(document).on('click', '.explist__list .pagination a', function(e){
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
		expertFilter($page);
		

	})
})
