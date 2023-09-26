function getURLVar(key) {
	var value = [];

	var query = document.location.search.split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}
$.fn.isVisible = function () {

	let elementTop = $(this).offset().top;
	let elementBottom = elementTop + $(this).outerHeight();

	let viewportTop = $(window).scrollTop();
	let viewportBottom = viewportTop + $(window).height();

	let flag = elementBottom > viewportTop && elementTop < viewportBottom;

	let item = $(this)

	if(flag) {

		if(item.hasClass('pfl-bg')) {
			let src = item.attr('href');
			$('<img>').attr('src', src).load(function(){
				item.css('background-image', 'url(' + src + ')');
				item.removeClass('pfl-lazy');
				item.removeClass('pfl-bg');
			});
		}
		if(item.hasClass('pfl-lazy')){
			let 
			src = item.attr('data-src');
			item.removeAttr('data-src');

			item.attr('src', src);
			item.on('load', function(){
				item.removeClass('pfl-lazy');
			})
		}
	}
};


winScroll = function($plus_pc = 0, $plus_mob = 0){
	var 
	$flagTop = false,
	$toTop = $('.toTop');
	if($toTop.length) {$flagTop = true;}

	if($(window).width() < 768 && $(window).scrollTop() >= $plus_mob) {
		$('.header').addClass('fixed');
		if($flagTop) {$toTop.addClass('active');}
	}else{
		$('.header').removeClass('fixed');
		if($flagTop) {$toTop.removeClass('active');}
	}

	if($(window).scrollTop() > $('.fixed__header').outerHeight() + 50 + $plus_pc) {
		$('.fixed__item').addClass('active');
		if($flagTop) {$toTop.addClass('active');}
	}else{
		$('.fixed__item').removeClass('active');
		if($flagTop) {$toTop.removeClass('active');}
	}

	if($('.fixed__progress').length) {
		var 
		bar = $('.fixed__progress'),
		$window = $(window),
		docHeight = $(document).height(),
		winHeight = $window.height(),
		baseX = docHeight - winHeight;

		$window.scroll(function(e) {      
			var x = ($window.scrollTop() / baseX) * 100;
			bar.css({'width': + x + '%'});
		});
	}
	

}

showModal = function($href, $class="", $type=""){
	$.fancybox.open({
		src  : $href,
		type : 'inline',
		opts : {
			// baseClass: 'fancy-custom ' + $class,
			baseClass: $class,
			// animationEffect: "zoom-in-out",
			animationEffect: false,
			touch: false,
			afterShow : function( instance, current ) {
				this.opts.animationEffect = true;
				$(current.src).addClass('active');
			},
			beforeClose : function( instance, current ) {
				$(current.src).removeClass('active');
				if(current.src === '#modal_search') {
					$('.search__show.active').removeClass('active');
				}
				if(current.src === '#modal_menu') {
					$('.mmenu__btn.active').removeClass('active');
				}
			},
		}
	});
}

textareainp = function(elem) {
	var 
	html = $(elem).html();
	console.log(html);
	$(elem).siblings('textarea').val($(elem).text()).trigger('change');
}

scrollTo = function(top = 0) {
	$('html, body').animate({scrollTop: top}, 800);
}

var myHash = window.location.hash;
if(myHash != undefined){ 
	location.hash = ''; 
	// scrollTo($(myHash).offset().top);
	$(function(){
		location.hash = myHash; 
	})
};

