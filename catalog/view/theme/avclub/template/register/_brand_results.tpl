<div class="regbrand__result">
	
	<div class="regbrand__result--top">
		<div class="regbrand__result--left">
			<div class="regbrand__result--title">Результаты поиска</div>
		</div>
	</div>

	<div class="regbrand__result--data">
		
		<div class="regbrand__list">
			<div class="regbrand__list--top">
				<div class="regbrand__list--text">
					<?php echo html_entity_decode($text_result); ?>
				</div>
				<div class="regbrand__list--change">
					<a href="#" class="link" id="regbrand-change" data-search="<?php echo $search; ?>">+ Изменить поиск</a>
				</div>
			</div>
			<div class="regbrand__list--title">Выберите вашу компанию из&nbsp;списка</div>
			<div class="regbrand__list--list">
				<?php foreach($companies as $company) { ?>
					<button type="button" class="btn btn-invert regbrand--choose" data-id="<?php echo $company['id']; ?>"><?php echo $company['title']; ?></button>
				<?php } ?>
			</div>
			<div class="regbrand__list--line"></div>
			<div class="regbrand__list--title">
				Если вашей компании нет в списке, <br>
				вы можете добавить новую компанию
			</div>
			<div class="regbrand__list--add">
				<button type="button" class="btn btn-invert" id="regbrand-add" data-search="<?php echo $search; ?>">
					Добавить компанию <?php echo $search; ?>
				</button>
			</div>
		</div>

	</div>

</div>