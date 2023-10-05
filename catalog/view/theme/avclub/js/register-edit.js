
$(function(){
	var initialFormState = $('#form-edit-expert').find('input:not([type="hidden"]), textarea').serialize();
	var initialFormProfileState = $('#form-edit-expert').find('.profile__edit input, textarea').serialize();
	var initialCompany = $('.regbrand__result--title').text();
	var isProfileEdit = false;
	var isCompanyChange = false;

	$(document).on('change', '#form-edit-expert input, #form-edit-expert textarea', function () {
		var serializedForm = $('#form-edit-expert').find('input:not([type="hidden"]), textarea').serialize();

		isProfileEdit = checkProfileEditChanges();
		isCompanyChange = checkCompanyAfterFormSubmission();

		// Проверяем, изменилось ли состояние формы
		if (serializedForm !== initialFormState || checkCompanyAfterFormSubmission()) {
			$('#button-edit-save').prop('disabled', false);
		} else {
			$('#button-edit-save').prop('disabled', true);
		}
	});

	// Функция для определения, были ли изменения внутри блока profile__edit
	function checkProfileEditChanges() {
		var profileEditForm = $('#form-edit-expert');

		// Сериализируем видимые инпуты и textarea внутри блока profile__edit
		var serializedFormInsideProfileEdit = profileEditForm.find('.profile__edit input, textarea').serialize();
		// var serializedFormInsideProfileEdit1 = profileEditForm.find('.profile__edit textarea').serialize();

		// Проверяем, изменилось ли состояние формы
		return serializedFormInsideProfileEdit !== initialFormProfileState;
	}

	// Функция для сравнения текста после отправки формы
	function checkCompanyAfterFormSubmission() {
		var currentCompanyAfterSubmission = $('.regbrand__result--title').text();

		return currentCompanyAfterSubmission !== initialCompany;
	}

	$(document).on('click', '#button-edit-save', function(e){
		e.preventDefault();
		var
		form = $(this).closest('form'),
		data = form.serialize(),
		$name = form.find('input[name="name"]'),
		$lastname = form.find('input[name="lastname"]'),
		$company = form.find('input[name="company"]'),
		$phone = form.find('input[name="telephone"]'),
		$email = form.find('input[name="email"]'),
		$rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
		warning = form.find('.regform__warning'),
		error = false,
		error_company = false;


		$('.invalid').removeClass('invalid');
		warning.stop(true,true).slideUp();

		if($email.length){
			if($email.val().length < 1 || !$rv_email.test($email.val())){
				addInvalid($email.closest('.regform__inp'));
				error = true;		
			}
		}

		var input_arr = [];
		var company_arr = [];

		if($name.length){input_arr.push($name);}
		if($lastname.length){input_arr.push($lastname);}
		if($phone.length){input_arr.push($phone);}

		$.each(input_arr, function(key, item){
			if(item.val().length < 2 ){
				addInvalid(item.closest('.regform__inp'));
				error = true;		
			}
		})

		if(!form.find('input[name="b24_company_old_id"]').length || !form.find('input[name="b24_company_id"]').length) {
			error = true;
			error_company = true;		
		}else{
			company_arr.push(form.find('input[name="city"]'));
			company_arr.push(form.find('input[name="company_phone"]'));
			company_arr.push(form.find('input[name="company_site"]'));
			var company_activity = form.find('input[name="company_activity"]').closest('.regform__inp');
			if(!form.find('input[name="company_activity"]:checked').length) {
				addInvalid(company_activity);
				error = true;
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
			}
		}
		$.each(company_arr, function(key, item){
			if(item.val().length < 2 ){
				addInvalid(item.closest('.regform__inp'));
				error = true;		
				error_company = true;		
			}
		})

		if($company.val().length < 2 || error_company ){
			addInvalid($company.closest('.regform__inp'));
			error = true;		
			error_company = true;		
		}*/


		if(!error) {
			var ajaxData = form.serialize();
			ajaxData += '&isProfileEdit=' + isProfileEdit;
			ajaxData += '&isCompanyChanged=' + isCompanyChange;

			$.ajax({
				type: "POST",
				url: "index.php?route=register/edit/saveData",
				dataType: "json",
				data: ajaxData,
				beforeSend: function(json) { $('.reg__load').fadeIn(); },
				complete: function(json) {  },
				success: function(json){
					if(json['reload']) {
						location.reload();
					}
					if(json['redirect']) {
						location = json['redirect'].replace("&amp;", "&");
					}
					if(json['error_text']) {
						warning.find('.regform__warning--text').text(json['error_text']);
						setTimeout(function(){
							warning.stop(true,true).slideDown();
						}, 50);
					}

					if(json['error_text']) {
						$('.reg__load').fadeIn();
					}

				},
				error: function(json){
					console.log('save data', json);
				}
			});
		}
	})

	/* IMAGE CROP */
	$('.edit__image--image').on('click', function(e){
		e.preventDefault();
		showModal($(this).attr('href'), 'fancy-standart');
	})
	$uploadCrop = $('#upload-demo').croppie({
		enableExif: true,
		showZoomer: false,
		viewport: {
			width: $(window).width() > 500 ? 365 : 220,
			height: $(window).width() > 500 ? 365 : 220,
			type: 'circle'
		}
	});

	$('#edit-image-file').on('change', function (e) {
		var input = this; 

		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('.imgedit').addClass('uploaded');
				$uploadCrop.croppie('bind', {
					url: e.target.result
				});
			}
			reader.readAsDataURL(input.files[0]);
		} else {
			alert("Ваш браузер не поддерживает редактирование фото");
		}
	});

	$('#edit-image-save').on('click', function (ev) {
		$uploadCrop.croppie('result', {
			type: 'canvas',
			size: 'viewport'
		}).then(function (resp) {
			$('#edit-image').attr('src', resp);
			$('#form-edit-expert textarea[name="photo"]').val(resp).trigger('change');
			$.fancybox.close();
		});
	});

	$('#edit-image-delete').on('click', function(e){
		e.preventDefault();
		var
		image = $('#edit-image');

		image.attr('src', image.attr('data-placeholder'));
		$('#form-edit-expert textarea[name="photo"]').val('delete').trigger('change');
	})

	$('#edit-image-cancel').on('click', function(e){
		e.preventDefault();
		$('.imgedit').removeClass('uploaded');
		$.fancybox.close();
	})


});