$(function(){

	$('[contenteditable=true]').keydown(function(e) {
    if (e.keyCode == 13) {
      document.execCommand('insertHTML', false, '\n');
      // return false;
    }
  });

	var 
	$plus_pc = 0,
	$plus_mob = 0;
	if($('.branding__pc').length){$plus_pc = 240;}
	if($('.branding__mob').length){$plus_mob = 240;}
	$(window).on('load scroll resize', function(){
		winScroll($plus_pc, $plus_mob);
	});

	$('input[name="phone"]').inputmask("+9{0,30}");

	if($('a.zoom').length) {
		$('a.zoom').fancybox();
	}
	
	
	$(document).on('click','.search__show', function(e){
		e.preventDefault();

		var $self = $(this);

		if($self.hasClass('active')) {
			$self.removeClass('active');
			$instance = $.fancybox.getInstance();
			if($instance){$instance.close();}
		}else{
			$instance = $.fancybox.getInstance();
			if($instance){$instance.close();}

			$self.addClass('active');

			showModal('#modal_search', 'fancy-from-top fancy-under', 'search');
		}
	})

	$('.search__form').on('submit', function(e){
		e.preventDefault();
		var 
		$form = $(this),
		url = $('base').attr('href') + 'index.php?route=search/search',
		value = $form.find('input[name=\'search\']').val(),
		datavalue = $form.find('input[name=\'search\']').attr('data-value'),
		type = $form.find('input[name=\'search_type\']:checked').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}else if(datavalue) {
			url += '&search=' + encodeURIComponent(datavalue);
		}
		if (type) {
			url += '&search_type=' + encodeURIComponent(type);
		}
		if(value || datavalue) {
			location = url;
		}
		return false;
	})

	$(window).on('resize', function(){
		if($(window).width() > 767 && $('.mmenu__btn').hasClass('active')) {
			$('.mmenu__btn').removeClass('active');
			$instance = $.fancybox.getInstance();
			if($instance){$instance.close();}
		}
	});
	$(document).on('click','.mmenu__btn', function(e){
		e.preventDefault();
		var $self = $(this);
		if($self.hasClass('active')) {
			$self.removeClass('active');
			$instance = $.fancybox.getInstance();
			if($instance){$instance.close();}
		}else{
			
			$instance = $.fancybox.getInstance();
			if($instance){$instance.close();}
			$self.addClass('active');
			showModal('#modal_menu', 'fancy-from-top fancy-under', 'search');
		}
	})


	$(document).on('click','.modalshow', function(e){
		e.preventDefault();
		var 
		$href = $(this).attr('href'),
		$class = "fancy-vert";

		if($href === '#modal_tag') {
			$class = "fancy-from-top fancy-under";
		}

		showModal($href, $class);

	})

	$(document).on('click', '.quest__head', function(e){
		e.preventDefault();
		var
		$self = $(this),
		$item = $self.closest('.quest__item'),
		$text = $self.siblings('.quest__text');

		if($item.hasClass('active')) {
			$item.removeClass('active');
			$text.stop(true, true).slideUp();
		}else{
			$item.addClass('active');
			$text.stop(true, true).slideDown();
		}
	})

	if($('.prg__item').length) {
		$('.prg__item').on('mouseenter', function() {
			var 
			$self = $(this),
			$index = $self.index();
			$('.prg__image-'+$index).addClass('active').siblings().removeClass('active');
		});
		$('.section_prg').on('mouseleave', function() {
			var $self = $(this);
			$('.prg__image img').eq(0).addClass('active').siblings().removeClass('active');
		});
	}

	

	$(document).on('click', '.banner_click', function(e){
		var $banner_id = $(this).attr('data-id');
		if($banner_id) {
			$.ajax({
				type: "POST", 
				url: "index.php?route=themeset/themeset/updateBannerClick", 
				dataType: "json", 
				data: {banner_id: $banner_id}
			});
		}
	})

	$(document).on('click', '.wish', function(e){
		e.stopPropagation();
		e.preventDefault();
		wishlist_change($(this));
		return false;
	})

	$(document).on('click', '.cosort__top', function(){
		var $list = $(this).closest('.cosort__check');
		$list.toggleClass('show');
	})
	window.onclick = function(e) {
		if (!$('.cosort__top').is(e.target) && $('.cosort__check.show .cosort__top').has(e.target).length === 0) {
			$('.cosort__check.show').removeClass('show');
		}
	}
	$(document).on('change', '.cosort__list input', function(e){
		var
		$cont = $(this).closest('.cosort__check'),
		$text = $cont.find('input:checked').siblings('span').html();
		$cont.find('.cosort__top span').html($text);
	})
	
	$('[contenteditable]').on('paste', function (e) { 		
		e.preventDefault();     
		var pastedData = e.originalEvent.clipboardData.getData('text');     
		var selection = window.getSelection().toString();     
		document.execCommand('inserttext', false, pastedData); 
	});

	$(document).on('keyup input change', '[data-input-change]', function(){
		if($(this).val().length > 0) {
			$(this).addClass('valid');
		}else{
			$(this).removeClass('valid');
		}
	});

	// FORM SUBMIT
	$('.letter__form').on('submit', function(){
		var
		$form = $(this),
		$data = $form.serialize(),
		$email = $form.find('input[name="email"]'),
		$agree = $form.find('input[name="agree"]:checked'),
		$rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
		$error = false;
		if($email.length){
			if($email.val().length < 1 || !$rv_email.test($email.val())){
				$email.addClass('invalid');
				$error = true;		
			}else{
				$email.removeClass('invalid');
			}
		}

		if($error){
			return false;
		}else{
			$.ajax({
				type: "POST", 
				url: "index.php?route=themeset/themeset/letter", 
				dataType: "json", 
				data: $data,
				beforeSend: function($json) {

				},
				success: function($json){
					if($json['success']){
						$email.val('');
						$instance = $.fancybox.getInstance();
						if($instance){$instance.close();}
						showModal('#modal_letter_success', "fancy-vert");
					}
				},
				error: function(){

				}
			});
		}
		return false;
	})
	$('.form__order').on('submit', function(){
		var
		$form = $(this),
		$data = $form.serialize(),
		$name = $form.find('input[name="name"]'),
		$company = $form.find('input[name="company"]').not('.not_req'),
		$phone = $form.find('input[name="phone"]').not('.not_req'),
		$email = $form.find('input[name="email"]'),
		$web = $form.find('input.req[name="web"]'),
		$comment = $form.find('textarea.req[name="message"]'),
		$agree = $form.find('input[name="agree"]:checked'),
		$rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
		$error = false;


		if($form.find('input[name="agree"]').length && !$agree.length){
			$form.find('.agree__cont').addClass('invalid');
			$error = true;
		}else{
			$form.find('.agree__cont').removeClass('invalid');
		}

		if($name.length){
			if($name.val().length < 3){
				$name.addClass('invalid'); 
				$error = true;
			}else{
				$name.removeClass('invalid');
			}
		}

		if($web.length){
			if($web.val().length < 4){
				$web.addClass('invalid'); 
				$error = true;
			}else{
				$web.removeClass('invalid');
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

		if($company.length){
			if($company.val().length < 2){
				$company.addClass('invalid'); 
				$error = true;
			}else{
				$company.removeClass('invalid');
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
		if($email.length){
			if($email.val().length < 1 || !$rv_email.test($email.val())){
				$email.addClass('invalid');
				$error = true;		
			}else{
				$email.removeClass('invalid');
			}
		}

		if($error){
			return false;
		}else{
			$.ajax({
				type: "POST", 
				url: "index.php?route=themeset/themeset/send", 
				dataType: "json", 
				data: $data,
				beforeSend: function($json) {

				},
				success: function($json){
					if($json['success']){
						$('.form__order')[0].reset();
						$instance = $.fancybox.getInstance();
						if($instance){$instance.close();}
						showModal('#modal_success', "fancy-vert");
					}

				},
				error: function(){

				}
			});
		}
		return false;
	})
	$('.form__file').on('click', function() {
		var node = this;

		$('#form-upload').remove();

		$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

		$('#form-upload input[name=\'file\']').trigger('click');

		if (typeof timer != 'undefined') {
			clearInterval(timer);
		}
		timer = setInterval(function() {
			if ($('#form-upload input[name=\'file\']').val() != '' ) {
				clearInterval(timer);

				$.ajax({
					url: 'index.php?route=themeset/themeset/upload',
					type: 'post',
					dataType: 'json',
					data: new FormData($('#form-upload')[0]),
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function() {
						$('.preloader').css('z-index', '9999999').fadeIn();
					},
					complete: function() {
						$('.preloader').fadeOut();
					},
					success: function(json) {

						if (json['error']) {
							alert(json['error']);
						}

						if (json['success']) {
							$(node).find('.file__upload span').html(json['filename']);
							$(node).find('input').attr('value', json['code']);
						}
					},
					error: function(json) {
						console.log(json);
					}
				});
			}
		}, 500);
	});
	// # FORM SUBMIT


	// GO TO 
	$(document).on('click', '.goTo', function(e){
		e.preventDefault();
		var $top = 0;

		if($(this).hasClass('toTop')) {
			$top = 0;
		}else{
			var 
			$target = $(this).attr('href');
			$top = $($target).offset().top;
		}
		
		scrollTo($top);
		return false;
	});

	if($('.asl__cont').length) {
		let aslMain = [];
		let aslThumb = [];

		$('.asl__cont').each(function(key, item){
			var slideMain = $(item).find('.asl__main');
			var slideThumb = $(item).find('.asl__thumb');

			slideMain.addClass('asl__main-' + key);
			slideThumb.addClass('asl__thumb-' + key);

			aslMain[key] = slideMain.slick({
				infinite: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				asNavFor: '.asl__thumb-'+key,
				dots: false,
				arrows: false
			});


			aslThumb[key] = slideThumb.slick({
				infinite: true,
				slidesToShow: 4,
				slidesToScroll: 1,
				prevArrow: '<button type="button" class="slick-prev nav__item nav__prev"><svg><use xlink:href="#arrow-left" /></svg></button>',
				nextArrow: '<button type="button" class="slick-next nav__item nav__next"><svg><use xlink:href="#arrow-right" /></svg></button>',
				asNavFor: '.asl__main-'+key,
				focusOnSelect: true,
				responsive: [
				{
					breakpoint: 992,
					settings: {
						slidesToShow: 4,
					},
				},
				{
					breakpoint: 510,
					settings: {
						slidesToShow: 3,
					},
				}
				]
			});
		});

		/*$(window).on('resize', function(){
			$.each(prSlides, function(key,item) {
				prSlides[key].slick('slickGoTo', 0);
				prSlides[key].slick('setPosition');
			});
		})*/
	}

	/*if($('.slider__slider').length) {
		$('.slider__slider').on('init', function(event, slick){
			$(this).find('.slick-active').addClass('slider__active');
		});
		var topSlider = $('.slider__slider').slick({
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			dots: true,
			fade: true,
			cssEase: 'linear',
			autoplay: true,
			autoplaySpeed: 5000,
			prevArrow: '.slider__nav .nav__prev',
			nextArrow: '.slider__nav .nav__next',
			appendDots: '.slider__nav .nav__dots'
		}).on('afterChange', function (event, slick, currentSlide, nextSlide) {
			$(slick.$list[0]).find('.slider__active').removeClass('slider__active');
			$(slick.$slides[currentSlide]).addClass('slider__active');
		});

	}*/

});

function wishlist_change($element, $url = '', $action = ''){

	var
	$journal_id = $($element).attr('data-id'),
	$label = $('.wish-'+$journal_id);


	if($($element).hasClass('active') || $action == 'remove'){
		$.ajax({
			url: 'index.php?route=account/wishlist/remove',
			type: 'post',
			data: 'journal_id=' + $journal_id + '&url=' + $url,
			dataType: 'json',
			success: function(json) {
				if (json['redirect']) {
					location = json['redirect'];
				}

				var now_location = String(document.location.pathname);

				if ((now_location == '/wishlist/') || (getURLVar('route') == 'account/wishlist')) {
					$label.closest('.news__outer').remove();

					$.each($('.wish__tab'), function(key,value){
						var $filter = $(value).attr('data-filter');
						if($filter && !$('.filter-'+$filter).length) {
							$(value).closest('li').remove();
							$('.wish__tab').eq(0).trigger('click');
						}
					})

					if(!$('.filter__item').length) {
						$('.wish__tabs').remove();
						$('.filter__empty').removeClass('d-none');
					}
				}
				
				$label.removeClass('active');
				$.each($label, function(key, value){
					if($(value).find('span').length) {
						$(value).find('span').text($(value).attr('data-passive'));
					}
				})
				if(json['total_count'] == 0) {
					$('.hlinks__wish a').removeClass('active');
				}
			},
			error: function(json) {
				console.log(json);
			}
		});
	}else{
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'journal_id=' + $journal_id,
			dataType: 'json',
			success: function(json) {
				if (json['redirect']) {
					location = json['redirect'];
				}
				if (json['success']) {

					$label.addClass('active');
					$.each($label, function(key, value){
						if($(value).find('span').length) {
							$(value).find('span').text($(value).attr('data-active'));
						}
					})
					$('.hlinks__wish a').addClass('active');
				}
				
				$label.addClass('active');
			},
			error: function(json) {
				console.log(json);
			}
		});
	}
}
