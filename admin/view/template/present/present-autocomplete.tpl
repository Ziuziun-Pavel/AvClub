<script>
	addPresent = function(el, present_id='', present_name='', present_exp='', uniq = false) {
		var 
		$present = '',
		present_text = $(el).val();

		if(present_id && present_name) {
			$present += '<li class="present__item present__item-'+present_id+'">';
			$present += present_name;
			$present += '<button type="button" class="present__remove" ><i class="fa fa-close"></i></button>';
			if(uniq) {
				$present += '<input type="hidden" name="present_id" value="'+present_id+'">';
			}else{
				$present += '<input type="hidden" name="present[]" value="'+present_id+'">';
			}
			$present += '</li>';
		}

		$(el).val('');
		if(uniq) {
			$('.present__list').html($present);
		}else{
			$('.present__list').append($present);
		}
		
	}

	/* MULTIMPE companies */
	if($('.present__input').length) {
		document.getElementsByClassName('present__input')[0].onkeypress = function(e) {
			if(e.key=='Enter') {
				e.preventDefault();

				var
				$input = $(this),
				$search  = $input.val();

				if(!$search){return false;}

				$.ajax({
					url: 'index.php?route=present/present/existPresent&token=<?php echo $token; ?>&filter_title=' +  encodeURIComponent($search),
					dataType: 'json',
					error: function(json) {
						console.log(json);
					},
					success: function(json) {
						if(json['exist'] && json['present']) {
							if($('.present__item-'+json['present']['present_id']).length) {
								alert('Этот анонс уже есть в списке!');
							}else{
								addPresent($input, json['present']['present_id'], json['present']['present'], json['present']['exp'], false);
							}
						}else{
							alert('Анонс не найден в базе');
						}
					}
				});

				return false;
			}
		}
	}
	$('input[name=\'present_search\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=present/present/autocomplete&token=<?php echo $token; ?>&filter_title=' +  encodeURIComponent(request),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['present_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			if($('.present__item-'+item.value).length) {
				alert('Этот анонс уже есть в списке!');
			}else{
				addPresent($('input[name=\'present_search\']'), item.value, item.label, item.exp, false);
			}
		}
	});
	/* # MULTIMPE companies */


	$(document).on('click', '.present__remove', function(e) {
		e.preventDefault();
		if(confirm('Вы действительно хотите удалить анонс?')) {
			$(this).closest('li').remove();
		}
	});
</script>
<style>
	.present__cont{
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 3px;
	}
	.present__cont ul{margin: 0;}
	.present__cont input{
		width: 100%;
		border: none;
		padding: 10px;
		outline: none;
	}
	.present__list{
		margin: 0;
		padding: 0;
		list-style: none;
		font-size: 12px;
	}
	.present__list.sortable .present__item{cursor: grab;}
	.present__item{
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
	.present__item-new{
		background: #8fbb6c;
		color: #fff;
	}
	.present__item button{
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