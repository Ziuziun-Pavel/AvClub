<script>
	addBrand = function(el, brand_id='', brand_name='') {
		var 
		$brand = '',
		brand_text = $(el).val();

		if(brand_id && brand_name) {
			$brand += '<li class="brand__item brand__item-'+brand_id+'" value="'+brand_name.toLowerCase()+'">';
			$brand += brand_name;
			$brand += '<button type="button" class="brand__remove" ><i class="fa fa-close"></i></button>';
			$brand += '<input type="hidden" name="brand[]" value="'+brand_id+'">';
			$brand += '</li>';
		}else{
			$brand += '<li class="brand__item brand__item-new" data-toggle="tooltip" value="'+brand_text.toLowerCase()+'" title="Этого бренда пока нет в базе данных. Он будет создан при сохранении страницы">';
			$brand += brand_text;
			$brand += '<button type="button" class="brand__remove" ><i class="fa fa-close"></i></button>';
			$brand += '<input type="hidden" name="brand_new[]" value="'+brand_text+'">';
			$brand += '</li>';
		}

		$(el).val('');
		$('.brand__list').append($brand);
	}
	document.getElementsByClassName('brand__input')[0].onkeypress = function(e) {
		if(e.key=='Enter') {
			e.preventDefault();

			var
			$input = $(this),
			$search  = $input.val();

			if(!$search){return false;}

			$.ajax({
				url: 'index.php?route=company/brand/existBrand&token=<?php echo $token; ?>&filter_item=' +  encodeURIComponent($search),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					if(json['exist'] && json['brand']) {
						if($('.brand__item-'+json['brand']['brand_id']).length) {
							alert('Добавляемый бренд уже есть в списке!');
						}else{
							addBrand($input, json['brand']['brand_id'], json['brand']['brand']);
						}
					}else{
						if($('.brand__item[value="'+$search.toLowerCase()+'"]').length) {
							alert('Добавляемый бренд уже есть в списке!');
						}else if(confirm('Добавляемый бренд не найден в базе. Желаете добавить новый бренд?')) {
							addBrand($input, '', '');
						}
						
					}
				}
			});

			return false;
		}
	}

	$('input[name=\'brand_search\']').autocomplete({
		'source': function(request, response) {

			$.ajax({
				url: 'index.php?route=company/brand/autocomplete&token=<?php echo $token; ?>&filter_title=' +  encodeURIComponent(request),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['brand'],
							value: item['brand_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			if($('.brand__item-'+item.value).length) {
				alert('Добавляемый бренд уже есть в списке!');
			}else{
				addBrand($('input[name=\'brand_search\']'), item.value, item.label);
			}
		}
	});

	$(document).on('click', '.brand__remove', function(e) {
		e.preventDefault();
		if(confirm('Вы действительно хотите удалить бренд из списка?')) {
			$(this).closest('li').remove();
		}
	});
</script>
<style>
	.brand__cont{
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 3px;
	}
	.brand__cont ul{margin: 0;}
	.brand__cont input{
		width: 100%;
		border: none;
		padding: 10px;
		outline: none;
	}
	.brand__list{
		margin: 0;
		padding: 0;
		list-style: none;
		font-size: 12px;
	}
	.brand__item{
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
	.brand__item-new{
		background: #8fbb6c;
		color: #fff;
	}
	.brand__item button{
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