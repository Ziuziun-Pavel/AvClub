
<div class="regbrand__start">
	<div class="regbrand__start--title">Укажите название торговой <br>марки/бренда компании в которой вы работаете</div>
	<div class="regbrand__start--subtitle">Введите название компании в поиск и мы найдем ее в каталоге</div>
	<div class="regbrand__start--inp">
		<div class="regform__inp regform__inp-plh">
			<input type="text" name="brand" value="<?php echo !empty($brand_search) ? $brand_search : ''; ?>" class="regform__input <?php echo !empty($brand_search) ? 'valid' : ''; ?>" data-input-change/>
			<div class="regform__plh">Название компании</div>
		</div>
		<button type="button" class="btn btn-invert regbrand__start--search" id="regbrand-search">+ Найти</button>
	</div>
</div>
