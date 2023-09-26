<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
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
	<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
<?php } ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-pencil"></i> Доступные меню</h3>
	</div>
	<div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-attribute" class="form-horizontal">
			<ul class="nav nav-tabs">
				<li class="hidden"><a href="#tab-content" data-toggle="tab" aria-expanded="true"><?php echo $text_content; ?></a></li>           
				
				<li class="active"><a href="#tab-setting" data-toggle="tab" aria-expanded="true"><?php echo $text_setting; ?></a></li>      
				<li><a href="#tab-social" data-toggle="tab" aria-expanded="true">Соц. сети</a></li>
				<li ><a href="#tab-menu" data-toggle="tab" aria-expanded="true">Меню</a></li>
				<li ><a href="#tab-tags" data-toggle="tab" aria-expanded="true">Теги</a></li>
				<li ><a href="#tab-bitrix" data-toggle="tab" aria-expanded="true">Bitrix24</a></li>
				<li ><a href="#tab-unisender" data-toggle="tab" aria-expanded="true">UniSender</a></li>
				<li class="hidden"><a href="#tab-page" data-toggle="tab" aria-expanded="true"><?php echo $text_page; ?></a></li>          
			</ul>
			<div id="tabs" class="tab-content">

				<!-- CONTENT -->
				<div class="tab-pane " id="tab-content">
					<div class="tabbable tabs-left">
						<ul id="vtab-option" class="nav nav-tabs hidden">	
							<li class="active"><a href="#tab-content-modal" data-toggle="tab" aria-expanded="false"><?php echo $text_content_modal; ?></a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab-content-modal">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-site-personal"><?php echo $entry_site_personal; ?></label>
									<div class="col-sm-10">
										<textarea name="themeset_site_personal" id="" placeholder="<?php echo $entry_site_personal; ?>" id="input-site-personal" class="form-control summernote"><?php echo $themeset_site_personal; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-site-agree"><?php echo $entry_site_agree; ?></label>
									<div class="col-sm-10">
										<textarea name="themeset_site_agree" id="" placeholder="<?php echo $entry_site_agree; ?>" id="input-site-agree" class="form-control summernote"><?php echo $themeset_site_agree; ?></textarea>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
				<!-- # CONTENT -->


				<!-- SETTING -->
				<div class="tab-pane active" id="tab-setting">

					<div class="form-group">
						<label class="col-sm-2 control-label">Рекламодателям</label>
						<div class="col-sm-5">
							<input type="text" name="themeset_adv[title]" value="<?php echo $themeset_adv['title']; ?>" placeholder="Заголовок" class="form-control" />
						</div>
						<div class="col-sm-5">
							<input type="text" name="themeset_adv[href]" value="<?php echo $themeset_adv['href']; ?>" placeholder="Ссылка" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">E-mail</label>
						<div class="col-sm-10">
							<input type="text" name="themeset_email" value="<?php echo $themeset_email; ?>" placeholder="E-mail" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Телефон:</label>
						<div class="col-sm-10">
							<div class="phone__item row">
								<div class="col-sm-10">
									<input type="text" name="themeset_phone" value="<?php echo $themeset_phone; ?>" placeholder="Телефон" class="form-control" />
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Текст под лого</label>
						<div class="col-sm-10">
							<input type="text" name="themeset_logo_text" value="<?php echo $themeset_logo_text; ?>" placeholder="Текст под лого" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Копирайт</label>
						<div class="col-sm-10">
							<textarea rows="6" name="themeset_copy" id="" placeholder="Копирайт" class="form-control "><?php echo $themeset_copy; ?></textarea>
						</div>
					</div>

				</div>  
				<!-- # SETTING -->


				<!-- TAGS -->
				<div class="tab-pane" id="tab-tags">

					<div class="form-group">
						<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="Вы можете изменять порядок отображения тегов путем перетаскивания их мышью">Теги</span></label>
						<div class="col-sm-10">
							<div class="tag__cont">
								<ul class="tag__list sortable_tags">
									<?php if(!empty($themeset_tags)) { ?>
										<?php foreach($themeset_tags as $tag) { ?>
											<li class="tag__item tag__item-<?php echo $tag['tag_id']; ?>" value="<?php echo utf8_strtolower($tag['tag']); ?>">
												<?php echo $tag['tag']; ?>
												<button type="button" class="tag__remove" ><i class="fa fa-close"></i></button>
												<input type="hidden" name="themeset_tags[]" value="<?php echo $tag['tag_id']; ?>">
											</li>
										<?php } ?>
									<?php } ?>
								</ul>
								<input type="text" name="tag_search" class="tag__input" placeholder="Начните вводить тег здесь">
							</div>
						</div>
						<?php require_once(DIR_TEMPLATE . 'themeset/tag-autocomplete.tpl'); ?>
					</div>

				</div>  
				<!-- # TAGS -->


				<!-- BITRIX -->
				<div class="tab-pane" id="tab-bitrix">

					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-bitrix-lead" data-toggle="tab" aria-expanded="true">Заявка</a></li>
						<li><a href="#tab-bitrix-companies" data-toggle="tab" aria-expanded="true">Компании</a></li>
					</ul>
					<div id="tabs-bitrix" class="tab-content">

						<!-- BITRIX LEAD -->
						<div class="tab-pane active" id="tab-bitrix-lead">

							<div class="form-group">
								<label class="col-sm-2 control-label">Статус</label>
								<div class="col-sm-4">
									<div class="btn-group btn-toggle" data-toggle="buttons">
										<label class="btn btn-success <?php echo $themeset_bitrix_status == 1 ? 'active' : ''; ?>"><input type="radio" name="themeset_bitrix_status" value="1"  <?php echo $themeset_bitrix_status == 1 ? 'checked' : ''; ?>> Включено </label>
										<label class="btn btn-danger   <?php echo $themeset_bitrix_status == 0 ? 'active' : ''; ?>"><input type="radio" name="themeset_bitrix_status" value="0"   <?php echo $themeset_bitrix_status == 0 ? 'checked' : ''; ?>>  Выключено </label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Вебхук crm.lead.add</label>
								<div class="col-sm-10">
									<input type="text" name="themeset_bitrix_webhook" value="<?php echo $themeset_bitrix_webhook; ?>" placeholder="Вебхук crm.lead.add" class="form-control" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">ID ответственного</label>
								<div class="col-sm-10">
									<input type="text" name="themeset_bitrix_assigned" value="<?php echo $themeset_bitrix_assigned; ?>" placeholder="ID ответственного" class="form-control" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Поле ТЕКСТ КЕЙСА</label>
								<div class="col-sm-10">
									<input type="text" name="themeset_bitrix_file" value="<?php echo $themeset_bitrix_file; ?>" placeholder="Поле ТЕКСТ КЕЙСА" class="form-control" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Поле КОМПАНИЯ</label>
								<div class="col-sm-10">
									<input type="text" name="themeset_bitrix_company" value="<?php echo $themeset_bitrix_company; ?>" placeholder="Поле КОМПАНИЯ" class="form-control" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Поле КРАТКОЕ ОПИСАНИЕ КОМПАНИИ</label>
								<div class="col-sm-10">
									<input type="text" name="themeset_bitrix_company_text" value="<?php echo $themeset_bitrix_company_text; ?>" placeholder="Поле КРАТКОЕ ОПИСАНИЕ КОМПАНИИ" class="form-control" />
								</div>
							</div>

						</div>
						<!-- # BITRIX LEAD -->

						<!-- BITRIX COMPANIES -->
						<div class="tab-pane" id="tab-bitrix-companies">
							<div class="form-group">
								<label class="col-sm-2 control-label">Адрес портала Битрикс24</label>
								<div class="col-sm-10">
									<input type="text" name="themeset_bitrix_url" value="<?php echo $themeset_bitrix_url; ?>" placeholder="my.bitrix24.ru" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Вебхук crm.company.list</label>
								<div class="col-sm-10">
									<input type="text" name="themeset_bitrix_companies_list" value="<?php echo $themeset_bitrix_companies_list; ?>" placeholder="Вебхук crm.company.list" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Вебхук crm.company.get</label>
								<div class="col-sm-10">
									<input type="text" name="themeset_bitrix_company_info" value="<?php echo $themeset_bitrix_company_info; ?>" placeholder="Вебхук crm.company.get" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Вебхук crm.company.fields</label>
								<div class="col-sm-10">
									<input type="text" name="themeset_bitrix_company_fields" value="<?php echo $themeset_bitrix_company_fields; ?>" placeholder="Вебхук crm.company.fields" class="form-control" />
								</div>
							</div>

							<fieldset>
								<legend>Категории компаний</legend>
								<div class="form-group">
									<label class="col-sm-2 control-label">ID категории Дистрибьютор</label>
									<div class="col-sm-10">
										<input type="text" name="themeset_bitrix_company_reseller" value="<?php echo $themeset_bitrix_company_reseller; ?>" placeholder="ID категории Дистрибьютор" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">ID категории Производитель</label>
									<div class="col-sm-10">
										<input type="text" name="themeset_bitrix_company_investor" value="<?php echo $themeset_bitrix_company_investor; ?>" placeholder="ID категории Производитель" class="form-control" />
									</div>
								</div>
							</fieldset>

							<fieldset>
								<legend>Локальное приложение</legend>
								<div class="form-group">
									<label class="col-sm-2 control-label">refresh_token</label>
									<div class="col-sm-10">
										<input type="text" name="themeset_bitrix_company_refresh_token" value="<?php echo $themeset_bitrix_company_refresh_token; ?>" placeholder="refresh_token" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">client_id</label>
									<div class="col-sm-10">
										<input type="text" name="themeset_bitrix_company_client_id" value="<?php echo $themeset_bitrix_company_client_id; ?>" placeholder="client_id" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">client_secret</label>
									<div class="col-sm-10">
										<input type="text" name="themeset_bitrix_company_client_secret" value="<?php echo $themeset_bitrix_company_client_secret; ?>" placeholder="client_secret" class="form-control" />
									</div>
								</div>
								<div class="well">
									<h3>Инструкция по настройке</h3>
									<hr>
									<ol>
										<li>
											Создать локальное приложение Битрикс с параметрами:
											<ul>
												<li>Путь вашего обработчика: <strong><?php echo $company_b24['url']; ?></strong></li>
												<li>Путь для первоначальной установки: <strong><?php echo $company_b24['install']; ?></strong></li>
												<li>Использует только API</li>
											</ul> 
											<hr>
										</li>
										<li>
											Скопировать поля <strong>client_id</strong> и <strong>client_secret</strong> в соответствующие поля на этой странице
											<hr>
										</li>
										<li>
											Открыть в браузере ссылку <strong>https://my.bitrix24.ru/oauth/authorize/?client_id=MY_CLIENT_ID&response_type=code&redirect_uri=<?php echo $company_b24['redirect_uri']; ?></strong> 
											<i>
												<br>, где
												<br><strong>my.bitrix24.ru</strong> заменить на адрес вашего портала Bitrix24,
												<br><strong>MY_CLIENT_ID</strong> заменить на client_id созданного локального приложения
											</i>
											<hr>
										</li>
										<li>
											Из адреса страницы, на которую браузер перешел на предыдущем шаге, скопировать значение параметра <strong>code</strong> и подставить его в ссылку <strong>https://my.bitrix24.ru/oauth/token/?client_id=MY_CLIENT_ID&grant_type=authorization_code&client_secret=MY_CLIENT_SECRET&redirect_uri=<?php echo $company_b24['redirect_uri']; ?>&code=CODE&scope=crm</strong>
											<i>
												<br>, где
												<br><strong>my.bitrix24.ru</strong> заменить на адрес вашего портала Bitrix24,
												<br><strong>MY_CLIENT_ID</strong> заменить на client_id созданного локального приложения
												<br><strong>MY_CLIENT_SECRET</strong> заменить на client_secret созданного локального приложения
												<br><strong>CODE</strong> заменить на скопированное значение code
											</i>
											<hr>
										</li>
										<li>
											На открывшейся странице скопировать <strong>refresh_token</strong> и вставить его в соответствующее поле на этой странице
										</li>
									</ol>

									<hr>
									<h3>CRON</h3>
									<p>Настроить на сервере ежедневное задание по расписанию с выполнение команды <strong>/usr/bin/wget -O /dev/null <?php echo $company_b24['refresh']; ?></strong></p>
								</div>
							</fieldset>
						</div>
						<!-- BITRIX COMPANIES -->

					</div>



				</div>  
				<!-- # BITRIX -->


				<!-- UNISENDER -->
				<div class="tab-pane" id="tab-unisender">

					<div class="form-group">
						<label class="col-sm-2 control-label">Статус</label>
						<div class="col-sm-4">
							<div class="btn-group btn-toggle" data-toggle="buttons">
								<label class="btn btn-success <?php echo $themeset_uni_status == 1 ? 'active' : ''; ?>"><input type="radio" name="themeset_uni_status" value="1"  <?php echo $themeset_uni_status == 1 ? 'checked' : ''; ?>> Включено </label>
								<label class="btn btn-danger   <?php echo $themeset_uni_status == 0 ? 'active' : ''; ?>"><input type="radio" name="themeset_uni_status" value="0"   <?php echo $themeset_uni_status == 0 ? 'checked' : ''; ?>>  Выключено </label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Api Key</label>
						<div class="col-sm-10">
							<input type="text" name="themeset_uni_key" value="<?php echo $themeset_uni_key; ?>" placeholder="Api Key" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">ID основного списка</label>
						<div class="col-sm-10">
							<input type="text" name="themeset_uni_main" value="<?php echo $themeset_uni_main; ?>" placeholder="ID основного списка" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">ID списка Аккаунт</label>
						<div class="col-sm-10">
							<input type="text" name="themeset_uni_account" value="<?php echo $themeset_uni_account; ?>" placeholder="Поле ТЕКСТ КЕЙСА" class="form-control" />
						</div>
					</div>

				</div>  
				<!-- # UNISENDER -->

				<!-- MENU -->
				<div class="tab-pane" id="tab-menu">
					<div class="form-group hidden" >
						<label class="col-sm-2 control-label">Верхнее меню</label>
						<div class="col-sm-10">
							<select name="themeset_menu_top" class="form-control">
								<option value="">-- Не выбрано --</option>
								<?php foreach($menus as $value){ ?>
									<option value="<?php echo $value['menu_id']; ?>" <?php echo $themeset_menu_top == $value['menu_id'] ? 'selected' : ''; ?>><?php echo $value['name'];?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group " >
						<label class="col-sm-2 control-label">Футер</label>
						<div class="col-sm-10">
							<select name="themeset_menu_footer" class="form-control">
								<option value="">-- Не выбрано --</option>
								<?php foreach($menus as $value){ ?>
									<option value="<?php echo $value['menu_id']; ?>" <?php echo $themeset_menu_footer == $value['menu_id'] ? 'selected' : ''; ?>><?php echo $value['name'];?></option>
								<?php } ?>
							</select>
						</div>
					</div>


				</div>
				<!-- # MENU -->

				<!-- SOCIAL -->
				<div class="tab-pane" id="tab-social">

					<fieldset>
						<legend>Соц. сети</legend>


						<div class="form-group">
							<label class="col-sm-2 control-label">Telegram</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_soc_tg" value="<?php echo $themeset_soc_tg; ?>" placeholder="Telegram" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Instagram</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_soc_insta" value="<?php echo $themeset_soc_insta; ?>" placeholder="Instagram" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Facebook</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_soc_fb" value="<?php echo $themeset_soc_fb; ?>" placeholder="Facebook" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">YouTube</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_soc_you" value="<?php echo $themeset_soc_you; ?>" placeholder="YouTube" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">ВКонтакте</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_soc_vk" value="<?php echo $themeset_soc_vk; ?>" placeholder="ВКонтакте" class="form-control" />
							</div>
						</div>
						<div class="form-group hidden">
							<label class="col-sm-2 control-label">Twitter</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_soc_tw" value="<?php echo $themeset_soc_tw; ?>" placeholder="Twitter" class="form-control" />
							</div>
						</div>
						<div class="form-group hidden">
							<label class="col-sm-2 control-label">Одноклассники</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_soc_od" value="<?php echo $themeset_soc_od; ?>" placeholder="Одноклассники" class="form-control" />
							</div>
						</div>
					</fieldset>


					<fieldset class="hidden">
						<legend>Мессенджеры</legend>


						<div class="form-group hidden">
							<label class="col-sm-2 control-label">Ссылка Telegram</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_mess_telegram" value="<?php echo $themeset_mess_telegram; ?>" placeholder="Ссылка Telegram" class="form-control" />
							</div>
						</div>

						<div class="form-group ">
							<label class="col-sm-2 control-label">Номер Viber</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_mess_viber" value="<?php echo $themeset_mess_viber; ?>" placeholder="Номер Viber" class="form-control" />
							</div>
						</div>

						<div class="form-group ">
							<label class="col-sm-2 control-label">Номер Whatsapp</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_mess_whatsapp" value="<?php echo $themeset_mess_whatsapp; ?>" placeholder="Номер Whatsapp" class="form-control" />
							</div>
						</div>

						<div class="form-group ">
							<label class="col-sm-2 control-label">Skype</label>
							<div class="col-sm-10">
								<input type="text" name="themeset_mess_skype" value="<?php echo $themeset_mess_skype; ?>" placeholder="Skype" class="form-control" />
							</div>
						</div>
					</fieldset>





				</div>  
				<!-- # SOCIAL -->

				<!-- PAGE -->
				<div class="tab-pane" id="tab-page">
					<ul id="tab-page-nav" class="nav nav-tabs">					
						<li class="active"><a href="#tab-page-contact" data-toggle="tab" aria-expanded="false"><?php echo $text_contact; ?></a></li>
						<li class=""><a href="#tab-page-specials" data-toggle="tab" aria-expanded="false"><?php echo $text_specials; ?></a></li>
						<li class=""><a href="#tab-page-manufacturer" data-toggle="tab" aria-expanded="false"><?php echo $text_manufacturer; ?></a></li>
					</ul>
					<div class="tab-content">

						<!-- CONTACT -->
						<div class="tab-pane active" id="tab-page-contact">


							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_contact_content; ?></label>
								<div class="col-sm-10">
									<textarea name="themeset_contact_content" id="" placeholder="<?php echo $entry_contact_content; ?>" id="input-site-agree" class="form-control summernote"><?php echo $themeset_contact_content; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_landing_title; ?></label>
								<div class="col-sm-10">
									<input type="text" name="themeset_contact_meta[title]" value="<?php echo $themeset_contact_meta['title']; ?>" placeholder="<?php echo $entry_landing_title; ?>" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_h1; ?></label>
								<div class="col-sm-10">
									<input type="text" name="themeset_contact_meta[meta_h1]" value="<?php echo $themeset_contact_meta['meta_h1']; ?>" placeholder="<?php echo $entry_meta_h1; ?>" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_title; ?></label>
								<div class="col-sm-10">
									<input type="text" name="themeset_contact_meta[meta_title]" value="<?php echo $themeset_contact_meta['meta_title']; ?>" placeholder="<?php echo $entry_meta_title; ?>" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_description; ?></label>
								<div class="col-sm-10">
									<textarea name="themeset_contact_meta[meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" class="form-control"><?php echo $themeset_contact_meta['meta_description']; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_cat_rowword; ?></label>
								<div class="col-sm-10">
									<textarea name="themeset_contact_meta[meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" class="form-control"><?php echo $themeset_contact_meta['meta_keyword']; ?></textarea>
								</div>
							</div>
						</div>
						<!-- # CONTACT -->

						<!-- SPECIALS -->
						<div class="tab-pane" id="tab-page-specials">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_landing_title; ?></label>
								<div class="col-sm-10">
									<input type="text" name="themeset_specials[title]" value="<?php echo $themeset_specials['title']; ?>" placeholder="<?php echo $entry_landing_title; ?>" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_h1; ?></label>
								<div class="col-sm-10">
									<input type="text" name="themeset_specials[meta_h1]" value="<?php echo $themeset_specials['meta_h1']; ?>" placeholder="<?php echo $entry_meta_h1; ?>" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_title; ?></label>
								<div class="col-sm-10">
									<input type="text" name="themeset_specials[meta_title]" value="<?php echo $themeset_specials['meta_title']; ?>" placeholder="<?php echo $entry_meta_title; ?>" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_description; ?></label>
								<div class="col-sm-10">
									<textarea name="themeset_specials[meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" class="form-control"><?php echo $themeset_specials['meta_description']; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_keyword; ?></label>
								<div class="col-sm-10">
									<textarea name="themeset_specials[meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" class="form-control"><?php echo $themeset_specials['meta_keyword']; ?></textarea>
								</div>
							</div>
						</div>
						<!-- # SPECIALS -->

						<!-- MANUFACTURER -->
						<div class="tab-pane" id="tab-page-manufacturer">
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_shop_title; ?></label>
								<div class="col-sm-10">
									<input type="text" name="themeset_manufacturer[title]" value="<?php echo $themeset_manufacturer['title']; ?>" placeholder="<?php echo $entry_shop_title; ?>" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_shop_description; ?></label>
								<div class="col-sm-10">
									<textarea name="themeset_manufacturer[description]" id="" placeholder="<?php echo $entry_shop_description; ?>" id="input-shop-description" class="form-control summernote"><?php echo $themeset_manufacturer['description']; ?></textarea>
								</div>
							</div>


							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_h1; ?></label>
								<div class="col-sm-10">
									<input type="text" name="themeset_manufacturer[meta_h1]" value="<?php echo $themeset_manufacturer['meta_h1']; ?>" placeholder="<?php echo $entry_meta_h1; ?>" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_title; ?></label>
								<div class="col-sm-10">
									<input type="text" name="themeset_manufacturer[meta_title]" value="<?php echo $themeset_manufacturer['meta_title']; ?>" placeholder="<?php echo $entry_meta_title; ?>" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_description; ?></label>
								<div class="col-sm-10">
									<textarea name="themeset_manufacturer[meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" class="form-control"><?php echo $themeset_manufacturer['meta_description']; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_meta_keyword; ?></label>
								<div class="col-sm-10">
									<textarea name="themeset_manufacturer[meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" class="form-control"><?php echo $themeset_manufacturer['meta_keyword']; ?></textarea>
								</div>
							</div>
						</div>
						<!-- # MANUFACTURER -->

					</div>
				</div>
				<!-- # PAGE -->






			</div>
		</form>
	</div>
</div>
</div>
</div>


<?php /* ?>
<script type="text/javascript">
	<?php if ($ckeditor) { ?>
		ckeditorInit('input-description', getURLVar('token'));
		<?php 
	} ?>
</script>
<?php */ ?>

<?php /* ?>
<div class="btn-group btn-toggle" data-toggle="buttons">
<label class="btn btn-primary <?php echo $cat['main'] == 1 ? 'active' : ''; ?>"><input type="radio" name="themeset_cats[<?php echo $cat_row; ?>][main]" value="1"  <?php echo $cat['main'] == 1 ? 'checked' : ''; ?>> Да </label>
<label class="btn btn-default <?php echo $cat['main'] == 0 ? 'active' : ''; ?>"><input type="radio" name="banner[<?php echo $cat_row; ?>][main]" value="0"   <?php echo $cat['main'] == 0 ? 'checked' : ''; ?>>  Нет </label>
</div>
<?php */ ?>
<style>
	.btn-toggle .btn:not(.active){
		color: #333;
		background-color: #e6e6e6;
		border-color: #adadad;
	}
</style>


<style>
	#nav_button .btn{
		width: 100%;
	}
	.tabs-left > .nav-tabs {
		border-bottom: 0;
	}

	.tab-content > .tab-pane,
	.pill-content > .pill-pane {
		display: none;
	}

	.tab-content > .active,
	.pill-content > .active {
		display: block;
	}

	.tabs-left > .nav-tabs > li,
	.tabs-right > .nav-tabs > li {
		float: none;
	}

	.tabs-left > .nav-tabs > li > a,
	.tabs-right > .nav-tabs > li > a {
		min-width: 74px;
		margin-right: 0;
		margin-bottom: 3px;
	}



	.tabs-left > .nav-tabs > li > a {
		margin-right: -1px;
		-webkit-border-radius: 4px 0 0 4px;
		-moz-border-radius: 4px 0 0 4px;
		border-radius: 4px 0 0 4px;
	}

	.tabs-left > .nav-tabs > li > a:hover,
	.tabs-left > .nav-tabs > li > a:focus {
		border-color: #eeeeee #dddddd #eeeeee #eeeeee;
	}

	.tabs-left > .nav-tabs .active > a,
	.tabs-left > .nav-tabs .active > a:hover,
	.tabs-left > .nav-tabs .active > a:focus {
		border-color: #ddd transparent #ddd #ddd;
		*border-right-color: #ffffff;
	}
</style>
<script type="text/javascript">
	<?php if ($ckeditor) { ?>
		ckeditorInit('input-site-personal', getURLVar('token'));
		ckeditorInit('input-site-agree', getURLVar('token'));
		ckeditorInit('input-contact-warning', getURLVar('token'));
		ckeditorInit('input-shop-description', getURLVar('token'));
	<?php } ?>


	$(document).on('click', '.delete_row', function(e){
		e.preventDefault();
		if(confirm('Вы уверены?')) {
			$(this).closest('tr').remove();
		}
	})

</script> 
<?php echo $footer; ?>
