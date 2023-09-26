<script>
	addFile = function(el, download_id='', file_name='', uniq = true) {
		var 
		$visitor = '',
		visitor_text = $(el).val();

		if(download_id && file_name) {
			$visitor += '<li class="file__item file__item-'+download_id+'"">';
			$visitor += file_name;
			$visitor += '<button type="button" class="file__remove" ><i class="fa fa-close"></i></button>';
			if(uniq) {
				$visitor += '<input type="hidden" name="file_id" value="'+download_id+'">';
			}else{
				$visitor += '<input type="hidden" name="files[]" value="'+download_id+'">';
			}
			$visitor += '</li>';
		}

		$(el).val('');
		if(uniq) {
			$('.file__list').html($visitor);
		}else{
			$('.file__list').append($visitor);
		}
		
	}

	/* uniq file */
	if($('.file__input').length) {
		document.getElementsByClassName('file__input')[0].onkeypress = function(e) {
			if(e.key=='Enter') {
				e.preventDefault();

				var
				$input = $(this),
				$search  = $input.val();

				if(!$search){return false;}

				$.ajax({
					url: 'index.php?route=catalog/download/existDownload&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($search),
					dataType: 'json',
					error: function(json) {
						console.log(json);
					},
					success: function(json) {
						if(json['exist'] && json['visitor']) {
							if($('.file__item-'+json['visitor']['download_id']).length) {
								alert('Этот файл уже назначен!');
							}else{
								addFile($input, json['visitor']['download_id'], json['visitor']['visitor'], json['visitor']['exp'], true);
							}
						}else{
							alert('Файл не найден в базе');
						}
					}
				});

				return false;
			}
		}
	}

	$('input[name=\'file_search\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							exp: item['exp'],
							value: item['download_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			if($('.file__item-'+item.value).length) {
				alert('Этот файл уже назначен!');
			}else{
				addFile($('input[name=\'file_search\']'), item.value, item.label, item.exp, true);
			}
		}
	});
	/* # uniq file */

	/* MULTIMPE file */
	if($('.files__input').length) {
		document.getElementsByClassName('files__input')[0].onkeypress = function(e) {
			if(e.key=='Enter') {
				e.preventDefault();

				var
				$input = $(this),
				$search  = $input.val();

				if(!$search){return false;}

				$.ajax({
					url: 'index.php?route=catalog/download/existDownload&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($search),
					dataType: 'json',
					error: function(json) {
						console.log(json);
					},
					success: function(json) {
						if(json['exist'] && json['download']) {
							if($('.file__item-'+json['download']['download_id']).length) {
								alert('Этот файл уже есть в списке!');
							}else{
								addFile($input, json['download']['download_id'], json['visitor']['visitor'], json['visitor']['exp'], false);
							}
						}else{
							alert('Файл не найден в базе');
						}
					}
				});

				return false;
			}
		}
	}
	$('input[name=\'files_search\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['download_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			if($('.file__item-'+item.value).length) {
				alert('Этот файл есть в списке!');
			}else{
				addFile($('input[name=\'files_search\']'), item.value, item.label, false);
			}
		}
	});
	/* # MULTIMPE file */

	$(document).on('click', '.file__remove', function(e) {
		e.preventDefault();
		if(confirm('Вы действительно хотите удалить файл?')) {
			$(this).closest('li').remove();
		}
	});
</script>
<style>
	.file__cont{
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 3px;
	}
	.file__cont ul{margin: 0;}
	.file__cont input{
		width: 100%;
		border: none;
		padding: 10px;
		outline: none;
	}
	.file__list{
		margin: 0;
		padding: 0;
		list-style: none;
		font-size: 12px;
	}
	.file__list.sortable .file__item{cursor: grab;}
	.file__item{
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
	.file__item-new{
		background: #8fbb6c;
		color: #fff;
	}
	.file__item button{
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