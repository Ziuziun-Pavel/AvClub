addInvalid = function(elem) {
	setTimeout(function(){
		$(elem).addClass('invalid');
	}, 50);
}

$(function(){


	$('.regphone__inp input[name="telephone"]').inputmask("+9{0,20}");

	$(document).on('click', '.regform__label', function(e){
		e.preventDefault();
		$(this).siblings('.regform__input').focus();
	})

	$(document).on('submit', '#registration-number', function(e){
		e.preventDefault();
		var
		form = $(this),
		data = $(this).serialize(),
		telephone = form.find('input[name="telephone"]'),
		email = form.find('input[name="email"]'),
		$rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
		mess = form.find('.reg__error'),
		error = false;

		form.find('.invalid').removeClass('invalid');


		if(email.length && email.is(':visible')){
			if(email.val().length < 1 || !$rv_email.test(email.val())){
				addInvalid(email.closest('.regform__inp'));
				error = true;		
			}
		}

		if(!telephone.inputmask('isComplete')) {
			addInvalid(telephone);
			error = true;
		}

		if(!error) {
			$.ajax({
				type: "POST", 
				url: "index.php?route=register/login/authorize", 
				dataType: "json", 
				data: form.serialize(),
				beforeSend: function(json) { $('.reg__load').fadeIn(); },
				complete: function(json) { $('.reg__load').fadeOut(); },
				success: function(json){
					if(json['template']) {
						$('.regdata').html(json['template']);
					}
					if(json['error']) {
						mess.text(json['error']);
						if(mess.is(':hidden')) {
							mess.stop(true, true).slideDown();
						}

						if(json['show_email']) {
							form.find('.regphone__email').stop(true, true).slideDown();
						}

					}else if(mess.is(':visible')) {
						mess.stop(true, true).slideUp();
					}
				},
				error: function(json){
					console.log('authorize error', json);
				}
			});
		}

		return false;
	});

	$(document).on('submit', '#registration-code', function(e){
		e.preventDefault();
		var
		form = $(this),
		data = $(this).serialize(),
		code = form.find('input[name="code"]'),
		mess = form.find('.reg__error'),
		error = false;

		$.ajax({
			type: "POST", 
			url: "index.php?route=register/login/inputCode", 
			dataType: "json", 
			data: form.serialize(),
			beforeSend: function(json) { $('.reg__load').fadeIn(); },
			complete: function(json) {  },
			success: function(json){
				if(json['reload']) {
					location.reload();
				}
				if(json['template']) {
					$('.regdata').html(json['template']);
					$('.reginfo').addClass('short');
				}
				if(json['error']) {
					code.val('');
					mess.text(json['error']);
					if(mess.is(':hidden')) {
						mess.stop(true, true).slideDown();
					}
				}else if(mess.is(':visible')) {
					mess.stop(true, true).slideUp();
				}


				if(json['redirect']) {
					location = json['redirect'].replace("&amp;", "&");
				}else{
					$('.reg__load').fadeOut();
				}
			},
			error: function(json){
				console.log('inputCode', json);
			}
		});
	})

	$(document).on('click', '#button-save', function(e){
		e.preventDefault();
		var
		form = $(this).closest('form'),
		data = form.serialize(),
		$name = form.find('input[name="name"]'),
		$lastname = form.find('input[name="lastname"]'),
		$company = form.find('input[name="company"]'),
		$email = form.find('input[name="email"]'),
		$post = form.find('input[name="post"]'),
		$rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
		error = false,
		error_company = false,
		error_text = '';

		$('.invalid').removeClass('invalid');


		if($email.length){
			if($email.val().length < 1 || !$rv_email.test($email.val())){
				addInvalid($email.closest('.regform__inp'));
				error = true;
				error_text = 'Ошибка почты';
			}
		}

		if ($post.length) {
			if ($post.val().length < 1) {
				addInvalid($post.closest('.regform__inp'));
				error = true;
				console.log('error2');
			}
		}

		if ($name.length) {
			if ($name.val().length < 1) {
				addInvalid($name.closest('.regform__inp'));
				error = true;
				console.log('error3');
			}
		}

		if ($lastname.length) {
			if ($lastname.val().length < 1) {
				addInvalid($lastname.closest('.regform__inp'));
				error = true;
				console.log('error4');
			}
		}

		var input_arr = [];
		var company_arr = [];

		if($name.length){input_arr.push($name);}
		if($lastname.length){input_arr.push($lastname);}

		$.each(input_arr, function(key, item){
			if(item.val().length < 2 ){
				addInvalid(item.closest('.regform__inp'));
				error = true;
				error_text = 'Ошибка ' + item.attr('name');
			}
		})

		if(!form.find('input[name="b24_company_old_id"]').length || !form.find('input[name="b24_company_id"]').length) {
			addInvalid(this.closest('#regbrand-search'));
			form.find('.error-message').show();
			console.log('error4');
			error = true;
			error_company = true;
		}else{
			if (!form.find('input[name="city"]').hasClass('noedit')) {
				company_arr.push(form.find('input[name="city"]'));
			}
			if (form.find('input[name="company_phone"]') && !form.find('input[name="company_phone"]').hasClass('noedit')) {
				company_arr.push(form.find('input[name="company_phone"]'));
			}
			if (!form.find('input[name="company_site"]').hasClass('noedit')) {
				company_arr.push(form.find('input[name="company_site"]'));
			}
			var company_activity = form.find('input[name="company_activity"]').closest('.regform__inp');
			if (!form.find('.regform__select--text').hasClass('noedit') && $('.regform__select--text span').html().trim() === '') {
				addInvalid(company_activity);
				error = true;
				console.log('error5');
				error_company = true;
			}

			$.each(company_arr, function(key, item){
				if(item.val().length < 2 ){
					addInvalid(item.closest('.regform__inp'));
					error = true;
					error_company = true;
				}
			})
		}

		if(error_company) {
			addInvalid($('.regbrand'));
		}

		/*if(form.find('input[name="company_status"]').val() === 'new') {
			company_arr.push(form.find('input[name="city"]'));
			company_arr.push(form.find('input[name="company_phone"]'));
			company_arr.push(form.find('input[name="company_site"]'));

			if(!form.find('input[name="company_activity[]"]:checked').length) {
				addInvalid($('.regcompact__title'));
				error = true;
				error_company = true;
				error_text = 'Ошибка - активность компании';
			}
		}
		$.each(company_arr, function(key, item){
			if(item.val().length < 2 ){
				addInvalid(item.closest('.regform__inp'));
				error = true;
				error_company = true;
				error_text = 'Ошибка - поле компании - ' + item.attr('name');
			}
		})

		if($company.val().length < 2 || error_company ){
			addInvalid($company.closest('.regform__inp'));
			error = true;
			error_company = true;
			error_text = 'Ошибка - название компании или поле компании';
		}*/

		if(!error) {

			$.ajax({
				type: "POST",
				url: "index.php?route=register/login/saveData",
				dataType: "json",
				data: form.serialize(),
				beforeSend: function(json) { $('.reg__load').fadeIn(); },
				complete: function(json) { $('.reg__load').fadeOut(); },
				success: function(json){
					if(json['reload']) {
						location.reload();
					}
					if(json['redirect']) {
						location = json['redirect'].replace("&amp;", "&");
					}
					if(json['template']) {
						$('.regdata').html(json['template']);
					}

				},
				error: function(json){
					console.log('save data', json);
				}
			});
		}
	})


});


(function($) {
	$.fn.autocompleteVisitorCompany = function(option) {
		return this.each(function() {
			var $this = $(this);
			var $dropdown = $('<ul class="dropdown-menu regform__drop" />');
			
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
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '<span>Выбрать</span></a></li>';
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