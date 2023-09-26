addInvalid = function(elem) {
	setTimeout(function(){
		$(elem).addClass('invalid');
	}, 50);
}

$(function(){
	$(document).on('click', '.regform__label', function(e){
		e.preventDefault();
		$(this).siblings('.regform__input').focus();
	})

	
	$('.form__reg_fail').on('submit', function(){
		var
		$form = $(this),
		$data = $form.serialize(),
		$name = $form.find('input[name="name"]'),
		$phone = $form.find('input[name="phone"]').not('.not_req'),
		$comment = $form.find('textarea.req[name="message"]'),
		$rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
		$error = false;


		if($name.length){
			if($name.val().length < 3){
				$name.addClass('invalid'); 
				$error = true;
			}else{
				$name.removeClass('invalid');
			}
		}

		if($comment.length){
			if($comment.val().length < 5){
				$comment.addClass('invalid'); 
				$error = true;
			}else{
				$comment.removeClass('invalid');
			}
		}
		if($phone.length){
			if(!$phone.inputmask('isComplete')){
				$phone.addClass('invalid'); 
				$error = true;
			}else{
				$phone.removeClass('invalid');
			}
		}

		if($error){
			return false;
		}else{
			$.ajax({
				type: "POST", 
				url: "index.php?route=register/register/sendFail", 
				dataType: "json", 
				data: $data,
				beforeSend: function($json) {

				},
				success: function($json){
					if($json['success']){
						$('.form__reg_fail')[0].reset();
						$instance = $.fancybox.getInstance();
						if($instance){$instance.close();}
						showModal('#reg-fail-success', "fancy-vert");
					}

				},
				error: function(){

				}
			});
		}
		return false;
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