
$(function(){

	/* IMAGE CROP */
	$('.edit__image--image').on('click', function(e){
		e.preventDefault();
		showModal($(this).attr('href'), 'fancy-standart');
	})
	$uploadCrop = $('#upload-demo').croppie({
		enableExif: true,
		showZoomer: false,
		viewport: {
			width: $(window).width() > 500 ? 336 : 168,
			height: $(window).width() > 500 ? 146 : 73
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
			$('.edit__image').addClass('uploaded');
			$('#edit-image').attr('src', resp);
			$('#form-edit-company textarea[name="photo"]').val(resp).trigger('change');
			$.fancybox.close();
		});
	});

	$('#edit-image-delete').on('click', function(e){
		e.preventDefault();
		var
		image = $('#edit-image');

		image.attr('src', image.attr('data-placeholder'));
		$('#form-edit-company textarea[name="photo"]').val('delete').trigger('change');
	})

	$('#edit-image-cancel').on('click', function(e){
		e.preventDefault();
		$('.imgedit').removeClass('uploaded');
		$('.edit__image').removeClass('uploaded');
		$.fancybox.close();
	})


});
