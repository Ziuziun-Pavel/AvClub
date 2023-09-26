<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-event" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-event" class="form-horizontal" autocomplete="off">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
						<li><a href="#tab-content" data-toggle="tab"><?php echo $tab_content; ?></a></li>
					</ul>
					<div class="tab-content">
						<?php /* GENERAL */ ?>
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
												<input type="text" name="event_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group hidden">
											<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
											<div class="col-sm-10">
												<textarea name="event_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['description'] : ''; ?></textarea>
												<?php if (isset($error_description[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
											<div class="col-sm-10">
												<input type="text" name="event_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-h1<?php echo $language['language_id']; ?>"><?php echo $entry_meta_h1; ?></label>
											<div class="col-sm-10">
												<input type="text" name="event_description[<?php echo $language['language_id']; ?>][meta_h1]" value="<?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['meta_h1'] : ''; ?>" placeholder="<?php echo $entry_meta_h1; ?>" id="input-meta-h1<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
											<div class="col-sm-10">
												<textarea name="event_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
											<div class="col-sm-10">
												<textarea name="event_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($event_description[$language['language_id']]) ? $event_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<?php /* # GENERAL */ ?>

						<?php /* DATA */ ?>
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
								<div class="col-sm-10">
									<select name="type_id" class="form-control">
										<option value="0" selected="selected"><?php echo $text_none; ?></option>
										<?php foreach($types as $type) { ?>
											<?php if($type['type_id'] == $type_id) { ?>
												<option value="<?php echo $type['type_id']; ?>" selected="selected"><?php echo $type['title']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $type['type_id']; ?>"><?php echo $type['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-city"><?php echo $entry_city; ?></label>
								<div class="col-sm-10">
									<select name="city_id" class="form-control">
										<option value="0" selected="selected"><?php echo $text_none; ?></option>
										<?php foreach($cities as $city) { ?>
											<?php if($city['city_id'] == $city_id) { ?>
												<option value="<?php echo $city['city_id']; ?>" selected="selected"><?php echo $city['title']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $city['city_id']; ?>"><?php echo $city['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
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
								<label class="col-sm-2 control-label" for="input-image-full"><?php echo $entry_image_full; ?></label>
								<div class="col-sm-10">
									<a href="" id="thumb-image-full" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_full; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
									<input type="hidden" name="image_full" value="<?php echo $image_full; ?>" id="input-image-full" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-date"><?php echo $entry_date; ?></label>
								<div class="col-sm-3">
									<div class="input-group date">
										<input type="text" name="date" value="<?php echo $date; ?>" placeholder="<?php echo $entry_date; ?>" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
									<?php if ($error_date_start) { ?>
										<div class="text-danger"><?php echo $error_date_start; ?></div>
									<?php } ?>
								</div>
								<div class="col-sm-3">
									<div class="input-group date">
										<input type="text" name="date_stop" value="<?php echo $date_stop; ?>" placeholder="<?php echo $entry_date; ?>" data-date-format="YYYY-MM-DD" id="input-date_stop" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
									<?php if ($error_date_stop) { ?>
										<div class="text-danger"><?php echo $error_date_stop; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-time_start"><?php echo $entry_time; ?></label>
								<div class="col-sm-3">
									<div class="input-group time">
										<input type="text" name="time_start" value="<?php echo $time_start; ?>" placeholder="<?php echo $entry_time_start; ?>" data-time-format="HH:mm" id="input-time_start" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="input-group time">
										<input type="text" name="time_end" value="<?php echo $time_end; ?>" placeholder="<?php echo $entry_time_end; ?>" data-time-format="HH:mm" id="input-time_end" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
								<div class="col-sm-10">
									<input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-count"><?php echo $entry_count; ?></label>
								<div class="col-sm-10">
									<input type="text" name="count" value="<?php echo $count; ?>" placeholder="<?php echo $entry_count; ?>" id="input-count" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
								<div class="col-sm-10">
									<input type="text" name="address" value="<?php echo $address; ?>" placeholder="<?php echo $entry_address; ?>" id="input-address" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-address_full"><?php echo $entry_address_full; ?></label>
								<div class="col-sm-10">
									<input type="text" name="address_full" value="<?php echo $address_full; ?>" placeholder="<?php echo $entry_address_full; ?>" id="input-address_full" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-coord"><span data-toggle="tooltip" title="<?php echo $help_coord; ?>"><?php echo $entry_coord; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="coord" value="<?php echo $coord; ?>" placeholder="54.718681,20.499113" id="input-coord" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-old"><span data-toggle="tooltip" title="<?php echo $help_old; ?>"><?php echo $entry_old; ?></span></label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-btn _btn-group btn-toggle" data-toggle="buttons">
											<label class="btn btn-danger <?php echo !$old_type ? 'active' : ''; ?>"><input type="radio" name="old_type" value=""  <?php echo !$old_type ? 'checked' : ''; ?>> <?php echo $text_old_none; ?> </label>
											<label class="btn btn-success <?php echo $old_type === 'page' ? 'active' : ''; ?>"><input type="radio" name="old_type" value="page"   <?php echo $old_type === 'page' ? 'checked' : ''; ?>>  <?php echo $text_old_page; ?> </label>
											<label class="btn btn-warning <?php echo $old_type === 'link' ? 'active' : ''; ?>"><input type="radio" name="old_type" value="link"   <?php echo $old_type === 'link' ? 'checked' : ''; ?>>  <?php echo $text_old_link; ?> </label>
										</div>
										<input type="text" name="old_link" value="<?php echo $old_link; ?>" placeholder="<?php echo $entry_old_link; ?>" class="form-control" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-show"><span data-toggle="tooltip" title="<?php echo $help_show; ?>"><?php echo $entry_show; ?></span></label>
								<div class="col-sm-10">
									<div class="btn-group btn-toggle" data-toggle="buttons">
										<label class="btn btn-success <?php echo $show_event == 1 ? 'active' : ''; ?>"><input type="radio" name="show_event" value="1"   <?php echo $show_event == 1 ? 'checked' : ''; ?>>  <?php echo $text_enabled; ?> </label>
										<label class="btn btn-danger <?php echo $show_event == 0 ? 'active' : ''; ?>"><input type="radio" name="show_event" value="0"  <?php echo $show_event == 0 ? 'checked' : ''; ?>> <?php echo $text_disabled; ?> </label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
								<div class="col-sm-10">
									<div class="btn-group btn-toggle" data-toggle="buttons">
										<label class="btn btn-success <?php echo $status == 1 ? 'active' : ''; ?>"><input type="radio" name="status" value="1"   <?php echo $status == 1 ? 'checked' : ''; ?>>  <?php echo $text_enabled; ?> </label>
										<label class="btn btn-danger <?php echo $status == 0 ? 'active' : ''; ?>"><input type="radio" name="status" value="0"  <?php echo $status == 0 ? 'checked' : ''; ?>> <?php echo $text_disabled; ?> </label>
									</div>
									<?php /* ?>
									<select name="status" id="input-status" class="form-control">
										<?php if ($status) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
									<?php */ ?>
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
						<?php /* # DATA */ ?>

						<?php /* CONTENT */ ?>
						<div class="tab-pane" id="tab-content">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab-tpl-template" data-toggle="tab"><?php echo $tab_template; ?></a></li>
								<li><a href="#tab-tpl-video" data-toggle="tab"><?php echo $tab_media; ?></a></li>
								<li><a href="#tab-tpl-brand" data-toggle="tab"><?php echo $tab_brand; ?></a></li>
								<li><a href="#tab-tpl-plus" data-toggle="tab"><?php echo $tab_plus; ?></a></li>
								<li><a href="#tab-tpl-insta" data-toggle="tab"><?php echo $tab_insta; ?></a></li>
								<li><a href="#tab-tpl-prg" data-toggle="tab"><?php echo $tab_prg; ?></a></li>
								<li><a href="#tab-tpl-speaker" data-toggle="tab"><?php echo $tab_speaker; ?></a></li>
								<li><a href="#tab-tpl-present" data-toggle="tab"><?php echo $tab_present; ?></a></li>
								<li><a href="#tab-tpl-ask" data-toggle="tab"><?php echo $tab_ask; ?></a></li>
							</ul>
							<div class="tab-content">

								<?php /* TEMPLATE */ ?>
								<div class="tab-pane active" id="tab-tpl-template">
									<div class="form-group">
										<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_sort; ?>"><?php echo $tab_template; ?></span></label>
										<div class="col-sm-10 col-md-8 col-lg-6">
											<ul class="template sortable">
												<?php foreach($template as $key=>$item) { ?>
													<li class="tpl__item <?php echo $item['disabled'] ? 'ui-state-disabled' : ''; ?>">
														<div class="tpl__name">
															<?php echo $item['title']; ?>
														</div>
														<div class="tpl__status">
															<div class="btn-group btn-toggle" data-toggle="buttons">
																<label class="btn btn-success <?php echo $item['status'] == 1 ? 'active' : ''; ?>"><input type="radio" name="template[<?php echo $key; ?>]" value="1"   <?php echo $item['status'] == 1 ? 'checked' : ''; ?>>  <?php echo $entry_template_on; ?> </label>
																<label class="btn btn-danger <?php echo $item['status'] == 0 ? 'active' : ''; ?>"><input type="radio" name="template[<?php echo $key; ?>]" value="0"  <?php echo $item['status'] == 0 ? 'checked' : ''; ?>> <?php echo $entry_template_off; ?> </label>
															</div>
														</div>
													</li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
								<style>
									.tpl__item{
										display: flex;
										align-items: center;
										border-radius: 5px;
										width: 100%;
										list-style: none;
										padding: 5px 10px;
										background: #f9f9f9;
										margin-bottom: 5px;
										position: relative;
										min-height: 45px;
										cursor: pointer;
										border: 1px dashed #d4d3d3;
										transition: .2s;
									}
									.tpl__item:hover{
										background: #f3f1f1;
									}
									.tpl__item.ui-state-disabled{
										opacity: .5;
										cursor: default;
									}
									.tpl__item.ui-state-disabled .tpl__status{
										display: none;
									}
									.tpl__name{
										font-weight: 700;
										padding-right: 15px;
										font-size: 110%;
									}
									.tpl__status{
										margin-left: auto;
									}
								</style>
								<?php /* # TEMPLATE */ ?>

								<?php /* VIDEO */ ?>
								<div class="tab-pane" id="tab-tpl-video">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-video_image"><?php echo $entry_video_image; ?></label>
										<div class="col-sm-10">
											<a href="" id="thumb-video_image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_video; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
											<input type="hidden" name="video_image" value="<?php echo $video_image; ?>" id="input-video_image" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-video"><?php echo $entry_video; ?></label>
										<div class="col-sm-10">
											<input type="text" name="video" value="<?php echo $video; ?>" placeholder="<?php echo $entry_video; ?>" id="input-video" class="form-control" />
										</div>
									</div>
								</div>
								<?php /* # VIDEO */ ?>


								<?php /* BRAND */ ?>
								<div class="tab-pane" id="tab-tpl-brand">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-brand_title"><?php echo $entry_title; ?></label>
										<div class="col-sm-10">
											<input type="text" name="brand_title" value="<?php echo $brand_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-brand_title" class="form-control" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-brand_template"><?php echo $entry_template; ?></label>
										<div class="col-sm-10">
											<div class="btn-group btn-toggle" data-toggle="buttons">
												<label class="btn btn-primary   <?php echo $brand_template == 0 ? 'active' : ''; ?>"><input type="radio" name="brand_template" value="0"   <?php echo $brand_template == 0 ? 'checked' : ''; ?>>  <?php echo $entry_template_list; ?> </label>
												<label class="btn btn-primary <?php echo $brand_template == 1 ? 'active' : ''; ?>"><input type="radio" name="brand_template" value="1"  <?php echo $brand_template == 1 ? 'checked' : ''; ?>> <?php echo $entry_template_slider; ?> </label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_sort; ?>"><?php echo $entry_company; ?></span></label>
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
								</div>
								<?php /* # BRAND */ ?>


								<?php /* PLUS */ ?>
								<div class="tab-pane" id="tab-tpl-plus">
									<table id="plus" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<td class="text-left"  style="width: 110px;min-width: 110px;"><?php echo $entry_image; ?></td>
												<td class="text-left" ><?php echo $entry_title; ?></td>
												<td class="text-left"><?php echo $entry_description; ?></td>
												<td class="text-center" style="width: 100px;min-width: 100px;"><?php echo $entry_sort_order; ?></td>
												<td style="width: 50px;min-width: 50px;"></td>
											</tr>
										</thead>
										<tbody>
											<?php $plus_row = 0; ?>
											<?php $plus_sort = 0; ?>

											<?php if (!empty($pluses)) { ?>
												<?php foreach ($pluses as $plus_item) { ?>
													<?php $plus_sort = $plus_item['sort_order'] > $plus_sort ? $plus_item['sort_order'] : $plus_sort; ?>
													<tr id="plus-row<?php echo $plus_row; ?>">
														<td class="text-left">
															<a href="" id="thumb-image-plus-<?php echo $plus_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $plus_item['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
															<input type="hidden" name="plus[<?php echo $plus_row; ?>][image]" value="<?php echo $plus_item['image']; ?>" id="input-image-plus-<?php echo $plus_row; ?>" />
														</td>
														<td class="text-left">
															<input name="plus[<?php echo $plus_row; ?>][title]" rows="4" placeholder="<?php echo $entry_title; ?>" class="form-control" value="<?php echo $plus_item['title']; ?>">
														</td>
														<td class="text-left">
															<textarea name="plus[<?php echo $plus_row; ?>][text]" rows="4" placeholder="<?php echo $entry_description; ?>" class="form-control"><?php echo $plus_item['text']; ?></textarea>
														</td>
														<td class="text-center" ><input type="text" name="plus[<?php echo $plus_row; ?>][sort_order]" value="<?php echo $plus_item['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
														<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger plus__remove"><i class="fa fa-minus-circle"></i></button></td>
													</tr>
													<?php $plus_row++; ?>
												<?php } ?>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4"></td>
												<td class="text-left"><button type="button" onclick="addPlus();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
											</tr>
										</tfoot>
									</table>
								</div>
								<script type="text/javascript">
									var plus_row = <?php echo $plus_row; ?>;
									var plus_sort = <?php echo $plus_sort+1; ?>;

									function addPlus() {

										html  = '<tr id="plus-row' + plus_row + '">';
										html  += '	<td class="text-left">';
										html  += '		<a href="" id="thumb-image-plus-' + plus_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>';
										html  += '		<input type="hidden" name="plus[' + plus_row + '][image]" value="" id="input-image-plus-' + plus_row + '" />';
										html  += '	</td>';
										html  += '	<td class="text-left">';
										html  += '		<input name="plus[' + plus_row + '][title]" rows="4" placeholder="<?php echo $entry_title; ?>" class="form-control" value="">';
										html  += '	</td>';
										html  += '	<td class="text-left">';
										html  += '		<textarea name="plus[' + plus_row + '][text]" rows="4" placeholder="<?php echo $entry_description; ?>" class="form-control"></textarea>';
										html  += '	</td>';
										html  += '	<td class="text-center" ><input type="text" name="plus[' + plus_row + '][sort_order]" value="' + plus_sort + '" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
										html  += '	<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger plus__remove"><i class="fa fa-minus-circle"></i></button></td>';
										html  += '</tr>';

										$('#plus tbody').append(html);

										plus_row++;
										plus_sort++;
									}
									$(document).on('click', '.plus__remove', function(e) {
										e.preventDefault();
										if(confirm('<?php echo $confirm_remove; ?>')) {
											$(this).closest('tr').remove();
										}
									})
								</script> 
								<?php /* # PLUS */ ?>


								<?php /* INSTA */ ?>
								<div class="tab-pane" id="tab-tpl-insta">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-insta_title"><?php echo $entry_title; ?></label>
										<div class="col-sm-10">
											<input type="text" name="insta_title" value="<?php echo $insta_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-insta_title" class="form-control" />
										</div>
									</div>
									<table id="insta" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<td class="text-left"  style="width: 110px;min-width: 110px;"><?php echo $entry_image; ?></td>
												<td class="text-left" ><?php echo $entry_href; ?></td>
												<td class="text-center" style="width: 100px;min-width: 100px;"><?php echo $entry_sort_order; ?></td>
												<td style="width: 50px;min-width: 50px;"></td>
											</tr>
										</thead>
										<tbody>
											<?php $insta_row = 0; ?>
											<?php $insta_sort = 0; ?>

											<?php if (!empty($insta_list)) { ?>
												<?php foreach ($insta_list as $insta_item) { ?>
													<?php $insta_sort = $insta_item['sort_order'] > $insta_sort ? $insta_item['sort_order'] : $insta_sort; ?>
													<tr id="insta-row<?php echo $insta_row; ?>">
														<td class="text-left">
															<a href="" id="thumb-image-insta-<?php echo $insta_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $insta_item['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
															<input type="hidden" name="insta[<?php echo $insta_row; ?>][image]" value="<?php echo $insta_item['image']; ?>" id="input-image-insta-<?php echo $insta_row; ?>" />
														</td>
														<td class="text-left">
															<input name="insta[<?php echo $insta_row; ?>][href]" rows="4" placeholder="<?php echo $entry_href; ?>" class="form-control" value="<?php echo $insta_item['href']; ?>">
														</td>
														<td class="text-center" ><input type="text" name="insta[<?php echo $insta_row; ?>][sort_order]" value="<?php echo $insta_item['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
														<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger insta__remove"><i class="fa fa-minus-circle"></i></button></td>
													</tr>
													<?php $insta_row++; ?>
												<?php } ?>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3"></td>
												<td class="text-left"><button type="button" onclick="addInsta();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
											</tr>
										</tfoot>
									</table>
								</div>
								<script type="text/javascript">
									var insta_row = <?php echo $insta_row; ?>;
									var insta_sort = <?php echo $insta_sort+1; ?>;

									function addInsta() {

										html  = '<tr id="insta-row' + insta_row + '">';
										html  += '	<td class="text-left">';
										html  += '		<a href="" id="thumb-image-insta-' + insta_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>';
										html  += '		<input type="hidden" name="insta[' + insta_row + '][image]" value="" id="input-image-insta-' + insta_row + '" />';
										html  += '	</td>';
										html  += '	<td class="text-left">';
										html  += '		<input name="insta[' + insta_row + '][href]" rows="4" placeholder="<?php echo $entry_href; ?>" class="form-control" value="">';
										html  += '	</td>';
										html  += '	<td class="text-center" ><input type="text" name="insta[' + insta_row + '][sort_order]" value="' + insta_sort + '" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
										html  += '	<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger insta__remove"><i class="fa fa-minus-circle"></i></button></td>';
										html  += '</tr>';

										$('#insta tbody').append(html);

										insta_row++;
										insta_sort++;
									}
									$(document).on('click', '.insta__remove', function(e) {
										e.preventDefault();
										if(confirm('<?php echo $confirm_remove; ?>')) {
											$(this).closest('tr').remove();
										}
									})
								</script> 
								<?php /* # INSTA */ ?>


								<?php /* PRG */ ?>
								<div class="tab-pane" id="tab-tpl-prg">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-prg_title"><?php echo $entry_title; ?></label>
										<div class="col-sm-10">
											<input type="text" name="prg_title" value="<?php echo $prg_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-prg_title" class="form-control" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><?php echo $entry_file; ?></label>
										<div class="col-sm-10">
											<div class="file__cont">
												<ul class="file__list">
													<?php if($prg_file) { ?>
														<li class="file__item file__item-<?php echo $prg_file['download_id']; ?>">
															<?php echo $prg_file['name']; ?>
															<button type="button" class="file__remove" ><i class="fa fa-close"></i></button>
															<input type="hidden" name="file_id" value="<?php echo $prg_file['download_id']; ?>">
														</li>
													<?php } ?>
												</ul>
												<input type="text" name="file_search" class="file__input" placeholder="<?php echo $entry_file_placeholder; ?>">
											</div>
										</div>
										<?php require_once(DIR_TEMPLATE . 'catalog/download-autocomplete.tpl'); ?>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-prg_template"><?php echo $entry_template; ?></label>
										<div class="col-sm-10">
											<div class="btn-group btn-toggle" data-toggle="buttons">
												<label class="btn btn-primary   <?php echo $prg_template == 0 ? 'active' : ''; ?>"><input type="radio" name="prg_template" value="0"   <?php echo $prg_template == 0 ? 'checked' : ''; ?>>  <?php echo $entry_template_image_disable; ?> </label>
												<label class="btn btn-primary <?php echo $prg_template == 1 ? 'active' : ''; ?>"><input type="radio" name="prg_template" value="1"  <?php echo $prg_template == 1 ? 'checked' : ''; ?>> <?php echo $entry_template_image; ?> </label>
											</div>
										</div>
									</div>
									<table id="prg" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<td class="text-left" ><?php echo $entry_title; ?></td>
												<td class="text-left"  style="width: 110px;min-width: 110px;"><?php echo $entry_image; ?></td>
												<td class="text-left" style="width: 150px;min-width: 150px;"><?php echo $entry_time; ?></td>
												<td class="text-center" style="width: 100px;min-width: 100px;"><?php echo $entry_sort_order; ?></td>
												<td style="width: 50px;min-width: 50px;"></td>
											</tr>
										</thead>
										<tbody>
											<?php $prg_row = 0; ?>
											<?php $prg_sort = 0; ?>

											<?php if (!empty($prg)) { ?>
												<?php foreach ($prg as $prg_item) { ?>
													<?php $prg_sort = $prg_item['sort_order'] > $prg_sort ? $prg_item['sort_order'] : $prg_sort; ?>
													<tr id="prg-row<?php echo $prg_row; ?>">
														<td class="text-left">
															<input name="prg[<?php echo $prg_row; ?>][title]" rows="4" placeholder="<?php echo $entry_title; ?>" class="form-control" value="<?php echo $prg_item['title']; ?>">
															<br><label><?php echo $entry_description; ?></label>
															<textarea name="prg[<?php echo $prg_row; ?>][text]" rows="4" placeholder="<?php echo $entry_description; ?>" class="form-control"><?php echo $prg_item['text']; ?></textarea>
														</td>
														<td class="text-left">
															<a href="" id="thumb-image-prg-<?php echo $prg_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $prg_item['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
															<input type="hidden" name="prg[<?php echo $prg_row; ?>][image]" value="<?php echo $prg_item['image']; ?>" id="input-image-prg-<?php echo $prg_row; ?>" />
														</td>
														<td class="text-left">
															<label><?php echo $entry_time_start; ?></label>
															<div class="input-group time">
																<input type="text" name="prg[<?php echo $prg_row; ?>][time_start]" value="<?php echo $prg_item['time_start']; ?>" placeholder="<?php echo $entry_time_start; ?>" data-time-format="HH:mm" class="form-control" />
																<span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button></span>
															</div>
															<br><label><?php echo $entry_time_end; ?></label>
															<div class="input-group time">
																<input type="text" name="prg[<?php echo $prg_row; ?>][time_end]" value="<?php echo $prg_item['time_end']; ?>" placeholder="<?php echo $entry_time_end; ?>" data-time-format="HH:mm" class="form-control" />
																<span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button></span>
															</div>
														</td>
														<td class="text-center" ><input type="text" name="prg[<?php echo $prg_row; ?>][sort_order]" value="<?php echo $prg_item['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
														<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger prg__remove"><i class="fa fa-minus-circle"></i></button></td>
													</tr>
													<?php $prg_row++; ?>
												<?php } ?>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4"></td>
												<td class="text-left"><button type="button" onclick="addPrg();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
											</tr>
										</tfoot>
									</table>
									<script type="text/javascript">
										var prg_row = <?php echo $prg_row; ?>;
										var prg_sort = <?php echo $prg_sort+1; ?>;

										function addPrg() {

											html  = '<tr id="prg-row' + prg_row + '">';
											html  += '	<td class="text-left">';
											html  += '		<input name="prg[' + prg_row + '][title]" rows="4" placeholder="<?php echo $entry_title; ?>" class="form-control" value="">';
											html  += '		<br><label><?php echo $entry_description; ?></label>';
											html  += '		<textarea name="prg[' + prg_row + '][text]" rows="4" placeholder="<?php echo $entry_description; ?>" class="form-control"></textarea>';
											html  += '	</td>';
											html  += '	<td class="text-left">';
											html  += '		<a href="" id="thumb-image-prg-' + prg_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>';
											html  += '		<input type="hidden" name="prg[' + prg_row + '][image]" value="" id="input-image-prg-' + prg_row + '" />';
											html  += '	</td>';
											html  += '	<td class="text-left">';
											html  += '		<label><?php echo $entry_time_start; ?></label>';
											html  += '		<div class="input-group time">';
											html  += '			<input type="text" name="prg[' + prg_row + '][time_start]" value="09:00" placeholder="<?php echo $entry_time_start; ?>" data-time-format="HH:mm" class="form-control" />';
											html  += '			<span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button></span>';
											html  += '		</div>';
											html  += '		<br><label><?php echo $entry_time_end; ?></label>';
											html  += '		<div class="input-group time">';
											html  += '			<input type="text" name="prg[' + prg_row + '][time_end]" value="10:00" placeholder="<?php echo $entry_time_end; ?>" data-time-format="HH:mm" class="form-control" />';
											html  += '			<span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button></span>';
											html  += '		</div>';
											html  += '	</td>';
											html  += '	<td class="text-center" ><input type="text" name="prg[' + prg_row + '][sort_order]" value="' + prg_sort + '" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
											html  += '	<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger prg__remove"><i class="fa fa-minus-circle"></i></button></td>';
											html  += '</tr>';

											$('#prg tbody').append(html);

											$('.prg-row' + prg_row + ' .time').datetimepicker({
												pickDate: false,
												pickTime: true,
												icons: {
													time: 'fa fa-clock-o',
													date: 'fa fa-calendar',
													up: 'fa fa-sort-up',
													down: 'fa fa-sort-down'
												}
											});

											prg_row++;
											prg_sort++;
										}
										$(document).on('click', '.prg__remove', function(e) {
											e.preventDefault();
											if(confirm('<?php echo $confirm_remove; ?>')) {
												$(this).closest('tr').remove();
											}
										})
									</script> 
								</div>
								<?php /* # PRG */ ?>


								<?php /* SPEAKER */ ?>
								<div class="tab-pane" id="tab-tpl-speaker">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-speaker_title"><?php echo $entry_title; ?></label>
										<div class="col-sm-10">
											<input type="text" name="speaker_title" value="<?php echo $speaker_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-speaker_title" class="form-control" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_sort; ?>"><?php echo $entry_author; ?></span></label>
										<div class="col-sm-10">
											<div class="author__cont">
												<ul class="author__list sortable">
													<?php if(!empty($authors)) { ?>
														<?php foreach($authors as $author) { ?>
															<li class="author__item author__item-<?php echo $author['author_id']; ?>" >
																<?php echo $author['name']; ?>
																<select name="author_exp[<?php echo $author['author_id']; ?>]" class="form-control">
																	<option value="0">-- По-умолчанию --</option>
																	<?php if(!empty($author['exp_list'])) { ?>
																		<?php foreach($author['exp_list'] as $exp_item) { ?>
																			<option 
																			value="<?php echo $exp_item['exp_id']; ?>"
																			<?php if( (empty($author['author_exp']) && $exp_item['main']) || $exp_item['exp_id'] == $author['author_exp'] ) {echo 'selected'; } ?>
																			>
																			<?php echo $exp_item['exp']; ?></option>
																		<?php } ?>
																	<?php } ?>
																</select>
																<button type="button" class="author__remove" ><i class="fa fa-close"></i></button>
																<input type="hidden" name="author[<?php echo $author['author_id']; ?>]" value="<?php echo $author['author_id']; ?>">
															</li>
														<?php } ?>
													<?php } ?>
												</ul>
												<input type="text" name="authors_search" class="authors__input" placeholder="<?php echo $entry_author_placeholder; ?>">
											</div>
										</div>
										<?php require_once(DIR_TEMPLATE . 'visitor/visitor-autocomplete.tpl'); ?>
									</div>
								</div>
								<?php /* # SPEAKER */ ?>


								<?php /* PRESENT */ ?>
								<div class="tab-pane" id="tab-tpl-present">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-present_title"><?php echo $entry_title; ?></label>
										<div class="col-sm-10">
											<input type="text" name="present_title" value="<?php echo $present_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-present_title" class="form-control" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><?php echo $entry_present; ?></label>
										<div class="col-sm-10">
											<div class="present__cont">
												<ul class="present__list sortable">
													<?php if(!empty($presents)) { ?>
														<?php foreach($presents as $present) { ?>
															<li class="present__item present__item-<?php echo $present['present_id']; ?>" >
																<?php echo $present['title']; ?>
																<button type="button" class="present__remove" ><i class="fa fa-close"></i></button>
																<input type="hidden" name="present[]" value="<?php echo $present['present_id']; ?>">
															</li>
														<?php } ?>
													<?php } ?>
												</ul>
												<input type="text" name="present_search" class="present__input" placeholder="<?php echo $entry_present_placeholder; ?>" autocomplete="off">
											</div>
										</div>
										<?php require_once(DIR_TEMPLATE . 'present/present-autocomplete.tpl'); ?>
									</div>
								</div>
								<?php /* # PRESENT */ ?>


								<?php /* ASK */ ?>
								<div class="tab-pane" id="tab-tpl-ask">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-ask_title"><?php echo $entry_title; ?></label>
										<div class="col-sm-10">
											<input type="text" name="ask_title" value="<?php echo $ask_title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-ask_title" class="form-control" />
										</div>
									</div>
									<table id="ask" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<td class="text-left" ><?php echo $entry_question; ?></td>
												<td class="text-left" ><?php echo $entry_answer; ?></td>
												<td class="text-center" style="width: 100px;min-width: 100px;"><?php echo $entry_sort_order; ?></td>
												<td style="width: 50px;min-width: 50px;"></td>
											</tr>
										</thead>
										<tbody>
											<?php $ask_row = 0; ?>
											<?php $ask_sort = 0; ?>

											<?php if (!empty($ask)) { ?>
												<?php foreach ($ask as $ask_item) { ?>
													<?php $ask_sort = $ask_item['sort_order'] > $ask_sort ? $ask_item['sort_order'] : $ask_sort; ?>
													<tr id="ask-row<?php echo $ask_row; ?>">
														<td class="text-left">
															<textarea name="ask[<?php echo $ask_row; ?>][title]" rows="4" placeholder="<?php echo $entry_question; ?>" class="form-control"><?php echo $ask_item['title']; ?></textarea>
														</td>
														<td class="text-left">
															<textarea name="ask[<?php echo $ask_row; ?>][text]" rows="4" placeholder="<?php echo $entry_answer; ?>" class="form-control"><?php echo $ask_item['text']; ?></textarea>
														</td>
														<td class="text-center" ><input type="text" name="ask[<?php echo $ask_row; ?>][sort_order]" value="<?php echo $ask_item['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
														<td class="text-left"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger ask__remove"><i class="fa fa-minus-circle"></i></button></td>
													</tr>
													<?php $ask_row++; ?>
												<?php } ?>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3"></td>
												<td class="text-left"><button type="button" onclick="addAsk();" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
											</tr>
										</tfoot>
									</table>
									<script type="text/javascript">
										var ask_row = <?php echo $ask_row; ?>;
										var ask_sort = <?php echo $ask_sort+1; ?>;

										function addAsk() {
											html  = '<tr id="ask-row' + ask_row + '">';
											html += '  <td class="text-left"><textarea name="ask[' + ask_row + '][title]" rows="4" placeholder="<?php echo $entry_question; ?>" class="form-control"></textarea></td>';  
											html += '  <td class="text-left"><textarea name="ask[' + ask_row + '][text]" rows="4" placeholder="<?php echo $entry_answer; ?>" class="form-control"></textarea></td>';  
											html += '  <td class="text-center"><input type="text" name="ask[' + ask_row + '][sort_order]" value="'+ask_sort+'" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
											html += '  <td class="text-center"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger ask__remove"><i class="fa fa-minus-circle"></i></button></td>';
											html += '</tr>';

											$('#ask tbody').append(html);

											ask_row++;
											ask_sort++;
										}
										$(document).on('click', '.ask__remove', function(e) {
											e.preventDefault();
											if(confirm('<?php echo $confirm_remove; ?>')) {
												$(this).closest('tr').remove();
											}
										})
									</script> 
								</div>
								<?php /* # ASK */ ?>

							</div>
						</div>
						<?php /* # CONTENT */ ?>



					</div>

				</form>
			</div>
		</div>
	</div>
	<script>
		/*$( ".sortable" ).sortable({
			cancel: ".ui-state-disabled"
		});*/
		$( ".sortable" ).sortable({
			items: "li:not(.ui-state-disabled)"
		});
		if($('.sortable li').length) {
			$( ".sortable li" ).disableSelection();
		}
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
<style>
	.btn-toggle .btn:not(.active){
		color: #333;
		background-color: #e6e6e6;
		border-color: #adadad;
	}
	table textarea{
		resize: vertical;
	}
</style>

<script type="text/javascript">

	var config_language_id = <?php echo $config_language_id; ?>;  

	<?php if(stristr($_GET['route'], 'add')) { ?>

		$('#input-title' + config_language_id).change(function(){ generateUrlOnAdd(); });  

		function generateUrlOnAdd() {
			data = {
				name           : $('#input-title' + config_language_id).val(),
				essence        : 'event',
				event_id : ''
			};

			getSeoUrl(data);
		}

	<?php } else { ?>

		$('#input-keyword').after('<br><button id="generateUrlOnEdit" class="btn btn-success">GENERATE SEO URL</button>');

		$('#generateUrlOnEdit').click(function(e){
			e.preventDefault();

			data = {
				name           : $('#input-title' + config_language_id).val(),
				essence        : 'event',
				event_id : <?php echo $_GET['event_id']; ?>
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
	$('.date').datetimepicker({
		pickDate: true,
		pickTime: false,
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
<?php echo $footer; ?>
