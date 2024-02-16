<div class="regbrand__result" style="margin: 0">
	
	
	<div class="regbrand__result--top">
		<div class="regbrand__result--left">
			<div class="regbrand__result--sub">Ваша компания</div>
			<div class="regbrand__result--title"><?php echo $company_info['search']; ?></div>
		</div>
		<div class="regbrand__result--change">
			<a href="#" class="link" id="regbrand-change" data-search="<?php echo $company_info['search']; ?>">+ Изменить</a>
		</div>
	</div>

	<div class="regbrand__fields">
		<div class="regbrand__fields--title">Заполните или измените данные о компании</div>
		<div class="regform__inp">
			<input type="text" name="city" value="<?php echo $company_info['city']; ?>" class="regform__input required " placeholder="" />
			<div class="regform__label">Город работы</div>
		</div>
		<div class="regform__inp">
			<input type="tel" name="company_phone" value="<?php echo $company_info['phone']; ?>" class="regform__input required " autocomplete="false" placeholder="" />
			<div class="regform__label">Телефон</div>
		</div>
		<div class="regform__inp">
			<input type="text" name="company_site" value="<?php echo $company_info['web']; ?>" class="regform__input required " autocomplete="false" placeholder="" />
			<div class="regform__label">Сайт компании</div>
		</div>

		<?php 
		$main_activity = '';
		foreach($activity as $item) {
			if(mb_strtolower($item) === mb_strtolower($company_info['activity'])) {
				$main_activity = $item;
				break;
			} 
		} 
		?>
		<div class="regform__inp regform__inp-plh regform__select dropdown">
			<div class="regform__select--text dropdown-toggle <?php echo $main_activity ? 'valid' : ''; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
				<span><?php echo $main_activity; ?></span>
				<svg><use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill"></svg>
				</div>
				<div class="regform__plh">Активность в proAV</div>
				<div class="regform__select--dropdown dropdown-menu" >
					<div class="regform__select--list">
						<?php foreach($activity as $item) { ?>
							<label class="regform__select--input">
								<input type="radio" name="company_activity" value="<?php echo $item; ?>" <?php echo mb_strtolower($item) === mb_strtolower($company_info['activity']) ? 'checked' : ''; ?>>
								<span><?php echo $item; ?></span>
							</label>
						<?php } ?>
					</div>
				</div>
			</div>
			<input type="hidden" name="company" value='<?php echo $company_info["search"]; ?>'>
			<input type="hidden" name="b24_company_old_id" value="<?php echo $company_info['b24_company_old_id']; ?>">
			<input type="hidden" name="b24_company_id" value="<?php echo $company_info['b24_company_id']; ?>">
			<input type="hidden" name="isCompanyChanged" value='<?php echo $isCompanyChanged; ?>'>

	</div>

	</div>


	<?php require(DIR_TEMPLATE . 'avclub/template/register/script-change.tpl'); ?>
