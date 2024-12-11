<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-master" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-master" class="form-horizontal">
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
												<input type="text" name="master_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($master_description[$language['language_id']]) ? $master_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group ">
											<label class="col-sm-2 control-label" for="input-preview<?php echo $language['language_id']; ?>"><?php echo $entry_preview; ?></label>
											<div class="col-sm-10">
												<textarea name="master_description[<?php echo $language['language_id']; ?>][preview]" rows="3" placeholder="<?php echo $entry_preview; ?>" id="input-preview<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo isset($master_description[$language['language_id']]) ? $master_description[$language['language_id']]['preview'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group ">
											<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
											<div class="col-sm-10">
												<textarea name="master_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo isset($master_description[$language['language_id']]) ? $master_description[$language['language_id']]['description'] : ''; ?></textarea>
												<?php if (isset($error_description[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group hidden">
											<label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
											<div class="col-sm-10">
												<input type="text" name="master_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($master_description[$language['language_id']]) ? $master_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group hidden">
											<label class="col-sm-2 control-label" for="input-meta-h1<?php echo $language['language_id']; ?>"><?php echo $entry_meta_h1; ?></label>
											<div class="col-sm-10">
												<input type="text" name="master_description[<?php echo $language['language_id']; ?>][meta_h1]" value="<?php echo isset($master_description[$language['language_id']]) ? $master_description[$language['language_id']]['meta_h1'] : ''; ?>" placeholder="<?php echo $entry_meta_h1; ?>" id="input-meta-h1<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group hidden">
											<label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
											<div class="col-sm-10">
												<textarea name="master_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($master_description[$language['language_id']]) ? $master_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group hidden">
											<label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
											<div class="col-sm-10">
												<textarea name="master_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($master_description[$language['language_id']]) ? $master_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="tab-pane" id="tab-data">

							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-link"><?php echo $entry_link; ?></label>
								<div class="col-sm-10">
									<input type="text" name="link" value="<?php echo $link; ?>" placeholder="<?php echo $entry_link; ?>" id="input-link" class="form-control" />
									<?php if ($error_link) { ?>
										<div class="text-danger"><?php echo $error_link; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
								<div class="col-sm-4">
									<div class="btn-group btn-toggle" data-toggle="buttons">
										<label class="btn btn-primary <?php echo $type === 'master' ? 'active' : ''; ?>"><input type="radio" name="type" value="master"  <?php echo $type === 'master' ? 'checked' : ''; ?>> Мастер-класс </label>
										<label class="btn btn-primary   <?php echo $type === 'meetup' ? 'active' : ''; ?>"><input type="radio" name="type" value="meetup"   <?php echo $type === 'meetup' ? 'checked' : ''; ?>>  Митап </label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
								<div class="col-sm-10">
									<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
									<input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
								</div>
							</div>
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-logo"><?php echo $entry_logo; ?></label>
								<div class="col-sm-10">
									<a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_logo; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
									<input type="hidden" name="logo" value="<?php echo $logo; ?>" id="input-logo" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-date-available"><?php echo $entry_date_available; ?></label>
								<div class="col-sm-4">
									<div class="input-group datetime">
										<input type="text" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" id="input-date-available" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span></div>
									</div>
								</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="duration">Продолжительность:</label>
								<div class="col-sm-3">
									<div class="input-group time">
										<input type="text" name="duration" value="<?php echo $duration; ?>" placeholder="<?php echo $duration; ?>" data-time-format="HH:mm" id="duration" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>

							</div>
							<div class="form-group">
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
								<div class="form-group">
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
								</div>
								<?php require_once(DIR_TEMPLATE . 'visitor/visitor-autocomplete.tpl'); ?>
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
											<input type="text" name="authors_search" class="author__input" placeholder="<?php echo $entry_experts_placeholder; ?>" data-name="experts">
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $entry_company; ?></label>
									<div class="col-sm-10">
										<div class="company__cont">
											<ul class="company__list sortable">
												<?php if(!empty($companies)) { ?>
													<?php foreach($companies as $company) { ?>
														<li class="company__item company__item-<?php echo $company['company_id']; ?>" >
															<?php echo $company['title']; ?>
															<button type="button" class="company__remove" ><i class="fa fa-close"></i></button>
															<input type="hidden" name="company[]" value="<?php echo $company['company_id']; ?>">
														</li>
													<?php } ?>
												<?php } ?>
											</ul>
											<input type="text" name="companies_search" class="companies__input" placeholder="<?php echo $entry_company_placeholder; ?>" autocomplete="off">
										</div>
									</div>
									<?php require_once(DIR_TEMPLATE . 'company/company-autocomplete.tpl'); ?>
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
												<td class="text-left"><select name="master_layout[0]" class="form-control">
													<option value=""></option>
													<?php foreach ($layouts as $layout) { ?>
														<?php if (isset($master_layout[0]) && $master_layout[0] == $layout['layout_id']) { ?>
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
					ckeditorInit('input-preview<?php echo $language['language_id']; ?>', getURLVar('token'));
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
					essence        : 'master',
					master_id : ''
				};

				getSeoUrl(data);
			}

		<?php } else { ?>

			$('#input-keyword').after('<br><button id="generateUrlOnEdit" class="btn btn-success">GENERATE SEO URL</button>');

			$('#generateUrlOnEdit').click(function(e){
				e.preventDefault();

				data = {
					name           : $('#input-title' + config_language_id).val(),
					essence        : 'master',
					master_id : <?php echo $_GET['master_id']; ?>
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

			$('.time').datetimepicker({
			pickDate: false,
			pickTime: true,
			icons: {
			time: 'fa fa-clock-o',
			date: 'fa fa-calendar',
			up: 'fa fa-sort-up',
			down: 'fa fa-sort-down'
		}
		});
	</script>

	<style>
		.btn-toggle .btn:not(.active){
			color: #333;
			background-color: #e6e6e6;
			border-color: #adadad;
		}
	</style>
	<?php echo $footer; ?>
