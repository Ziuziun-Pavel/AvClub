<script>
	$(function(){

		$('input[name="city"]').suggestions({
			token: "b204332cfe2b76ee801c230822401a1f9cd7f07b",
			type: "ADDRESS",
			count: 3,
			constraints: {
				locations: [
				{"country": "Россия"},
				{"country": "Беларусь"},
				{"country": "Казахстан"},
				{"country": "Армения"},
				{"country": "Киргизия"},
				]
			},
			bounds: "city-city",
			restrict_value: true,
			onSelect: function(suggestion) {
				var
				input = $('input[name="city"]'),
				result = suggestion.data;

				input.val(result.city);
			}
		});

		$(document).on('change', '.regform__select--dropdown input', function(){
			var
			select = $(this).closest('.regform__select'),
			input = select.find('input:checked'),
			text = select.find('.regform__select--text');

			text.addClass('valid').find('span').html(input.val());
		})

		$('input[name="company_phone"]').inputmask("+9{0,20}");
		$('input[name="company_sity"]').inputmask("+9{0,20}");

		$('input[name="company"]').on('keyup', function(){
			$('input[name="b24_company_id"]').val(0);
			$('input[name="company_status"]').val('new');
		});


		$(document).on('click', '.regform__find', function(e){
			e.preventDefault();

			if(!$(this).hasClass('add')) {
				$(this).siblings('.regform__input').focus();
			}else{

				$(this).removeClass('add');
				$('.regcompany').show();
				$('input[name="b24_company_id"]').val(0);
				$('input[name="company_status"]').val('new');

			}
		})

		$('input[name=\'company\']').autocompleteVisitorCompany({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=register/register/autocompleteCompanies&filter_company=' +  encodeURIComponent(request) + '&filter_limit=3',
					dataType: 'json',
					error: function(json) {
						console.log(json);
					},
					success: function(json) {
						json.push({
							title: 'Добавить новую компанию',
							id: 'new',
							b24id: 0
						});
						response($.map(json, function(item) {
							return {
								label: item['title'],
								value: item['id'],
								b24id: item['b24id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				if(item.value !== 'new') {
					$('input[name=\'company\']').val(item.label);
				}

				if(item.b24id) {
					$('.regcompany').hide();
					$('input[name="b24_company_id"]').val(item.b24id);
					$('input[name="company_status"]').val(item.b24id);
					$('.regform__find.add').removeClass('add').text('+ Найти');
				}else{
					$('.regcompany').show();
					$('input[name="b24_company_id"]').val(0);
					$('input[name="company_status"]').val('new');
				}

				$('input[name=\'company\']').trigger('change');
			}
		});

	})

</script>