<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-company" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-company" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
						<li class="hidden"><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<ul class="nav nav-tabs" id="language">
								<?php foreach ($languages as $language) { ?>
									<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
								<?php } ?>
							</ul>
							<div class="tab-content">
								<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
										<div class="form-group required">
											<label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
											<div class="col-sm-10">
												<input type="text" name="company_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($company_description[$language['language_id']]) ? $company_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-alternate<?php echo $language['language_id']; ?>"><?php echo $entry_alternate; ?></label>
											<div class="col-sm-10">
												<textarea name="company_description[<?php echo $language['language_id']; ?>][alternate]" rows="3" placeholder="<?php echo $entry_alternate; ?>" id="input-alternate<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($company_description[$language['language_id']]) ? $company_description[$language['language_id']]['alternate'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-preview<?php echo $language['language_id']; ?>"><?php echo $entry_preview; ?></label>
											<div class="col-sm-10">
												<textarea name="company_description[<?php echo $language['language_id']; ?>][preview]" rows="3" placeholder="<?php echo $entry_preview; ?>" id="input-preview<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($company_description[$language['language_id']]) ? $company_description[$language['language_id']]['preview'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group ">
											<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
											<div class="col-sm-10">
												<textarea name="company_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo isset($company_description[$language['language_id']]) ? $company_description[$language['language_id']]['description'] : ''; ?></textarea>
												<?php if (isset($error_description[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
											<div class="col-sm-10">
												<input type="text" name="company_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($company_description[$language['language_id']]) ? $company_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-h1<?php echo $language['language_id']; ?>"><?php echo $entry_meta_h1; ?></label>
											<div class="col-sm-10">
												<input type="text" name="company_description[<?php echo $language['language_id']; ?>][meta_h1]" value="<?php echo isset($company_description[$language['language_id']]) ? $company_description[$language['language_id']]['meta_h1'] : ''; ?>" placeholder="<?php echo $entry_meta_h1; ?>" id="input-meta-h1<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
											<div class="col-sm-10">
												<textarea name="company_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($company_description[$language['language_id']]) ? $company_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
											<div class="col-sm-10">
												<textarea name="company_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($company_description[$language['language_id']]) ? $company_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="tab-pane" id="tab-data">

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?></label>
								<div class="col-sm-10">
									<select name="category_id" class="form-control">
										<option value="0" selected="selected"><?php echo $text_none; ?></option>
										<?php foreach($categories as $category) { ?>
											<?php if($category['category_id'] == $category_id) { ?>
												<option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['title']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $category['category_id']; ?>"><?php echo $category['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_tag_main; ?></label>
								<div class="col-sm-10">
									<div class="tag__cont">
										<ul class="tag__list">
											<?php if($main_tag) { ?>
												<li class="tag__item tag__item-<?php echo $main_tag['tag_id']; ?>" value="<?php echo utf8_strtolower($main_tag['tag']); ?>">
													<?php echo $main_tag['tag']; ?>
													<button type="button" class="tag__remove" ><i class="fa fa-close"></i></button>
													<input type="hidden" name="tag_id" value="<?php echo $main_tag['tag_id']; ?>">
												</li>
											<?php } ?>
										</ul>
										<input type="text" name="tag_search" class="tag__input_uniq" placeholder="<?php echo $entry_tag_placeholder; ?>">
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-site"><?php echo $entry_site; ?></label>
								<div class="col-sm-10">
									<input type="text" name="site" value="<?php echo $site; ?>" placeholder="<?php echo $entry_site; ?>" id="input-site" class="form-control" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
								<div class="col-sm-10">
									<input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_phone; ?></label>
								<div class="col-sm-5">
									<input type="text" name="phone_1" value="<?php echo $phone_1; ?>" placeholder="<?php echo $entry_phone; ?>" id="input-phone_1" class="form-control" />
								</div>
								<div class="col-sm-5">
									<input type="text" name="phone_2" value="<?php echo $phone_2; ?>" placeholder="<?php echo $entry_phone; ?>" id="input-phone_2" class="form-control" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-social"><?php echo $entry_social; ?></label>
								<div class="col-sm-10">
									<table class="table table-striped table-bordered table-hover table__social">
										<thead>
											<tr>
												<td style="width: 40px;"></td>
												<td>Ссылка</td>
												<td>Тип</td>
												<td style="width: 60px;"></td>
											</tr>
											<tbody>
												<?php if(!empty($social)) { ?>
													<?php foreach($social as $key=>$item) { ?>
														<tr>
															<td class="text-center">
																<i class="fa fa-arrows"></i>
															</td>
															<td>
																<input type="text" class="form-control" name="social[<?php echo $key; ?>][link]" value="<?php echo $item['link']; ?>">
															</td>
															<td>
																<select name="social[<?php echo $key; ?>][type]" class="form-control">
																	<?php foreach($social_type as $key_type=>$type) { ?>
																		<option value="<?php echo $key_type; ?>" <?php echo $key_type === $item['type'] ? 'selected' : ''; ?>><?php echo $type; ?></option>
																	<?php } ?>
																</select>
															</td>
															<td class="text-center">
																<button class="btn btn-danger social__remove" type="button"><i class="fa fa-trash"></i></button>
															</td>
														</tr>
													<?php } ?>
												<?php } ?>
											</tbody>
											<tfoot>
												<tr>
													<td></td>
													<td colspan="3">
														<button class="btn btn-primary social__add" type="button">Добавить ссылку</button>
													</td>
												</tr>
											</tfoot>
										</thead>
									</table>
									<script>
										$(function(){
											var social_ind = <?php echo !empty($social) ? count($social) : 0; ?>;
											$('.social__add').on('click', function(e){
												e.preventDefault();
												var html = '';

												html +='<tr>';
												html +='	<td class="text-center"><i class="fa fa-arrows"></i></td>';
												html +='	<td>';
												html +='		<input type="text" class="form-control" name="social['+social_ind+'][link]" value="">';
												html +='	</td>';
												html +='	<td>';
												html +='		<select name="social['+social_ind+'][type]" class="form-control">';
												<?php foreach($social_type as $key_type=>$type) { ?>
													html +='				<option value="<?php echo $key_type; ?>"><?php echo $type; ?></option>';
												<?php } ?>
												html +='		</select>';
												html +='	</td>';
												html +='	<td class="text-center">';
												html +='		<button class="btn btn-danger social__remove" type="button"><i class="fa fa-trash"></i></button>';
												html +='	</td>';
												html +='</tr>';

												$('.table__social tbody').append(html);
												social_ind++;
											})

											$(document).on('click', '.social__remove', function(e){
												e.preventDefault();
												if(confirm('Вы уверены?')) {
													$(this).closest('tr').remove();
												}
											})
										})
									</script>
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
								<label class="col-sm-2 control-label"><?php echo $entry_gallery; ?></label>
								<div class="col-sm-10 sortable_gall">

									<?php if(!empty($gallery)) { ?>
										<?php foreach($gallery as $index=>$image) { ?>
											<div class="gall__item">
												<a href="" id="thumb-image-gallery-<?php echo $index; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
												<input type="hidden" name="gallery[]" value="<?php echo $image['image']; ?>" id="input-image-gallery-<?php echo $index; ?>" />
											</div>
										<?php } ?>
									<?php } ?>

									<button type="button" class="gall__item gall__add ui-state-disabled" data-number="<?php echo !empty($gallery) ? count($gallery) : '0'; ?>" id="gall-add" data-toggle="tooltip" title="Добавить изображение"></button>
								</div>
							</div>

							<div class="form-group ">
								<label class="col-sm-2 control-label"><?php echo $entry_brand; ?></label>
								<div class="col-sm-10">
									<div class="brand__cont">
										<ul class="brand__list">
											<?php if($brands) { ?>
												<?php foreach($brands as $brand) { ?>
													<li class="brand__item brand__item-<?php echo $brand['brand_id']; ?>" value="<?php echo utf8_strtolower($brand['brand']); ?>">
														<?php echo $brand['brand']; ?>
														<button type="button" class="brand__remove" ><i class="fa fa-close"></i></button>
														<input type="hidden" name="brand[]" value="<?php echo $brand['brand_id']; ?>">
													</li>
												<?php } ?>
											<?php } ?>

											<?php if($brands_new) { ?>
												<?php foreach($brands_new as $brand) { ?>
													<li class="brand__item brand__item-new" data-toggle="tooltip" value="<?php echo utf8_strtolower($brand['brand']); ?>" title="" data-original-title="Этого бренда пока нет в базе данных. Он будет создан при сохранении страницы">
														<?php echo $brand['brand']; ?>
														<button type="button" class="brand__remove"><i class="fa fa-close"></i></button><input type="hidden" name="brand_new[]" value="<?php echo $brand['brand']; ?>">
													</li>
												<?php } ?>
											<?php } ?>
										</ul>
										<input type="text" name="brand_search" class="brand__input" placeholder="<?php echo $entry_brand_placeholder; ?>">
									</div>
								</div>
								<?php require_once(DIR_TEMPLATE . 'company/brand-autocomplete.tpl'); ?>
							</div>

							<div class="form-group ">
								<label class="col-sm-2 control-label"><?php echo $entry_branch; ?></label>
								<div class="col-sm-10">
									<div class="branch__cont">
										<ul class="branch__list">
											<?php if($branches) { ?>
												<?php foreach($branches as $branch) { ?>
													<li class="branch__item branch__item-<?php echo $branch['branch_id']; ?>" value="<?php echo utf8_strtolower($branch['branch']); ?>">
														<?php echo $branch['branch']; ?>
														<button type="button" class="branch__remove" ><i class="fa fa-close"></i></button>
														<input type="hidden" name="branch[]" value="<?php echo $branch['branch_id']; ?>">
													</li>
												<?php } ?>
											<?php } ?>

											<?php if($branches_new) { ?>
												<?php foreach($branches_new as $branch) { ?>
													<li class="branch__item branch__item-new" data-toggle="tooltip" value="<?php echo utf8_strtolower($branch['branch']); ?>" title="" data-original-title="Этой отрасли пока нет в базе данных. Она будет создана при сохранении страницы">
														<?php echo $branch['branch']; ?>
														<button type="button" class="branch__remove"><i class="fa fa-close"></i></button><input type="hidden" name="branch_new[]" value="<?php echo $branch['branch']; ?>">
													</li>
												<?php } ?>
											<?php } ?>
										</ul>
										<input type="text" name="branch_search" class="branch__input" placeholder="<?php echo $entry_branch_placeholder; ?>">
									</div>
								</div>
								<?php require_once(DIR_TEMPLATE . 'company/branch-autocomplete.tpl'); ?>
							</div>

							<div class="form-group ">
								<label class="col-sm-2 control-label"><?php echo $entry_tag; ?></label>
								<div class="col-sm-10">
									<div class="tag__cont">
										<ul class="tag__list">
											<?php if($tags) { ?>
												<?php foreach($tags as $tag) { ?>
													<li class="tag__item tag__item-<?php echo $tag['tag_id']; ?>" value="<?php echo utf8_strtolower($tag['tag']); ?>">
														<?php echo $tag['tag']; ?>
														<button type="button" class="tag__remove" ><i class="fa fa-close"></i></button>
														<input type="hidden" name="tag[]" value="<?php echo $tag['tag_id']; ?>">
													</li>
												<?php } ?>
											<?php } ?>

											<?php if($tags_new) { ?>
												<?php foreach($tags_new as $tag) { ?>
													<li class="tag__item tag__item-new" data-toggle="tooltip" value="<?php echo utf8_strtolower($tag['tag']); ?>" title="" data-original-title="Этой отрасли пока нет в базе данных. Она будет создана при сохранении страницы">
														<?php echo $tag['tag']; ?>
														<button type="button" class="tag__remove"><i class="fa fa-close"></i></button><input type="hidden" name="tag_new[]" value="<?php echo $tag['tag']; ?>">
													</li>
												<?php } ?>
											<?php } ?>
										</ul>
										<input type="text" name="tags_search" class="tag__input" placeholder="<?php echo $entry_tag_placeholder; ?>">
									</div>
								</div>
							</div>
							<?php require_once(DIR_TEMPLATE . 'company/product-autocomplete.tpl'); ?>

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
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sort_order"><span data-toggle="tooltip" title="<?php echo $help_sort_order; ?>"><?php echo $entry_sort_order; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort_order" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
								<div class="col-sm-10">
									<select name="status" id="input-status" class="form-control">
										<option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>><?php echo $text_enabled; ?></option>
										<option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>><?php echo $text_disabled; ?></option>
									</select>
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
						</div>
						<div class="tab-pane" id="tab-design">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?php echo $entry_store; ?></td>
											<td class="text-left"><?php echo $entry_layout; ?></td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="text-left"><?php echo $text_default; ?></td>
											<td class="text-left"><select name="company_layout[0]" class="form-control">
												<option value=""></option>
												<?php foreach ($layouts as $layout) { ?>
													<?php if (isset($company_layout[0]) && $company_layout[0] == $layout['layout_id']) { ?>
														<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
													<?php } else { ?>
														<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript"><!--
		<?php if ($ckeditor) { ?>
			<?php foreach ($languages as $language) { ?>
				ckeditorInit('input-description<?php echo $language['language_id']; ?>', getURLVar('token'));
			<?php } ?>
		<?php } ?>
	</script>
	<script type="text/javascript">
		$('#language a:first').tab('show');
	</script>

</div>

<style>
	.gall__item{
		display: inline-block;
		vertical-align: top;
		float: left;
		margin: 5px;
	}
	.gall__add{
		display: inline-flex;
		width: 110px;
		height: 110px;
		position: relative;
		border: 1px solid #ddd;
		border-radius: 3px;
		align-items: center;
		justify-content: center;
		background: #f9f9f9;
		font-size: 70px;
		font-weight: 100;
		color: #ccc;
	}
	.gall__add:before{
		content: '+';
	}
</style>
<script>
	$(document).on('click', '.gall__add', function(){
		var
		$self = $(this),
		$number = parseInt($self.attr('data-number')) + 1,
		$html = '';

		$self.attr('data-number', $number);

		$html += '<div class="gall__item">';
		$html += '	<a href="" id="thumb-image-gallery-'+$number+'" data-toggle="image" class="img-thumbnail">';
		$html += '	<img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>';
		$html += '	<input type="hidden" name="gallery[]" value="" id="input-image-gallery-'+$number+'" />';
		$html += '</div>';

		$self.before($html);

	})
</script>
<script>
	$( ".sortable_gall" ).sortable({
		items: ".gall__item:not(.ui-state-disabled)"
	});

	$(document).on('click', '.gall__item #button-clear', function(){
		$(this).closest('.gall__item').remove();
	})
</script>


<script type="text/javascript">

	var config_language_id = <?php echo $config_language_id; ?>;  

	<?php if(stristr($_GET['route'], 'add')) { ?>

		$('#input-title' + config_language_id).change(function(){ generateUrlOnAdd(); });  

		function generateUrlOnAdd() {
			data = {
				name           : $('#input-title' + config_language_id).val(),
				essence        : 'company',
				company_id : ''
			};

			getSeoUrl(data);
		}

	<?php } else { ?>

		$('#input-keyword').after('<br><button id="generateUrlOnEdit" class="btn btn-success">GENERATE SEO URL</button>');

		$('#generateUrlOnEdit').click(function(e){
			e.preventDefault();

			data = {
				name           : $('#input-title' + config_language_id).val(),
				essence        : 'company',
				company_id : <?php echo $_GET['company_id']; ?>
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
<script>
	$('.datetime').datetimepicker({
		pickDate: true,
		pickTime: true,
		icons: {
			time: 'fa fa-clock-o',
			date: 'fa fa-calendar',
			up: 'fa fa-sort-up',
			down: 'fa fa-sort-down'
		}
	});
</script>
<?php echo $footer; ?>
