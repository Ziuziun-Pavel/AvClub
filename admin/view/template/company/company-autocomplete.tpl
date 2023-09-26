<script>
	addCompany = function(el, company_id='', company_name='', uniq = false) {
		var 
		$company = '',
		company_text = $(el).val();

		if(company_id && company_name) {
			$company += '<li class="company__item company__item-'+company_id+'">';
			$company += company_name;
			$company += '<button type="button" class="company__remove" ><i class="fa fa-close"></i></button>';
			if(uniq) {
				$company += '<input type="hidden" name="company_id" value="'+company_id+'">';
			}else{
				$company += '<input type="hidden" name="company[]" value="'+company_id+'">';
			}
			$company += '</li>';
		}

		$(el).val('');
		if(uniq) {
			$('.company__list').html($company);
		}else{
			$('.company__list').append($company);
		}
		
	}

	/* MULTIMPE companies */
	if($('.companies__input').length) {
		document.getElementsByClassName('companies__input')[0].onkeypress = function(e) {
			if(e.key=='Enter') {
				e.preventDefault();

				var
				$input = $(this),
				$search  = $input.val();

				if(!$search){return false;}

				$.ajax({
					url: 'index.php?route=company/company/existCompany&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($search),
					dataType: 'json',
					error: function(json) {
						console.log(json);
					},
					success: function(json) {
						if(json['exist'] && json['company']) {
							if($('.company__item-'+json['company']['company_id']).length) {
								alert('Эта компания уже есть в списке!');
							}else{
								addCompany($input, json['company']['company_id'], json['company']['company'], json['company']['exp'], false);
							}
						}else{
							alert('Компания не найдена в базе');
						}
					}
				});

				return false;
			}
		}
	}
	$('input[name=\'companies_search\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=company/company/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['company_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			if($('.company__item-'+item.value).length) {
				alert('Эта компания уже есть в списке!');
			}else{
				addCompany($('input[name=\'companies_search\']'), item.value, item.label, item.exp, false);
			}
		}
	});
	/* # MULTIMPE companies */

	/* UNIQ companies */
	if($('.company__input').length) {
		document.getElementsByClassName('company__input')[0].onkeypress = function(e) {
			if(e.key=='Enter') {
				e.preventDefault();

				var
				$input = $(this),
				$search  = $input.val();

				if(!$search){return false;}

				$.ajax({
					url: 'index.php?route=company/company/existCompany&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($search),
					dataType: 'json',
					error: function(json) {
						console.log(json);
					},
					success: function(json) {
						if(json['exist'] && json['company']) {
							if($('.company__item-'+json['company']['company_id']).length) {
								alert('Эта компания уже есть в списке!');
							}else{
								addCompany($input, json['company']['company_id'], json['company']['company'], true);
							}
						}else{
							alert('Компания не найдена в базе');
						}
					}
				});

				return false;
			}
		}
	}
	$('input[name=\'company_search\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=company/company/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['company_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			if($('.company__item-'+item.value).length) {
				alert('Эта компания уже есть в списке!');
			}else{
				addCompany($('input[name=\'company_search\']'), item.value, item.label, true);
			}
		}
	});
	/* # UNIQ companies */



	$(document).on('click', '.company__remove', function(e) {
		e.preventDefault();
		if(confirm('Вы действительно хотите удалить компанию?')) {
			$(this).closest('li').remove();
		}
	});
</script>
<style>
	.company__cont{
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 3px;
	}
	.company__cont ul{margin: 0;}
	.company__cont input{
		width: 100%;
		border: none;
		padding: 10px;
		outline: none;
	}
	.company__list{
		margin: 0;
		padding: 0;
		list-style: none;
		font-size: 12px;
	}
	.company__list.sortable .company__item{cursor: grab;}
	.company__item{
		display: inline-block;
		vertical-align: top;
		float: left;
		margin: 0 2px 4px;
		color: #000;
		background-color: #f0f0f0;
		border-color: #f0f0f0;
		padding: 7px 30px 7px 10px;
		border-radius: 2px;
		position: relative;
	}
	.company__item-new{
		background: #8fbb6c;
		color: #fff;
	}
	.company__item button{
		padding: 0;
		display: inline-block;
		vertical-align: top;
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		width: 30px;
		border: none;
		background: transparent;
		font-size: 10px;
	}
</style>