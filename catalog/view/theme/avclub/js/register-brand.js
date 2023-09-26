
$(function(){

	$(document).on('change', '.regbrand__fields input', function(){
		$('.regbrand input[name="b24_company_id"]').val(0);
	});

	$(document).on('click', '#regbrand-search', function(e){
		e.preventDefault();

		var 
		input = $(this).closest('.regbrand__start--inp').find('input[name="brand"]'),
		error = false;

		if(input.val().length < 1) {
			error = true;
		}

		if(!error) {
			$.ajax({
				type: "POST", 
				url: "index.php?route=register/company/searchCompanies", 
				dataType: "json", 
				data: 'company_name=' + input.val(),
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
			data: 'search=' + search,
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
		b24id = $(this).attr('data-id');

		$.ajax({
			type: "POST", 
			url: "index.php?route=register/company/chooseCompany", 
			dataType: "json", 
			data: 'b24id=' + b24id,
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
