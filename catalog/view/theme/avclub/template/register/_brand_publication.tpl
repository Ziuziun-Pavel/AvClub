
<div class="regbrand__start">
	<div class="regform__name">Бренд о котором идет речь в публикациях</div>
	<div class="regbrand__start--inp">
		<div class="regform__inp regform__inp-plh">
			<input type="text" name="brand" value="<?php echo !empty($brand_search) ? $brand_search : ''; ?>" class="regform__input <?php echo !empty($brand_search) ? 'valid' : ''; ?>" data-input-change/>
			<div class="regform__plh">Название компании</div>
		</div>
		<button type="button" class="btn btn-invert regbrand__start--search" id="regbrand-search">+ Найти</button>
	</div>
</div>
