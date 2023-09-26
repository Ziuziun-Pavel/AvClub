<script>

	addAuthor = function(el, visitor_id='', visitor_name='', visitor_exp=[], uniq = true) {
		var 
		$visitor = '',
		$input = $(el),
		visitor_text = $input.val(),
		input_name = $input.attr('data-name') ? $input.attr('data-name') : '';


		if(visitor_id && visitor_name) {
			$visitor += '<li class="author__item author__item-'+visitor_id+'" >';
			$visitor += visitor_name;

			if(input_name) {
				if(uniq) {
					inp_name_exp = input_name + '[exp_id]';
					inp_name_author = input_name + '[author_id]';
				}else{
					inp_name_exp = input_name + '['+visitor_id+'][exp_id]';
					inp_name_author = input_name + '['+visitor_id+'][author_id]';
				}
			}else{
				if(uniq) {
					inp_name_exp = 'author_exp';
					inp_name_author = 'author_id';
				}else{
					inp_name_exp = 'author_exp['+visitor_id+']';
					inp_name_author = 'author['+visitor_id+']';
				}
			}


			$visitor += '<select class="form-control" name="' + inp_name_exp + '">';
			$visitor += '	<option value="0">-- По-умолчанию --</option>';
			if(visitor_exp.length) {
				$.each(visitor_exp, function(key, value){
					$visitor += '	<option value="'+value.exp_id+'" ' + (key == 0 ? 'selected' : '') + '>'+value.exp+'</option>';
				});
			}
			$visitor += '</select>';
			$visitor += '<button type="button" class="author__remove" ><i class="fa fa-close"></i></button>';
			$visitor += '<input type="hidden" name="' + inp_name_author + '" value="'+visitor_id+'">';
			$visitor += '</li>';
		}

		$input.val('');
		if(uniq) {
			$input.siblings('.author__list').html($visitor);
		}else{
			$input.siblings('.author__list').append($visitor);
		}

	}
	$(function(){

		/* uniq author */
		if($('.author__input').length) {
			let inputs = document.querySelectorAll('.author__input');
			inputs.forEach( (input) => {
				input.onkeypress = function(e) {
					if(e.key=='Enter') {
						e.preventDefault();

						var
						$input = $(this),
						$search  = $input.val();

						if(!$search){return false;}

						$.ajax({
							url: 'index.php?route=visitor/visitor/existVisitor&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($search),
							dataType: 'json',
							error: function(json) {
								console.log(json);
							},
							success: function(json) {
								if(json['exist'] && json['visitor']) {
									if($(input).siblings('.author__list').find('.author__item-'+json['visitor']['visitor_id']).length) {
										alert('Этот автор уже назначен!');
									}else{
										addAuthor(input, json['visitor']['visitor_id'], json['visitor']['visitor'], json['visitor']['exp_list'], true);
									}
								}else{
									alert('Автор не найден в базе');
								}
							}
						});

						return false;
					}
				}
			})
		}

		let searchs = document.querySelectorAll("input[name='author_search']");
		searchs.forEach( (input) => {
			$(input).autocomplete({
				'source': function(request, response) {
					$.ajax({
						url: 'index.php?route=visitor/visitor/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
						dataType: 'json',
						error: function(json) {
							console.log(json);
						},
						success: function(json) {
							response($.map(json, function(item) {
								return {
									label: 		item['name'],
									exp: 			item['exp'],
									value: 		item['visitor_id'],
									exp_list: item['exp_list']
								}
							}));
						}
					});
				},
				'select': function(item) {
					if($(input).siblings('.author__list').find('.author__item-'+item.value).length) {
						alert('Этот автор уже назначен!');
					}else{
						addAuthor(input, item.value, item.label, item.exp_list, true);
					}
				}
			});
		})

		/* # uniq author */

		/* MULTIMPE AUTHORS */
		if($('.authors__input').length) {
			let inputs = document.querySelectorAll('.authors__input');
			inputs.forEach( (input) => {
				input.onkeypress = function(e) {
					if(e.key=='Enter') {
						e.preventDefault();

						var
						$input = $(this),
						$search  = $input.val();

						if(!$search){return false;}

						$.ajax({
							url: 'index.php?route=visitor/visitor/existVisitor&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($search),
							dataType: 'json',
							error: function(json) {
								console.log(json);
							},
							success: function(json) {
								if(json['exist'] && json['visitor']) {
									if($(input).siblings('.author__list').find('.author__item-'+json['visitor']['visitor_id']).length) {
										alert('Этот автор уже есть в списке!');
									}else{
										addAuthor(input, json['visitor']['visitor_id'], json['visitor']['visitor'], json['visitor']['exp_list'], false);
									}
								}else{
									alert('Автор не найден в базе');
								}
							}
						});

						return false;
					}
				}
			})
		}


		let searchs_mult = document.querySelectorAll("input[name='authors_search']");
		searchs_mult.forEach( (input) => {
			$(input).autocomplete({
				'source': function(request, response) {
					$.ajax({
						url: 'index.php?route=visitor/visitor/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
						dataType: 'json',
						error: function(json) {
							console.log(json);
						},
						success: function(json) {
							response($.map(json, function(item) {
								return {
									label: 		item['name'],
									exp: 			item['exp'],
									value: 		item['visitor_id'],
									exp_list: item['exp_list']
								}
							}));
						}
					});
				},
				'select': function(item) {
					if($(input).siblings('.author__list').find('.author__item-'+item.value).length) {
						alert('Этот автор есть в списке!');
					}else{
						addAuthor(input, item.value, item.label, item.exp_list, false);
					}
				}
			});
		})
		/* # MULTIMPE AUTHORS */


		$(document).on('click', '.author__remove', function(e) {
			e.preventDefault();
			if(confirm('Вы действительно хотите удалить автора?')) {
				$(this).closest('li').remove();
			}
		});
		
	})

</script>
<style>
	.author__cont{
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 3px;
	}
	.author__cont ul{margin: 0;}
	.author__cont input{
		width: 100%;
		border: none;
		padding: 10px;
		outline: none;
	}
	.author__list{
		margin: 0;
		padding: 0;
		list-style: none;
		font-size: 12px;
	}
	.author__list.sortable .author__item{cursor: grab;}
	.author__item{
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
	.author__item-new{
		background: #8fbb6c;
		color: #fff;
	}
	.author__item button{
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
	.author__item select{
		margin-top: 5px;
		padding: 0 5px;
		height: 25px;
		font-size: 11px;
	}
</style>