<script>
	addTag = function(el, tag_id='', tag_name='') {

		var 
		$tag = '',
		$input = $(el),
		tag_text = $input.val(),
		input_name = $input.attr('data-name') ? $input.attr('data-name') : 'tag[]',
		input_name_new = $input.attr('data-name-new') ? $input.attr('data-name-new') : 'tag_new[]';

		if(tag_id && tag_name) {
			$tag += '<li class="tag__item tag__item-'+tag_id+'" value="'+tag_name.toLowerCase()+'">';
			$tag += tag_name;
			$tag += '<button type="button" class="tag__remove" ><i class="fa fa-close"></i></button>';
			$tag += '<input type="hidden" name="' + input_name + '" value="'+tag_id+'">';
			$tag += '</li>';
		}else{
			$tag += '<li class="tag__item tag__item-new" data-toggle="tooltip" value="'+tag_text.toLowerCase()+'" title="Этого тега пока нет в базе данных. Он будет создан при сохранении страницы">';
			$tag += tag_text;
			$tag += '<button type="button" class="tag__remove" ><i class="fa fa-close"></i></button>';
			$tag += '<input type="hidden" name="' + input_name_new + '" value="'+tag_text+'">';
			$tag += '</li>';
		}

		$input.val('');
		$input.siblings('.tag__list').append($tag);
	}

	let inputs = document.querySelectorAll('.tag__input');
	inputs.forEach( (input) => {
		input.onkeypress = function(e) {
			if(e.key=='Enter') {
				e.preventDefault();

				var
				$input = $(this),
				$search  = $input.val();

				if(!$search){return false;}

				$.ajax({
					url: 'index.php?route=tag/tag/existTag&token=<?php echo $token; ?>&filter_item=' +  encodeURIComponent($search),
					dataType: 'json',
					error: function(json) {
						console.log(json);
					},
					success: function(json) {
						if(json['exist'] && json['tag']) {
							if($(input).siblings('.tag__list').find('.tag__item-'+json['tag']['tag_id']).length) {
								alert('Добавляемый тег уже есть в списке!');
							}else{
								addTag(input, json['tag']['tag_id'], json['tag']['tag']);
							}
						}
						<?php if(!isset($add_status) || $add_status == true) { ?>
							else{
								if($(input).siblings('.tag__list').find('.tag__item[value="'+$search.toLowerCase()+'"]').length) {
									alert('Добавляемый тег уже есть в списке!');
								}else if(confirm('Добавляемый тег не найден в базе. Желаете добавить новый тег?')) {
									addTag(input, '', '');
								}
							}
						<?php } ?>
					}
				});

				return false;
			}
		}
	})

	let searchs = document.querySelectorAll('input[name=\'tag_search\']');
	searchs.forEach( (input) => {
		$(input).autocomplete({
			'source': function(request, response) {

				$.ajax({
					url: 'index.php?route=tag/tag/autocomplete&token=<?php echo $token; ?>&filter_tag=' +  encodeURIComponent(request),
					dataType: 'json',
					error: function(json) {
						console.log(json);
					},
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['tag'],
								value: item['tag_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				if($(input).siblings('.tag__list').find('.tag__item-'+item.value).length) {
					alert('Добавляемый тег уже есть в списке!');
				}else{
					addTag(input, item.value, item.label);
			}
		}
	});
	})

	$(document).on('click', '.tag__remove', function(e) {
		e.preventDefault();
		if(confirm('Вы действительно хотите удалить тег из списка?')) {
			$(this).closest('li').remove();
		}
	});
</script>
<style>
	.tag__cont{
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 3px;
	}
	.tag__cont ul{margin: 0;}
	.tag__cont input{
		width: 100%;
		border: none;
		padding: 10px;
		outline: none;
	}
	.tag__list{
		margin: 0;
		padding: 0;
		list-style: none;
		font-size: 12px;
	}
	.tag__item{
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
	.tag__item-new{
		background: #8fbb6c;
		color: #fff;
	}
	.tag__item button{
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