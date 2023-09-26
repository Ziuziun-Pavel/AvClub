<script>
	addBranch = function(el, branch_id='', branch_name='') {
		var 
		$branch = '',
		branch_text = $(el).val();

		if(branch_id && branch_name) {
			$branch += '<li class="branch__item branch__item-'+branch_id+'" value="'+branch_name.toLowerCase()+'">';
			$branch += branch_name;
			$branch += '<button type="button" class="branch__remove" ><i class="fa fa-close"></i></button>';
			$branch += '<input type="hidden" name="branch[]" value="'+branch_id+'">';
			$branch += '</li>';
		}else{
			$branch += '<li class="branch__item branch__item-new" data-toggle="tooltip" value="'+branch_text.toLowerCase()+'" title="Этой отрасли пока нет в базе данных. Она будет создана при сохранении страницы">';
			$branch += branch_text;
			$branch += '<button type="button" class="branch__remove" ><i class="fa fa-close"></i></button>';
			$branch += '<input type="hidden" name="branch_new[]" value="'+branch_text+'">';
			$branch += '</li>';
		}

		$(el).val('');
		$('.branch__list').append($branch);
	}
	document.getElementsByClassName('branch__input')[0].onkeypress = function(e) {
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
					if(json['exist'] && json['branch']) {
						if($('.branch__item-'+json['branch']['branch_id']).length) {
							alert('Добавляемая отрасль уже есть в списке!');
						}else{
							addBranch($input, json['branch']['branch_id'], json['branch']['branch']);
						}
					}else{
						if($('.branch__item[value="'+$search.toLowerCase()+'"]').length) {
							alert('Добавляемая отрасль уже есть в списке!');
						}else if(confirm('Добавляемая отрасль не найдена в базе. Желаете добавить новую отрасль?')) {
							addBranch($input, '', '');
						}
						
					}
				}
			});

			return false;
		}
	}

	$('input[name=\'branch_search\']').autocomplete({
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
			if($('.branch__item-'+item.value).length) {
				alert('Добавляемая отрасль уже есть в списке!');
			}else{
				addBranch($('input[name=\'branch_search\']'), item.value, item.label);
			}
		}
	});

	$(document).on('click', '.branch__remove', function(e) {
		e.preventDefault();
		if(confirm('Вы действительно хотите удалить отрасль из списка?')) {
			$(this).closest('li').remove();
		}
	});
</script>
<style>
	.branch__cont{
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 3px;
	}
	.branch__cont ul{margin: 0;}
	.branch__cont input{
		width: 100%;
		border: none;
		padding: 10px;
		outline: none;
	}
	.branch__list{
		margin: 0;
		padding: 0;
		list-style: none;
		font-size: 12px;
	}
	.branch__item{
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
	.branch__item-new{
		background: #8fbb6c;
		color: #fff;
	}
	.branch__item button{
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