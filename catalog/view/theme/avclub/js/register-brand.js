
$(function(){

	$(document).on('change', '.regbrand__fields input', function(){
		$('.regbrand input[name="b24_company_id"]').val(0);
	});

	$(document).on('click', '#regbrand-search', function(e){
		e.preventDefault();

		var 
		input = $(this).closest('.regbrand__start--inp').find('input[name="brand"]'),
		selectedCountry = $('#country:checked'),
		error = false;

		if(input.val().length < 1 || !selectedCountry.val()) {
			error = true;
		}

		console.log(input.val());
		console.log(selectedCountry.val());

		if(!error) {
			$.ajax({
				type: "POST", 
				url: "index.php?route=register/company/searchCompanies", 
				dataType: "json",
				data: {
					company_name: input.val(),
					country: selectedCountry.val()
				},
				beforeSend: function(json) { $('.reg__load').fadeIn(); },
				complete: function(json) { $('.reg__load').fadeOut(); },
				success: function(json){
					if(json['template']) {
						$('.regbrand').html(json['template']);
					}
				},
				error: function(json){
					console.log('brand search error', json);
				}
			});
		} else {
			$('.regbrand__country--subtitle__error, .regbrand__company--subtitle__error').hide();

			if (!selectedCountry.val() && input.val().length > 1) {
				console.log('1')
				$('.regbrand__country--subtitle__error').text('Выберите название страны').show();
			}else if(input.val().length < 1 && selectedCountry.val()){
				console.log('2')
				$('.regbrand__company--subtitle__error').text('Введите название компании').show();
			}else{
				console.log('3')
				$('.regbrand__company--subtitle__error').text('Введите название компании').show();
				$('.regbrand__country--subtitle__error').text('Выберите название страны').show();
			}
		}

	})

	$(document).on('click', '#regbrand-change', function(e){
		e.preventDefault();

		var 
		search = $(this).attr('data-search');

		$.ajax({
			type: "POST", 
			url: "index.php?route=register/company/changeSearch", 
			dataType: "json", 
			data: {
				'search': search
			},
			beforeSend: function(json) { $('.reg__load').fadeIn(); },
			complete: function(json) { $('.reg__load').fadeOut(); },
			success: function(json){
				if(json['template']) {
					$('.regbrand').html(json['template']);
				}
			},
			error: function(json){
				console.log('brand change error', json);
			}
		});

	})

	$(document).on('click', '.regbrand--choose', function(e){
		e.preventDefault();

		var 
		btn = $(this),
		b24id = $(this).attr('data-id'),
		company_name = $(this).attr('data-name'),
		company_inn = $(this).attr('data-inn');

		$.ajax({
			type: "POST", 
			url: "index.php?route=register/company/chooseCompany", 
			dataType: "json", 
			data: {
				'b24id': b24id,
				'company_name': company_name,
				'company_inn': company_inn,
			},
			beforeSend: function(json) { $('.reg__load').fadeIn(); },
			complete: function(json) { $('.reg__load').fadeOut(); },
			success: function(json){
				if(json['template']) {
					$('.regbrand').html(json['template']);
				}
			},
			error: function(json){
				console.log('brand choose error', json);
			}
		});

	})

	$(document).on('click', '#regbrand-add', function(e){
		e.preventDefault();

		var 
		btn = $(this),
		search = $(this).attr('data-search');

		$.ajax({
			type: "POST", 
			url: "index.php?route=register/company/addNewCompany", 
			dataType: "json", 
			data: 'search=' + search,
			beforeSend: function(json) { $('.reg__load').fadeIn(); },
			complete: function(json) { $('.reg__load').fadeOut(); },
			success: function(json){
				if(json['template']) {
					$('.regbrand').html(json['template']);
				}
			},
			error: function(json){
				console.log('brand add error', json);
			}
		});

	})


});
