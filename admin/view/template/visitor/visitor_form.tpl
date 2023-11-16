<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-visitor" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
				<h1><?php echo $heading_title; ?></h1>
				<ul class="breadcrumb">
					<?php foreach ($breadcrumbs as $breadcrumb) { ?>
						<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div class="container-fluid">
			<?php if ($error_warning) { ?>
				<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-visitor" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-fields" data-toggle="tab"><?php echo $tab_fields; ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">

							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
								<div class="col-sm-10">
									<input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
									<?php if ($error_firstname) { ?>
										<div class="text-danger"><?php echo $error_firstname; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
								<div class="col-sm-10">
									<input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
									<?php if ($error_lastname) { ?>
										<div class="text-danger"><?php echo $error_lastname; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_company; ?></label>
								<div class="col-sm-10">
									<div class="company__cont">
										<ul class="company__list ">
											<?php if(!empty($company)) { ?>
												<li class="company__item company__item-<?php echo $company['company_id']; ?>" >
													<?php echo $company['title']; ?>
													<button type="button" class="company__remove" ><i class="fa fa-close"></i></button>
													<input type="hidden" name="company_id" value="<?php echo $company['company_id']; ?>">
												</li>
											<?php } ?>
										</ul>
										<input type="text" name="company_search" class="company__input" placeholder="<?php echo $entry_company_placeholder; ?>" autocomplete="off">
									</div>
								</div>
								<?php require_once(DIR_TEMPLATE . 'company/company-autocomplete.tpl'); ?>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-exp"><?php echo $entry_expert; ?></label>
								<div class="col-sm-10">
									<div class="btn-group btn-toggle" data-toggle="buttons">
										<label class="btn btn-primary <?php echo $expert == 0 ? 'active' : ''; ?>">
											<input 
											type="radio" 
											name="expert" 
											value="0"
											<?php echo $expert == 0 ? 'checked' : ''; ?> 
											> Пользователь
										</label>
										<label class="btn btn-warning <?php echo $expert == 1 ? 'active' : ''; ?>">
											<input 
											type="radio" 
											name="expert" 
											value="1"
											<?php echo $expert == 1 ? 'checked' : ''; ?> 
											> Эксперт
										</label>
									</div>
								</div>
							</div>

							<div class="form-group <?php echo $expert == 1 ? '' : 'hidden'; ?>">
								<label class="col-sm-2 control-label" for="input-b24id"><?php echo $entry_b24id; ?></label>
								<div class="col-sm-10">
									<input type="text" name="b24id" value="<?php echo $b24id; ?>" placeholder="<?php echo $entry_b24id; ?>" id="input-b24id" class="form-control" />
								</div>
							</div>

							<!--<div class="form-group <?php echo $expert == 1 ? '' : 'hidden'; ?>">
								<label class="col-sm-2 control-label" for="input-emails"><?php echo $entry_emails; ?></label>
								<div class="col-sm-10">
									<textarea name="emails" rows="5" placeholder="<?php echo $entry_emails; ?>" id="input-emails" class="form-control"><?php echo $emails; ?></textarea>
								</div>
							</div>-->

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-exp"><?php echo $entry_exp; ?></label>
								<div class="col-sm-10">
									<?php  ?>
									<input type="text" name="exp" value="<?php echo $exp; ?>" placeholder="<?php echo $entry_exp; ?>" id="input-exp" class="form-control hidden" />
									<?php  ?>

									<table id="exp" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<td class="text-left"><?php echo $entry_exp; ?></td>
												<td style="width: 50px;min-width: 50px;"></td>
											</tr>
										</thead>
										<tbody>
											<?php $exp_row = 0; ?>
											<?php $exp_sort = 0; ?>
											<?php $exp_add = 0; ?>

											<?php if (!empty($exp_list)) { ?>
												<?php foreach ($exp_list as $exp_item) { ?>
													<tr data-id="<?php echo $exp_item['exp_id']; ?>">
														<td class="text-left">
															<input 
															name="exp_list[<?php echo $exp_item['exp_id']; ?>][exp]" 
															placeholder="<?php echo $entry_exp; ?>" 
															class="form-control" 
															value="<?php echo $exp_item['exp']; ?>" 
															/>
														</td>
														<td>
															<div class="btn-group btn-toggle" data-toggle="buttons">
																<label class="btn btn-success <?php echo $exp_item['main'] == 1 ? 'active' : ''; ?>">
																	<input 
																	type="radio" 
																	name="exp_list[<?php echo $exp_item['exp_id']; ?>][exp_main]" 
																	class="exp_main"
																	value="1"
																	<?php echo $exp_item['main'] == 1 ? 'checked' : ''; ?> 
																	> По-умолчанию
																</label>
															</div>
														</td>
														<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger exp__remove"><i class="fa fa-minus-circle"></i></button></td>
														<input 
														type="hidden" 
														class="expert__main" 
														name="exp_list[<?php echo $exp_item['exp_id']; ?>][main]" 
														value="<?php echo $exp_item['main']; ?>"
														/>
													</tr>
												<?php } ?>
											<?php } ?>

											<?php if (!empty($exp_add)) { ?>
												<?php foreach ($exp_add as $key=>$exp_item) { ?>
													<?php $exp_add = $exp_add < (int)$key ? (int)$key : $exp_add; ?>
													<tr>
														<td class="text-left">
															<input 
															name="exp_add[<?php echo $exp_item['exp_id']; ?>][exp]" 
															placeholder="<?php echo $entry_exp; ?>" 
															class="form-control" 
															value="<?php echo $exp_item['exp']; ?>" 
															/>
														</td>
														<td>
															<div class="btn-group btn-toggle" data-toggle="buttons">
																<label class="btn btn-success <?php echo $exp_item['main'] == 1 ? 'active' : ''; ?>">
																	<input 
																	type="radio" 
																	name="exp_add[<?php echo $exp_item['exp_id']; ?>][exp_main]" 
																	class="exp_main"
																	value="1"
																	<?php echo $exp_item['main'] == 1 ? 'checked' : ''; ?> 
																	> По-умолчанию
																</label>
															</div>
														</td>
														<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger exp__remove"><i class="fa fa-minus-circle"></i></button></td>
														<input 
														type="hidden" 
														class="expert__main" 
														name="exp_add[<?php echo $exp_item['exp_id']; ?>][main]" 
														value="<?php echo $exp_item['main']; ?>"
														/>
													</tr>
												<?php } ?>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="2" class="text-right"><button type="button" onclick="addExp();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $text_exp_add; ?></button></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
								<div class="col-sm-10">
									<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
									<input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_event; ?></label>
								<div class="col-sm-10">
									<div class="event__cont">
										<ul class="event__list">
											<?php if(!empty($events)) { ?>
												<?php foreach($events as $event) { ?>
													<li class="event__item event__item-<?php echo $event['event_id']; ?>" >
														<?php echo $event['title']; ?>
														<button type="button" class="event__remove" ><i class="fa fa-close"></i></button>
														<input type="hidden" name="event[]" value="<?php echo $event['event_id']; ?>">
													</li>
												<?php } ?>
											<?php } ?>
										</ul>
										<input type="text" name="event_search" class="event__input" placeholder="<?php echo $entry_event_placeholder; ?>" autocomplete="off">
									</div>
								</div>
								<?php require_once(DIR_TEMPLATE . 'avevent/event-autocomplete.tpl'); ?>
							</div>

							<!--<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
								<div class="col-sm-10">
									<input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
									<?php if ($error_email) { ?>
										<div class="text-danger"><?php echo $error_email; ?></div>
									<?php  } ?>
								</div>
							</div>-->

							<!-- <div class="form-group required">
								<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
								<div class="col-sm-10">
									<input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
									<?php if ($error_email) { ?>
										<div class="text-danger"><?php echo $error_email; ?></div>
									<?php  } ?>
								</div>
							</div> -->

							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
								<div class="col-sm-10">
									<input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" autocomplete="off" />
									<?php if ($error_password) { ?>
										<div class="text-danger"><?php echo $error_password; ?></div>
									<?php  } ?>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
								<div class="col-sm-10">
									<input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" autocomplete="off" id="input-confirm" class="form-control" />
									<?php if ($error_confirm) { ?>
										<div class="text-danger"><?php echo $error_confirm; ?></div>
									<?php  } ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
									<?php if ($error_keyword) { ?>
										<div class="text-danger"><?php echo $error_keyword; ?></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
								<div class="col-sm-10">
									<select name="status" id="input-status" class="form-control">
										<?php if ($status) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>


						</div>

						<div class="tab-pane" id="tab-fields">

							<div class="form-group ">
								<label class="col-sm-2 control-label"><?php echo $entry_tag; ?></label>
								<div class="col-sm-10">
									<div class="tag__cont">
										<ul class="tag__list">
											<?php if($tags_expert) { ?>
												<?php foreach($tags_expert as $tag) { ?>
													<li class="tag__item tag__item-<?php echo $tag['tag_id']; ?>" value="<?php echo utf8_strtolower($tag['tag']); ?>">
														<?php echo $tag['tag']; ?>
														<button type="button" class="tag__remove" ><i class="fa fa-close"></i></button>
														<input type="hidden" name="tag_expert[]" value="<?php echo $tag['tag_id']; ?>">
													</li>
												<?php } ?>
											<?php } ?>

											<?php if($tags_expert_new) { ?>
												<?php foreach($tags_expert_new as $tag) { ?>
													<li class="tag__item tag__item-new" data-toggle="tooltip" value="<?php echo utf8_strtolower($tag['tag']); ?>" title="" data-original-title="Этого тега пока нет в базе данных. Он будет создан при сохранении страницы">
														<?php echo $tag['tag']; ?>
														<button type="button" class="tag__remove"><i class="fa fa-close"></i></button><input type="hidden" name="tag_expert_new[]" value="<?php echo $tag['tag']; ?>">
													</li>
												<?php } ?>
											<?php } ?>
										</ul>
										<input type="text" name="tag_search" class="tag__input" placeholder="<?php echo $entry_tag_placeholder; ?>" data-name="tag_expert[]" data-name-new="tag_expert_new[]">
									</div>
								</div>
							</div>

							<div class="form-group ">
								<label class="col-sm-2 control-label">Теги отрасли</label>
								<div class="col-sm-10">
									<div class="tag__cont">
										<ul class="tag__list">
											<?php if($tags_branch) { ?>
												<?php foreach($tags_branch as $tag) { ?>
													<li class="tag__item tag__item-<?php echo $tag['tag_id']; ?>" value="<?php echo utf8_strtolower($tag['tag']); ?>">
														<?php echo $tag['tag']; ?>
														<button type="button" class="tag__remove" ><i class="fa fa-close"></i></button>
														<input type="hidden" name="tag_branch[]" value="<?php echo $tag['tag_id']; ?>">
													</li>
												<?php } ?>
											<?php } ?>

											<?php if($tags_branch_new) { ?>
												<?php foreach($tags_branch_new as $tag) { ?>
													<li class="tag__item tag__item-new" data-toggle="tooltip" value="<?php echo utf8_strtolower($tag['tag']); ?>" title="" data-original-title="Этого тега пока нет в базе данных. Он будет создан при сохранении страницы">
														<?php echo $tag['tag']; ?>
														<button type="button" class="tag__remove"><i class="fa fa-close"></i></button><input type="hidden" name="tag_branch_new[]" value="<?php echo $tag['tag']; ?>">
													</li>
												<?php } ?>
											<?php } ?>
										</ul>
										<input type="text" name="tag_search" class="tag__input" placeholder="<?php echo $entry_tag_placeholder; ?>" data-name="tag_branch[]" data-name-new="tag_branch_new[]">
									</div>
								</div>
							</div>
							<?php require_once(DIR_TEMPLATE . 'tag/tag-autocomplete.tpl'); ?>

							<?php $field_list = array(
								'field_expertise' => array(
									'entry'	=> $entry_field_expertise,
									'value'	=> $field_expertise,
								),
								'field_useful' => array(
									'entry'	=> $entry_field_useful,
									'value'	=> $field_useful,
								),
								'field_regalia' => array(
									'entry'	=> $entry_field_regalia,
									'value'	=> $field_regalia,
								),
							); ?>

							<?php foreach($field_list as $key=>$item) { ?>
								<div class="form-group ">
									<label class="col-sm-2 control-label" ><?php echo $item['entry']; ?></label>
									<div class="col-sm-10">
										<textarea name="<?php echo $key; ?>" rows="5" placeholder="<?php echo $item['entry']; ?>" class="form-control"><?php echo $item['value']; ?></textarea>
									</div>
								</div>
							<?php } ?>


						</div>


					</div>

				</form>
			</div>
		</div>
	</div>


</div>
<script>
	var $index = <?php echo $exp_add + 1; ?>;
	addExp = function() {
		html = '';
		html += '<tr>';
		html += '	<td class="text-left">';
		html += '		<input name="exp_add[' + $index + '][exp]" placeholder="<?php echo $entry_exp; ?>" class="form-control" value="" />';
		html += '	</td>';
		html += '	<td>';
		html += '		<div class="btn-group btn-toggle" data-toggle="buttons">';
		html += '			<label class="btn btn-success">';
		html += '				<input type="radio" class="exp_main" name="exp_add[' + $index + '][exp_main]" value="1"> По-умолчанию</label>';
		html += '		</div>';
		html += '	</td>';
		html += '	<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger exp__remove"><i class="fa fa-minus-circle"></i></button></td>';
		html += ' <input type="hidden" name="exp_add[' + $index + '][main]" class="expert__main" value="0">';
		html += '</tr>';

		$('#exp tbody').append(html);
		$index++;
	}

	changeExpert = function(){
		var 
		$expert = $('input[name="expert"]:checked').val(),
		$b24id = $('input[name="b24id"]');
		$emails = $('textarea[name="emails"]');

		if($expert == 1) {
			$b24id.closest('.form-group').removeClass('hidden');
			$emails.closest('.form-group').removeClass('hidden');
		}else{
			$b24id.closest('.form-group').addClass('hidden');
			$emails.closest('.form-group').addClass('hidden');
		}
	}

	$(function(){

		$('input[name="expert"]').on('change', changeExpert);

		$(document).on('click', '.exp__remove', function(e) {
			e.preventDefault();
			if(confirm('<?php echo $confirm_exp_remove; ?>')) {
				var
				$self = $(this),
				$tr = $self.closest('tr'),
				$exp_id = $tr.attr('data-id');

				if(typeof $exp_id !== typeof undefined && $exp_id !== false) {
					$.ajax({
						url: 'index.php?route=visitor/visitor/deleteExp&token=<?php echo $token; ?>&exp_id=' + $exp_id + '&visitor_id=<?php echo $visitor_id; ?>',
						dataType: 'json',
						error: function(json) {
							console.log(json);
						},
						success: function(json) {
							if(json['success'] ) {
								$tr.remove();
							}else{
								alert(json['error']);
							}
						}
					});
				}else{
					$tr.remove();
				}
			}
		})


		$(document).on('change', 'input.exp_main', function(){
			if($(this).prop('checked')) {

				$(this)
				.closest('tr')
				.find('input.expert__main').val(1);

				$(this)
				.closest('tr')
				.siblings('tr')
				.find('.exp_main').prop('checked', false)
				.closest('label').removeClass('active')
				.closest('tr')
				.find('input.expert__main').val(0);
			}
		});


	})
</script>
<style>
	.btn-toggle .btn:not(.active){
		color: #333;
		background-color: #e6e6e6;
		border-color: #adadad;
	}
</style>

<script type="text/javascript">

	var config_language_id = <?php echo $config_language_id; ?>;  

	<?php if(stristr($_GET['route'], 'add')) { ?>

		$('#input-name').change(function(){ generateUrlOnAdd(); });  

		function generateUrlOnAdd() {
			data = {
				name           : $('#input-name').val(),
				essence        : 'expert',
				expert_id : ''
			};

			getSeoUrl(data);
		}

	<?php } else { ?>

		$('#input-keyword').after('<br><button id="generateUrlOnEdit" class="btn btn-success">GENERATE SEO URL</button>');

		$('#generateUrlOnEdit').click(function(e){
			e.preventDefault();

			data = {
				name           : $('#input-firstname').val() + ' ' +  $('#input-lastname').val(),
				essence        : 'expert',
				expert_id : <?php echo $_GET['visitor_id']; ?>
			};

			getSeoUrl(data);
		});   

	<?php } ?>

	function getSeoUrl(data) {
		$.ajax({
			url: 'index.php?route=extension/module/seo_url_generator/getSeoUrlByAjax&token=<?php echo $token; ?>',
			dataType: 'json',
			data: data,
			method : 'POST',
			beforeSend: function() {
			},
			success: function( response, textStatus, jqXHR ){
				if( response.result != '' ){
					$('#input-keyword').val(response.result);
				}
			},
			error: function( jqXHR, textStatus, errorThrown ){
				console.log('AJAX query Error: ' + textStatus );
			},
			complete: function() {  
			},
		});
	}

</script>

<?php echo $footer; ?>
