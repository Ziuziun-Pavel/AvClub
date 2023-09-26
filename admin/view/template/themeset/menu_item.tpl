<div class="tab-pane" id="tab-menu" >
	<div class="tabs-left">
		<ul class="nav nav-tabs col-sm-3 col-md-2">
			<?php $count = 0;if($links){ ?>
				<?php foreach($links as $item){ ?>
					<li id="href-<?php echo $count;?>" class="<?php echo $count == 0 ? 'active' : '';?>"><a href="#tab-<?php echo $count;?>" data-toggle="tab" aria-expanded="true">Ссылка <?php echo $count+1; ?></a></li>
					<?php $count++;} ?>
				<?php } ?>
				<li><button type="button" class="btn btn-primary" onclick="addMenuItem(this);">Добавить</button></li>   
			</ul>
			<div id="tabs" class="tab-content col-sm-9 col-md-10">
				<?php $max_sort = 0;  ?>
				<?php if($links){ ?>
					<?php $count = 0; 
					foreach($links as $item){  ?>
						<?php $max_sort = ($max_sort < $item['sort_order']) ? $item['sort_order'] : $max_sort; ?>
						<div class="tab-pane <?php echo $count == 0 ? 'active' : '';?>" id="tab-<?php echo $count; ?>" data-id="<?php echo $number; ?>" data-index="<?php echo $count; ?>">
							<div class="form-group">
								<label class="col-sm-2 control-label">
									<span data-toggle="tooltip" title="" data-original-title="<?php echo $help_title;?>"><?php echo $entry_title; ?></span>
								</label>
								<div class="col-sm-10">
									<input type="text" name="links[<?php echo $count; ?>][title]" value="<?php echo $item['title']; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
								</div>
							</div>
							<div class="change__type">
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $entry_type; ?></label>
									<div class="col-sm-10">
										<select name="links[<?php echo $count; ?>][type]" class="form-control" onchange="changeType(this);">
											<option value="category" <?php echo ($item['type'] == 'category' ? 'selected' : ''); ?>><?php echo $text_category;?></option>
											<option value="category_type" <?php echo ($item['type'] == 'category_type' ? 'selected' : ''); ?>>Категории по типам</option>
											<option value="category_with_children" <?php echo ($item['type'] == 'category_with_children' ? 'selected' : ''); ?>>Категории с подкатегориями</option>
											<option value="manufacturer" <?php echo ($item['type'] == 'manufacturer' ? 'selected' : ''); ?>><?php echo $text_manufacturer;?></option>
											<option value="information" <?php echo ($item['type'] == 'information' ? 'selected' : ''); ?>><?php echo $text_information;?></option>
											<option value="news_category" <?php echo ($item['type'] == 'news_category' ? 'selected' : ''); ?>>Блог - категория</option>
											<option value="news_article" <?php echo ($item['type'] == 'news_article' ? 'selected' : ''); ?>>Блог - статья</option>
											<option value="standart" <?php echo ($item['type'] == 'standart' ? 'selected' : ''); ?>><?php echo $text_standart;?></option>
											<option value="href" <?php echo ($item['type'] == 'href' ? 'selected' : ''); ?>><?php echo $text_href;?></option>
										</select>
									</div>
								</div>

								<div class="form-group type__item type__category type__category_with_children type__category_type" style="<?php echo (($item['type'] == 'category' || $item['type'] == 'category_with_children' || $item['type'] == 'category_type' ) ? '' : 'display: none;'); ?>">
									<label class="col-sm-2 control-label"><?php echo $text_category; ?></label>
									<div class="col-sm-10">
										<select name="links[<?php echo $count; ?>][category_id]" class="form-control">
											<?php foreach($categories as $value){ ?>
												<option value="<?php echo $value['category_id']; ?>" <?php echo $item['category_id'] == $value['category_id'] ? 'selected' : ''; ?>><?php echo $value['name'];?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group type__item type__manufacturer" style="<?php echo ($item['type'] == 'manufacturer' ? '' : 'display: none;'); ?>">
									<label class="col-sm-2 control-label"><?php echo $text_manufacturer; ?></label>
									<div class="col-sm-10">
										<select name="links[<?php echo $count; ?>][manufacturer_id]" class="form-control">
											<option value="0">-- Не выбрано --</option>
											<?php foreach($manufacturers as $value){ ?>
												<option value="<?php echo $value['manufacturer_id']; ?>" <?php echo $item['manufacturer_id'] == $value['manufacturer_id'] ? 'selected' : ''; ?>><?php echo $value['name'];?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group type__item type__information" style="<?php echo ($item['type'] == 'information' ? '' : 'display: none;'); ?>">
									<label class="col-sm-2 control-label"><?php echo $text_information; ?></label>
									<div class="col-sm-10">
										<select name="links[<?php echo $count; ?>][information_id]" class="form-control">
											<?php foreach($informations as $value){ ?>
												<option value="<?php echo $value['information_id']; ?>" <?php echo $item['information_id'] == $value['information_id'] ? 'selected' : ''; ?>><?php echo $value['title'];?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group type__item type__news_article" style="<?php echo ($item['type'] == 'news_article' ? '' : 'display: none;'); ?>">
									<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="" data-original-title="Автодополнение">Заголовок статьи</span></label>
									<div class="col-sm-10">
										<input type="text" name="newsblog" value="" placeholder="Заголовок статьи" class="form-control" />
										<input type="hidden" name="links[<?php echo $count; ?>][news_article]" value="<?php echo $item['article_id']; ?>">
										<div class="well well-sm">
											<?php if(isset($item['news_article']) && $item['news_article']){ ?>
												<div><i class="fa fa-minus-circle article_remove"></i> <?php echo $blog_articles[$item['article_id']]; ?></div>
											<?php } ?>
										</div>
									</div>
								</div>
								<div class="form-group type__item type__news_category" style="<?php echo ($item['type'] == 'news_category' ? '' : 'display: none;'); ?>">
									<label class="col-sm-2 control-label">Блог - категория</label>
									<div class="col-sm-10">
										<select name="links[<?php echo $count; ?>][news_category_id]" class="form-control">
											<option value="0">-- Не выбрано --</option>
											<?php foreach($blog_categories as $value){ ?>
												<option value="<?php echo $value['category_id']; ?>" <?php echo $item['news_category_id'] == $value['category_id'] ? 'selected' : ''; ?>><?php echo $value['name'];?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group type__item type__standart" style="<?php echo ($item['type'] == 'standart' ? '' : 'display: none;'); ?>">
									<label class="col-sm-2 control-label"><?php echo $text_standart; ?></label>
									<div class="col-sm-10">
										<select name="links[<?php echo $count; ?>][standart]" class="form-control">
											<?php foreach($standart as $value){ ?>
												<option value="<?php echo $value['value']; ?>" <?php echo $item['standart'] == $value['value'] ? 'selected' : ''; ?>><?php echo $value['name'];?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group type__item type__href" style="<?php echo (($item['type'] == 'href') ? '' : 'display: none;'); ?>">
									<label class="col-sm-2 control-label"><?php echo $entry_href; ?></label>
									<div class="col-sm-10">
										<input type="text" name="links[<?php echo $count; ?>][href]" value="<?php echo $item['href']; ?>" placeholder="<?php echo $entry_href; ?>" class="form-control" />
									</div>
								</div>

							</div>
							<div class="form-group " >
								<label class="col-sm-2 control-label">Меню</label>
								<div class="col-sm-10">
									<select name="links[<?php echo $count; ?>][submenu]" class="form-control">
										<option value="">-- Не выбрано --</option>
										<?php foreach($menus as $value){ ?>
											<option value="<?php echo $value['menu_id']; ?>" <?php echo $item['submenu'] == $value['menu_id'] ? 'selected' : ''; ?>><?php echo $value['name'];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Изображение</label>
								<div class="col-sm-10"><a href="" id="thumb-image<?php echo $count; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $item['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
									<input type="hidden" name="links[<?php echo $count; ?>][image]" value="<?php echo $item['image']; ?>" id="input-image<?php echo $count; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-10">
									<input type="text" name="links[<?php echo $count; ?>][sort_order]" value="<?php echo $item['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />
								</div>
							</div> 
							<div class="form-group">
								<label class="col-sm-2 control-label">Выделение</label>
								<div class="col-sm-10">
									<select name="links[<?php echo $count; ?>][label]" class="form-control">
										<?php if ($item['label']) { ?>
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
								<label class="col-sm-2 control-label">На мобильных</label>
								<div class="col-sm-10">
									<select name="links[<?php echo $count; ?>][mobile]" class="form-control">
										<?php if ($item['mobile']) { ?>
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
								<label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
								<div class="col-sm-10">
									<select name="links[<?php echo $count; ?>][status]" class="form-control">
										<?php if ($item['status']) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							<br>
							<br>
							<br>
							<br>
							<div class="form-group">
								<div class="col-sm-10 col-sm-push-2">
									<a href="#" class="btn btn-danger tab_delete" data-id="<?php echo $count; ?>">Удалить</a>
								</div>
							</div> 
						</div>

						<?php $count++;
					} ?>
				<?php } ?>

			</div>

		</div>
	</div>