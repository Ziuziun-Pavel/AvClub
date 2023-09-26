<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-journal" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-journal" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
						<li><a href="#tab-gallery" data-toggle="tab" class="<?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>"><?php echo $tab_gallery; ?></a></li>
						<li><a href="#tab-column" data-toggle="tab" class="<?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>"><?php echo $tab_column; ?></a></li>
						<li><a href="#tab-case" data-toggle="tab" class="<?php echo in_array($type, array('case')) ? '' : 'hidden'; ?>"><?php echo $tab_case; ?></a></li>
						<li><a href="#tab-copy" data-toggle="tab" class="<?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>"><?php echo $tab_copy; ?></a></li>
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
												<input type="text" name="journal_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($journal_description[$language['language_id']]) ? $journal_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group <?php echo in_array($type, array('news', 'article','video')) ? '' : 'hidden'; ?>">
											<label class="col-sm-2 control-label" for="input-preview<?php echo $language['language_id']; ?>"><?php echo $entry_preview; ?></label>
											<div class="col-sm-10">
												<textarea name="journal_description[<?php echo $language['language_id']; ?>][preview]" rows="3" placeholder="<?php echo $entry_preview; ?>" id="input-preview<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($journal_description[$language['language_id']]) ? $journal_description[$language['language_id']]['preview'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group required <?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>">
											<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
											<div class="col-sm-10">
												<textarea name="journal_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo isset($journal_description[$language['language_id']]) ? $journal_description[$language['language_id']]['description'] : ''; ?></textarea>
												<?php if (isset($error_description[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
												<?php } ?>
												<div class="well">
													<?php echo $help_input_master; ?>
												</div>
											</div>
										</div>
										<div class="form-group <?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>">
											<label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
											<div class="col-sm-10">
												<input type="text" name="journal_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($journal_description[$language['language_id']]) ? $journal_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group <?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>">
											<label class="col-sm-2 control-label" for="input-meta-h1<?php echo $language['language_id']; ?>"><?php echo $entry_meta_h1; ?></label>
											<div class="col-sm-10">
												<input type="text" name="journal_description[<?php echo $language['language_id']; ?>][meta_h1]" value="<?php echo isset($journal_description[$language['language_id']]) ? $journal_description[$language['language_id']]['meta_h1'] : ''; ?>" placeholder="<?php echo $entry_meta_h1; ?>" id="input-meta-h1<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group <?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>">
											<label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
											<div class="col-sm-10">
												<textarea name="journal_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($journal_description[$language['language_id']]) ? $journal_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group <?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>">
											<label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
											<div class="col-sm-10">
												<textarea name="journal_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($journal_description[$language['language_id']]) ? $journal_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>

						<!-- DATA -->
						<div class="tab-pane" id="tab-data">
							<div class="form-group <?php echo in_array($type, array('article','opinion','video','case')) ? '' : 'hidden'; ?>">
								<label class="col-sm-2 control-label"><?php echo $entry_author; ?></label>
								<div class="col-sm-10">
									<div class="author__cont">
										<ul class="author__list">
											<?php if($author) { ?>
												<li class="author__item author__item-<?php echo $author['author_id']; ?>" >
													<?php echo $author['name']; ?>
													<select name="author_exp" class="form-control">
														<option value="0">-- По-умолчанию --</option>
														<?php if(!empty($author['exp_list'])) { ?>
															<?php foreach($author['exp_list'] as $exp_item) { ?>
																<option 
																value="<?php echo $exp_item['exp_id']; ?>"
																<?php if( (empty($author_exp) && $exp_item['main']) || $exp_item['exp_id'] == $author_exp ) {echo 'selected'; } ?>
																>
																<?php echo $exp_item['exp']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
													<button type="button" class="author__remove" ><i class="fa fa-close"></i></button>
													<input type="hidden" name="author_id" value="<?php echo $author['author_id']; ?>">
												</li>
											<?php } ?>
										</ul>
										<input type="text" name="author_search" class="author__input" placeholder="<?php echo $entry_author_placeholder; ?>">
									</div>
									<?php if ($error_author) { ?>
										<div class="text-danger"><?php echo $error_author; ?></div>
									<?php } ?>
								</div>
								<?php require_once(DIR_TEMPLATE . 'visitor/visitor-autocomplete.tpl'); ?>
							</div>
							<div class="form-group ">
								<label class="col-sm-2 control-label"><?php echo $entry_experts; ?></label>
								<div class="col-sm-10">
									<div class="author__cont">
										<ul class="author__list">
											<?php if($experts) { ?>
												<?php foreach($experts as $expert) { ?>
													<li class="author__item author__item-<?php echo $expert['author_id']; ?>" >
														<?php echo $expert['name']; ?>
														<select name="experts[<?php echo $expert['author_id']; ?>][exp_id]" class="form-control">
															<option value="0">-- По-умолчанию --</option>
															<?php if(!empty($expert['exp_list'])) { ?>
																<?php foreach($expert['exp_list'] as $exp_item) { ?>
																	<option 
																	value="<?php echo $exp_item['exp_id']; ?>" 
																	<?php echo $exp_item['exp_id'] == $expert['exp_id'] ? 'selected' : ''; ?>
																	>
																	<?php echo $exp_item['exp']; ?></option>
																<?php } ?>
															<?php } ?>
														</select>
														<button type="button" class="author__remove" ><i class="fa fa-close"></i></button>
														<input type="hidden" name="experts[<?php echo $expert['author_id']; ?>][author_id]" value="<?php echo $expert['author_id']; ?>">
													</li>
												<?php } ?>
											<?php } ?>
										</ul>
										<input type="text" name="authors_search" class="author__input" placeholder="<?php echo $entry_author_placeholder; ?>" data-name="experts">
									</div>
								</div>
							</div>

							<div class="form-group <?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>">
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
										</ul>
										<input type="text" name="tag_search" class="tag__input" placeholder="<?php echo $entry_tag_placeholder; ?>">
									</div>
								</div>
								<?php $add_status = false; ?>
								<?php require_once(DIR_TEMPLATE . 'tag/tag-autocomplete.tpl'); ?>
							</div>
							<div class="form-group <?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>">
								<label class="col-sm-2 control-label" ><?php echo $entry_master; ?></label>
								<div class="col-sm-10">
									<select name="master_id" class="form-control">
										<option value=""><?php echo $entry_master_default; ?></option>
										<?php if($master_list) { ?>
											<?php foreach($master_list as $master) { ?>
												<option value="<?php echo $master['master_id']; ?>" <?php echo $master['master_id'] == $master_id ? 'selected' : ''; ?>><?php echo $master['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group <?php echo in_array($type, array('opinion')) ? 'hidden' : ''; ?>">
								<label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
								<div class="col-sm-10">
									<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
									<input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
								</div>
							</div>
							<div class="form-group <?php echo in_array($type, array('opinion')) ? 'hidden' : ''; ?>">
								<label class="col-sm-2 control-label" for="input-image_show"><?php echo $entry_image_show; ?></label>
								<div class="col-sm-4">
									<div class="btn-group btn-toggle" data-toggle="buttons">
										<label class="btn btn-primary <?php echo $image_show == 1 ? 'active' : ''; ?>"><input type="radio" name="image_show" value="1"  <?php echo $image_show == 1 ? 'checked' : ''; ?>> Да </label>
										<label class="btn btn-default   <?php echo $image_show == 0 ? 'active' : ''; ?>"><input type="radio" name="image_show" value="0"   <?php echo $image_show == 0 ? 'checked' : ''; ?>>  Нет </label>
									</div>
								</div>
							</div>
							<div class="form-group <?php echo in_array($type, array('video')) ? '' : 'hidden'; ?>">
								<label class="col-sm-2 control-label" for="input-video"><?php echo $entry_video; ?></label>
								<div class="col-sm-10">
									<input type="text" name="video" value="<?php echo $video; ?>" placeholder="<?php echo $entry_video; ?>" id="input-video" class="form-control" />
								</div>
							</div>
							<div class="form-group <?php echo in_array($type, array('video')) ? '' : 'hidden'; ?>">
								<label class="col-sm-2 control-label" for="input-master_old"><?php echo $entry_master_old; ?></label>
								<div class="col-sm-4">
									<div class="btn-group btn-toggle" data-toggle="buttons">
										<label class="btn btn-primary <?php echo $master_old == 1 ? 'active' : ''; ?>"><input type="radio" name="master_old" value="1"  <?php echo $master_old == 1 ? 'checked' : ''; ?>> Да </label>
										<label class="btn btn-default   <?php echo $master_old == 0 ? 'active' : ''; ?>"><input type="radio" name="master_old" value="0"   <?php echo $master_old == 0 ? 'checked' : ''; ?>>  Нет </label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-date-available"><?php echo $entry_date_available; ?></label>
								<div class="col-sm-4">
									<div class="input-group datetime">
										<input type="text" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" id="input-date-available" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group <?php echo in_array($type, array('special')) ? 'hidden' : ''; ?>">
								<label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
									<?php if ($error_keyword) { ?>
										<div class="text-danger"><?php echo $error_keyword; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group <?php echo in_array($type, array('special')) ? '' : 'hidden'; ?>">
								<label class="col-sm-2 control-label" for="input-link">Ссылка</label>
								<div class="col-sm-10">
									<input type="text" name="link" value="<?php echo $link; ?>" placeholder="Ссылка" id="input-link" class="form-control" />
									<?php if ($error_link) { ?>
										<div class="text-danger"><?php echo $error_link; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-10">
									<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
								</div>
							</div>
						</div>
						<!-- # DATA -->

						<!-- GALLERY -->
						<div class="tab-pane" id="tab-gallery">

							<?php 
							$gallery_list = array(
								1 => $gallery_1,
								2 => $gallery_2,
								3 => $gallery_3,
								4 => $gallery_4,
								5 => $gallery_5,
							);
							?>

							<?php foreach($gallery_list as $key=>$gallery) { ?>
								<fieldset>
									<legend>Галерея <?php echo $key; ?></legend>
									<div class="form-group">
										<label class="col-sm-2 control-label" >Код для использования</label>
										<div class="col-sm-10">
											<div class="well">
												<strong>[gallery_<?php echo $key; ?>]</strong>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Изображения</label>
										<div class="col-sm-10 sortable_gall">

											<?php if(!empty($gallery)) { ?>
												<?php foreach($gallery as $index=>$image) { ?>
													<div class="gall__item">
														<a href="" id="thumb-image-gallery-<?php echo $key; ?>-<?php echo $index; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
														<input type="hidden" name="gallery_<?php echo $key; ?>[]" value="<?php echo $image['image']; ?>" id="input-image-gallery-<?php echo $key; ?>-<?php echo $index; ?>" />
													</div>
												<?php } ?>
											<?php } ?>

											<button type="button" class="gall__item gall__add ui-state-disabled" data-id="<?php echo $key; ?>" data-number="<?php echo !empty($gallery) ? count($gallery) : '0'; ?>" id="gall-add-<?php echo $key; ?>" data-toggle="tooltip" title="Добавить изображение"></button>
										</div>
									</div>
								</fieldset>
							<?php } ?>

						</div>
						<!-- # GALLERY -->

						<!-- COLUMN -->
						<div class="tab-pane" id="tab-column">

							<ul class="panel__nav nav nav-tabs col-xs-12">
								<?php if($column_list){ ?>
									<?php foreach($column_list as $key=>$column){ ?>
										<li id="column-<?php echo $column['column_id']; ?>" class="<?php echo $key == 0 ? 'active' : ''; ?>"><a href="#column-pane-<?php echo $column['column_id']; ?>" data-toggle="tab" aria-expanded="true"><?php echo $column['column_id']; ?></a></li>
									<?php } ?>
								<?php } ?>
								<li id="column_btn"><button type="button" class="btn btn-block btn-primary" onclick="addColumn();">Добавить</button></li>   
							</ul>
							<div id="columns" class="tab-content col-xs-12">
								<?php $key_id = 101; ?>
								<?php 
								$sizes = array(
									'xs'	=> '0-509 px',
									'sm'	=> '509-767 px',
									'md'	=> '768-991 px',
									'lg'	=> '992-1269 px',
									'xl'	=> '1270-1599 px',
									'xlg'	=> '1600+ px',
								);
								?>
								<?php foreach($column_list as $key=>$column) { ?>
									<div class="tab-pane <?php echo $key == 0 ? 'active' : ''; ?>" id="column-pane-<?php echo $column['column_id']; ?>">
										<?php $key_id = $key_id <= $column['column_id'] ? $column['column_id'] + 1 : $key_id;  ?>
										<div class="form-group">
											<label class="col-sm-2 control-label" >Код для использования</label>
											<div class="col-sm-10">
												<div class="well">
													<strong>[column id=<?php echo $column['column_id']; ?>]</strong>
												</div>
											</div>
										</div>
										<div class="form-group ">
											<label class="col-sm-2 control-label" >Колонка 1</label>
											<div class="col-sm-10">
												<textarea name="column[<?php echo $column['column_id']; ?>][text_left]" placeholder="Колонка 1" id="input-column-<?php echo $column['column_id']; ?>-left" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo $column['text_left']; ?></textarea>
											</div>
										</div>
										<div class="form-group ">
											<label class="col-sm-2 control-label" >Колонка 2</label>
											<div class="col-sm-10">
												<textarea name="column[<?php echo $column['column_id']; ?>][text_right]" placeholder="Колонка 2" id="input-column-<?php echo $column['column_id']; ?>-right" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo $column['text_right']; ?></textarea>
											</div>
										</div>
										<fieldset>
											<legend>Размеры колонок (Слева - Справа)</legend>

											<?php foreach($sizes as $key_size=>$size) { ?>

												<div class="form-group">
													<label class="col-sm-2 control-label"><?php echo $size; ?></label>
													<div class="col-sm-2">
														<select name="column[<?php echo $column['column_id']; ?>][size][<?php echo $key_size; ?>][left]" class="form-control">
															<?php for ($x = 1; $x <= 12; $x++) { ?>
																<option value="<?php echo $x; ?>" <?php echo $x == $column['size'][$key_size]['left'] ? 'selected' : ''; ?>><?php echo $x; ?></option>
															<?php } ?>
														</select>
													</div>
													<div class="col-sm-2">
														<select name="column[<?php echo $column['column_id']; ?>][size][<?php echo $key_size; ?>][right]" class="form-control">
															<?php for ($x = 1; $x <= 12; $x++) { ?>
																<option value="<?php echo $x; ?>" <?php echo $x == $column['size'][$key_size]['right'] ? 'selected' : ''; ?>><?php echo $x; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>

											<?php } ?>

										</fieldset>
										<div class="form-group">
											<div class="col-sm-10 col-sm-push-2">
												<a href="#" class="btn btn-danger column_delete" data-id="<?php echo $column['column_id']; ?>">Удалить</a>
											</div>
										</div>
										<input type="hidden" name="column[<?php echo $column['column_id']; ?>][column_id]" value="<?php echo $column['column_id']; ?>" />
									</div>
								<?php } ?>
							</div>

							<script type="text/javascript">
								<?php if ($ckeditor) { ?>
									<?php if($column_list) { ?>
										<?php foreach($column_list as $column) { ?>
											ckeditorInit('input-column-<?php echo $column['column_id']; ?>-left', getURLVar('token'));
											ckeditorInit('input-column-<?php echo $column['column_id']; ?>-right', getURLVar('token'));
										<?php } ?>
									<?php } ?>
								<?php } ?>

							</script>
							<script>
								var $index = <?php echo $key_id; ?>;

								addColumn = function(){
									$html = '';
									$html += '<div class="tab-pane <?php echo $key == 0 ? 'active' : ''; ?>" id="column-pane-'+$index+'">';
									$html += '	<div class="form-group">';
									$html += '		<label class="col-sm-2 control-label" >Код для использования</label>';
									$html += '		<div class="col-sm-10">';
									$html += '			<div class="well">';
									$html += '				<strong>[column id='+$index+']</strong>';
									$html += '			</div>';
									$html += '		</div>';
									$html += '	</div>';
									$html += '	<div class="form-group ">';
									$html += '		<label class="col-sm-2 control-label" >Колонка 1</label>';
									$html += '		<div class="col-sm-10">';
									$html += '			<textarea name="column['+$index+'][text_left]" placeholder="Колонка 1" id="input-column-'+$index+'-left" data-lang="<?php echo $lang; ?>" class="form-control summernote"></textarea>';
									$html += '		</div>';
									$html += '	</div>';
									$html += '	<div class="form-group ">';
									$html += '		<label class="col-sm-2 control-label" >Колонка 2</label>';
									$html += '		<div class="col-sm-10">';
									$html += '			<textarea name="column['+$index+'][text_right]" placeholder="Колонка 2" id="input-column-'+$index+'-right" data-lang="<?php echo $lang; ?>" class="form-control summernote"></textarea>';
									$html += '		</div>';
									$html += '	</div>';

									$html += '	<fieldset>';
									$html += '		<legend>Размеры колонок (Слева - Справа)</legend>';

									<?php foreach($sizes as $key_size=>$size) { ?>

										$html += '			<div class="form-group">';
										$html += '				<label class="col-sm-2 control-label"><?php echo $size; ?></label>';
										$html += '				<div class="col-sm-2">';
										$html += '					<select name="column['+$index+'][size][<?php echo $key_size; ?>][left]" class="form-control">';
										<?php for ($x = 1; $x <= 12; $x++) { ?>
											$html += '							<option value="<?php echo $x; ?>" <?php echo $x == 6 ? "selected" : "";?>><?php echo $x; ?></option>';
										<?php } ?>
										$html += '					</select>';
										$html += '				</div>';
										$html += '				<div class="col-sm-2">';
										$html += '					<select name="column['+$index+'][size][<?php echo $key_size; ?>][right]" class="form-control">';
										<?php for ($x = 1; $x <= 12; $x++) { ?>
											$html += '							<option value="<?php echo $x; ?>" <?php echo $x == 6 ? "selected" : "";?>><?php echo $x; ?></option>';
										<?php } ?>
										$html += '					</select>';
										$html += '				</div>';
										$html += '			</div>';
									<?php } ?>

									$html += '		</fieldset>';

									$html += '	<div class="form-group">';
									$html += '		<div class="col-sm-10 col-sm-push-2">';
									$html += '			<a href="#" class="btn btn-danger column_delete" data-id="'+$index+'">Удалить</a>';
									$html += '		</div>';
									$html += '	</div>';
									$html += '	<input type="hidden" name="column['+$index+'][column_id]" value="'+$index+'" />';
									$html += '</div>';

									$button = '<li id="column-'+$index+'" class="show"><a href="#column-pane-'+$index+'" data-toggle="tab" aria-expanded="true">'+$index+'</a></li>';

									$('#columns').append($html);
									$('#column_btn').before($button);

									$('#column-'+$index+' a').trigger('click');

									$index++;

									<?php if($ckeditor)	{ ?>
										ckeditorInit('input-column-'+$index+'-left', getURLVar('token'));
										ckeditorInit('input-column-'+$index+'-right', getURLVar('token'));
									<?php }	?>
									start_summernote();
								}

								$(document).on('click', '.column_delete', function(e){
									e.preventDefault();
									if(confirm('Вы уверены? Не забудьте удалить код колонки из текста статьи!')){
										var $id = $(this).attr('data-id');
										$('#column-pane-'+$id).remove();
										$('#column-'+$id).remove();
										if($('.panel__nav li').not('#nav_button').length) {
											$('.panel__nav li').eq(0).find('a').trigger('click');
										}
									}

								})

							</script>						

						</div>
						<!-- # COLUMN -->

						<!-- DESIGN -->
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
											<td class="text-left">
												<select name="journal_layout[0]" class="form-control">
													<option value=""></option>
													<?php foreach ($layouts as $layout) { ?>
														<?php if (isset($journal_layout[0]) && $journal_layout[0] == $layout['layout_id']) { ?>
															<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- # DESIGN -->

						<!-- COPY -->
						<div class="tab-pane" id="tab-copy">
							<table id="copy" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<td class="text-left"><?php echo $entry_copy; ?></td>
										<td class="text-right" style="width: 200px;"><?php echo $entry_sort_order; ?></td>
										<td style="width: 80px;"></td>
									</tr>
								</thead>
								<tbody>
									<?php $copy_row = 0; ?>
									<?php $copy_sort = 0; ?>

									<?php if (!empty($copies)) { ?>
										<?php foreach ($copies as $copy) { ?>
											<?php $copy_sort = $copy['sort_order'] > $copy_sort ? $copy['sort_order'] : $copy_sort; ?>
											<tr id="copy-row<?php echo $copy_row; ?>">
												<td class="text-left">
													<input type="text" name="copy[<?php echo $copy_row; ?>][text]" value="<?php echo $copy['text']; ?>" placeholder="<?php echo $entry_copy; ?>" class="form-control" />
												</td>
												<td class="text-right" ><input type="text" name="copy[<?php echo $copy_row; ?>][sort_order]" value="<?php echo $copy['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
												<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger copy__remove"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $copy_row++; ?>
										<?php } ?>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2"></td>
										<td class="text-left"><button type="button" onclick="addCopy();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
									</tr>
								</tfoot>
							</table>
						</div>
						<!-- # COPY -->

						<!-- CASE -->
						<div class="tab-pane" id="tab-case">

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-case-title"><?php echo $case_title; ?></label>
								<div class="col-sm-10">
									<input type="text" name="case[title]" value="<?php echo $case['title']; ?>" placeholder="<?php echo $case_title; ?>" id="input-case-title" class="form-control" />
									<?php if ($error_case) { ?>
										<div class="text-danger"><?php echo $error_case; ?></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-case-template"><?php echo $case_template; ?></label>
								<div class="col-sm-10">
									<select name="case[template]" id="input-case-template" class="form-control">
										<option value="0" <?php echo $case['template'] == 0 ? 'selected' : ''; ?>><?php echo $case_template_simple; ?></option>
										<option value="1"  <?php echo $case['template'] == 1 ? 'selected' : ''; ?>><?php echo $case_template_full; ?></option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-case-template"><?php echo $case_bottom; ?></label>
								<div class="col-sm-10">
									<div class="btn-group btn-toggle" data-toggle="buttons">
										<label class="btn btn-primary <?php echo $case['bottom'] == 1 ? 'active' : ''; ?>"><input type="radio" name="case[bottom]" value="1"  <?php echo $case['bottom'] == 1 ? 'checked' : ''; ?>> <?php echo $text_yes; ?> </label>
										<label class="btn btn-default   <?php echo $case['bottom'] == 0 ? 'active' : ''; ?>"><input type="radio" name="case[bottom]" value="0"   <?php echo $case['bottom'] == 0 ? 'checked' : ''; ?>>  <?php echo $text_no; ?> </label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-case-description"><?php echo $case_description; ?></label>
								<div class="col-sm-10">
									<textarea name="case[description]" rows="5"  placeholder="<?php echo $case_description; ?>" id="input-case-description" class="form-control"><?php echo $case['description']; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-image-case"><?php echo $case_logo; ?></label>
								<div class="col-sm-10">
									<a href="" id="thumb-image-case" data-toggle="image" class="img-thumbnail"><img src="<?php echo $case['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
									<input type="hidden" name="case[logo]" value="<?php echo $case['logo']; ?>" id="input-image-case" />
								</div>
							</div>

							<table id="case" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<td class="text-left"><?php echo $entry_title; ?></td>
										<td class="text-left"><?php echo $case_text; ?></td>
										<td class="text-left" style="width: 160px;"><?php echo $case_catalog; ?></td>
										<td class="text-right" style="width: 160px;"><?php echo $entry_sort_order; ?></td>
										<td style="width: 80px;"></td>
									</tr>
								</thead>
								<tbody>
									<?php $case_row = 0; ?>
									<?php $case_sort = 0; ?>

									<?php if (!empty($case['attr'])) { ?>
										<?php foreach ($case['attr'] as $case_item) { ?>
											<?php $case_sort = $case_item['sort_order'] > $case_sort ? $case_item['sort_order'] : $case_sort; ?>
											<tr id="case-row<?php echo $case_row; ?>">
												<td class="text-left">
													<input type="text" name="case[attr][<?php echo $case_row; ?>][title]" value="<?php echo $case_item['title']; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
												</td>
												<td class="text-left">
													<input type="text" name="case[attr][<?php echo $case_row; ?>][text]" value="<?php echo $case_item['text']; ?>" placeholder="<?php echo $case_text; ?>" class="form-control" />
												</td>
												<td class="text-center">
													<div class="btn-group btn-toggle" data-toggle="buttons">
														<label class="btn btn-primary <?php echo $case_item['catalog'] == 1 ? 'active' : ''; ?>"><input type="radio" name="case[attr][<?php echo $case_row; ?>][catalog]" value="1"  <?php echo $case_item['catalog'] == 1 ? 'checked' : ''; ?>> <?php echo $text_yes; ?> </label>
														<label class="btn btn-default   <?php echo $case_item['catalog'] == 0 ? 'active' : ''; ?>"><input type="radio" name="case[attr][<?php echo $case_row; ?>][catalog]" value="0"   <?php echo $case_item['catalog'] == 0 ? 'checked' : ''; ?>>  <?php echo $text_no; ?> </label>
													</div>
												</td>
												<td class="text-right" ><input type="text" name="case[attr][<?php echo $case_row; ?>][sort_order]" value="<?php echo $case_item['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
												<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger case__remove"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $case_row++; ?>
										<?php } ?>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="4"></td>
										<td class="text-left"><button type="button" onclick="addCaseAttr();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
									</tr>
								</tfoot>
							</table>
						</div>
						<!-- # CASE -->


					</div>
				</div>
				<input type="hidden" name="type" value="<?php echo $type; ?>">
			</form>
		</div>
	</div>
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
		$gallery = $self.attr('data-id'),
		$number = parseInt($self.attr('data-number')) + 1,
		$html = '';

		$self.attr('data-number', $number);

		$html += '<div class="gall__item">';
		$html += '	<a href="" id="thumb-image-gallery-'+$gallery+'-'+$number+'" data-toggle="image" class="img-thumbnail">';
		$html += '	<img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>';
		$html += '	<input type="hidden" name="gallery_'+$gallery+'[]" value="" id="input-image-gallery-'+$gallery+'-'+$number+'" />';
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
	var copy_row = <?php echo $copy_row; ?>;
	var copy_sort = <?php echo $copy_sort+1; ?>;

	function addCopy() {
		html  = '<tr id="copy-row' + copy_row + '">';
		html += '  <td class="text-left"><input type="text" name="copy[' + copy_row + '][text]" value="" placeholder="<?php echo $entry_copy; ?>" class="form-control" /></td>';  
		html += '  <td class="text-right"><input type="text" name="copy[' + copy_row + '][sort_order]" value="'+copy_sort+'" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger copy__remove"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';

		$('#copy tbody').append(html);

		copy_row++;
		copy_sort++;
	}
	$(document).on('click', '.copy__remove', function(e) {
		e.preventDefault();
		if(confirm('<?php echo $entry_copy_remove; ?>')) {
			$(this).closest('tr').remove();
		}
	})
</script>
<script type="text/javascript">
	var case_row = <?php echo $case_row; ?>;
	var case_sort = <?php echo $case_sort+1; ?>;

	function addCaseAttr() {
		html  = '<tr id="case-row' + case_row + '">';
		html += '  <td class="text-left"><input type="text" name="case[attr][' + case_row + '][title]" value="" placeholder="<?php echo $entry_title; ?>" class="form-control" /></td>';  
		html += '  <td class="text-left"><input type="text" name="case[attr][' + case_row + '][text]" value="" placeholder="<?php echo $case_text; ?>" class="form-control" /></td>';  
		html += '  <td class="text-center">';
		html += '			<div class="btn-group btn-toggle" data-toggle="buttons">';
		html += '				<label class="btn btn-primary"><input type="radio" name="case[attr][' + case_row + '][catalog]" value="1" > <?php echo $text_yes; ?> </label>';
		html += '				<label class="btn btn-default active"><input type="radio" name="case[attr][' + case_row + '][catalog]" value="0" checked>  <?php echo $text_no; ?> </label>';
		html += '			</div></td>'; 
		html += '  <td class="text-right"><input type="text" name="case[attr][' + case_row + '][sort_order]" value="'+case_sort+'" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger case__remove"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';

		$('#case tbody').append(html);

		case_row++;
		case_sort++;
	}
	$(document).on('click', '.case__remove', function(e) {
		e.preventDefault();
		if(confirm('<?php echo $case_remove; ?>')) {
			$(this).closest('tr').remove();
		}
	})
</script> 
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


<script type="text/javascript">

	var config_language_id = <?php echo $config_language_id; ?>;  

	<?php if(stristr($_GET['route'], 'add')) { ?>

		$('#input-title' + config_language_id).change(function(){ generateUrlOnAdd(); });  

		function generateUrlOnAdd() {
			data = {
				name           : $('#input-title' + config_language_id).val(),
				essence        : 'journal',
				journal_id : ''
			};

			getSeoUrl(data);
		}

	<?php } else { ?>

		$('#input-keyword').after('<br><button id="generateUrlOnEdit" class="btn btn-success">GENERATE SEO URL</button>');

		$('#generateUrlOnEdit').click(function(e){
			e.preventDefault();

			data = {
				name           : $('#input-title' + config_language_id).val(),
				essence        : 'journal',
				journal_id : <?php echo $_GET['journal_id']; ?>
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
<script>

	start_summernote = function(){
		$('.summernote').each(function() {
			var element = this;
			var lang = $(element).data('lang');

			if (typeof(lang) == 'undefined') {
				lang = 'en-US';
			}

			$(element).summernote({
				disableDragAndDrop: true,
				height: 300,
				lang: lang,
				emptyPara: '',
				toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'image', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
				],
				buttons: {
					image: function() {
						var ui = $.summernote.ui;

						var button = ui.button({
							contents: '<i class="note-icon-picture" />',
							tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
							click: function () {
								$('#modal-image').remove();

								$.ajax({
									url: 'index.php?route=common/filemanager&token=' + getURLVar('token'),
									dataType: 'html',
									beforeSend: function() {
										$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
										$('#button-image').prop('disabled', true);
									},
									complete: function() {
										$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
										$('#button-image').prop('disabled', false);
									},
									success: function(html) {
										$('body').append('<div id="modal-image" class="modal">' + html + '</div>');

										$('#modal-image').modal('show');

										$('#modal-image').delegate('a.thumbnail', 'click', function(e) {
											e.preventDefault();

											$(element).summernote('insertImage', $(this).attr('href'));

											$('#modal-image').modal('hide');
										});
									}
								});						
							}
						});

						return button.render();
					}
				}
			});
		});
	}
</script>
<style>
	.btn-group .btn-default.active{
		color: #fff;
		background-color: #f56b6b;
		border-color: #f24545;
	}
	.btn-group .btn-primary{
		color: #333;
		background-color: #e6e6e6;
		border-color: #adadad;
	}
	.btn-group .btn-primary.active{
		color: #fff;
		background-color: #75a74d;
		border-color: #5c843d;
	}
</style>
<?php echo $footer; ?>
