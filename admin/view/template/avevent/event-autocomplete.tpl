<script>
	addEvent = function(el, event_id='', event_name='', uniq = false) {
		var 
		$event = '',
		event_text = $(el).val();

		if(event_id && event_name) {
			$event += '<li class="event__item event__item-'+event_id+'">';
			$event += event_name;
			$event += '<button type="button" class="event__remove" ><i class="fa fa-close"></i></button>';
			if(uniq) {
				$event += '<input type="hidden" name="event_id" value="'+event_id+'">';
			}else{
				$event += '<input type="hidden" name="event[]" value="'+event_id+'">';
			}
			$event += '</li>';
		}

		$(el).val('');
		if(uniq) {
			$('.event__list').html($event);
		}else{
			$('.event__list').append($event);
		}
		
	}

	/* MULTIMPE companies */
	if($('.event__input').length) {
		document.getElementsByClassName('event__input')[0].onkeypress = function(e) {
			if(e.key=='Enter') {
				e.preventDefault();

				var
				$input = $(this),
				$search  = $input.val();

				if(!$search){return false;}

				$.ajax({
					url: 'index.php?route=avevent/event/existEvent&token=<?php echo $token; ?>&filter_title=' +  encodeURIComponent($search),
					dataType: 'json',
					error: function(json) {
						console.log(json);
					},
					success: function(json) {
						if(json['exist'] && json['event']) {
							if($('.event__item-'+json['event']['event_id']).length) {
								alert('Это мероприятие уже есть в списке!');
							}else{
								addEvent($input, json['event']['event_id'], json['event']['event'], false);
							}
						}else{
							alert('Мероприятие не найдено в базе');
						}
					}
				});

				return false;
			}
		}
	}
	$('input[name=\'event_search\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=avevent/event/autocomplete&token=<?php echo $token; ?>&filter_title=' +  encodeURIComponent(request),
				dataType: 'json',
				error: function(json) {
					console.log(json);
				},
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['event_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			if($('.event__item-'+item.value).length) {
				alert('Это мероприятие уже есть в списке!');
			}else{
				addEvent($('input[name=\'event_search\']'), item.value, item.label, false);
			}
		}
	});
	/* # MULTIMPE companies */


	$(document).on('click', '.event__remove', function(e) {
		e.preventDefault();
		if(confirm('Вы действительно хотите удалить мероприятие?')) {
			$(this).closest('li').remove();
		}
	});
</script>
<style>
	.event__cont{
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 3px;
	}
	.event__cont ul{margin: 0;}
	.event__cont input{
		width: 100%;
		border: none;
		padding: 10px;
		outline: none;
	}
	.event__list{
		margin: 0;
		padding: 0;
		list-style: none;
		font-size: 12px;
	}
	.event__list.sortable .event__item{cursor: grab;}
	.event__item{
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
	.event__item-new{
		background: #8fbb6c;
		color: #fff;
	}
	.event__item button{
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