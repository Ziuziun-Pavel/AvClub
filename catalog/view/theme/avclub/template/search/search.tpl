<?php $theme_dir = 'catalog/view/theme/avclub'; ?>

<?php echo $header; ?>
<?php echo $content_top; ?>
<section class="section_content section_search">
	<div class="container">

		<?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

		<div class="content__sub">
			<h1>Результаты поиска</h1>
		</div>

		<form action="#" class="search__form search__form-page search__form-content">

			<div class="search__inp">
				<input type="text" name="search" class="search__input active" placeholder="<?php echo $search; ?> (при клике запрос можно изменить)" value="" data-value="<?php echo $search; ?>">
				<button type="submit" class="search__submit">
					<svg class="ico ico-center"><use xlink:href="#long-arrow-right" /></svg>
				</button>
			</div>
			<div class="clearfix"></div>
			<div class="search__bottom row">
				<div class="search__filter col-md-8">
					<?php /* foreach($filters as $filter) { ?>
						<a href="<?php echo $filter['href']; ?>" class="search__radio <?php echo $search_type === $filter['value'] ? 'active' : ''; ?>">
							<span><?php echo $filter['text']; ?></span>
						</a>
					<?php } */?>
					<label class="search__radio">
						<input type="radio" name="search_type" value="" <?php echo !$search_type ? 'checked' : ''; ?>>
						<span>Весь сайт</span>
					</label>
					<label class="search__radio">
						<input type="radio" name="search_type" value="journal" <?php echo $search_type === 'journal' ? 'checked' : ''; ?>>
						<span>Журнал</span>
					</label>
					<label class="search__radio">
						<input type="radio" name="search_type" value="master" <?php echo $search_type === 'master' ? 'checked' : ''; ?>>
						<span>Онлайн-события</span>
					</label>
					<label class="search__radio">
						<input type="radio" name="search_type" value="event" <?php echo $search_type === 'event' ? 'checked' : ''; ?>>
						<span>Мероприятия</span>
					</label>
					<label class="search__radio">
						<input type="radio" name="search_type" value="company" <?php echo $search_type === 'company' ? 'checked' : ''; ?>>
						<span>Компании</span>
					</label>
				</div>
				<div class="search__sort col-md-4">
					<?php foreach($sorts as $item) { ?>
						<a href="<?php echo $item['href']; ?>" class="<?php echo $sort === $item['value'] ? 'active' : ''; ?>"><?php echo $item['text']; ?> <svg class="ico"><use xlink:href="#arr-down" /></svg></a>
					<?php } ?>
				</div>
			</div>

		</form>
		<div class="content__cont content__cont-search">
			<div class="search__row or__row row">
				<?php if($results) { ?>
					<?php $journal_date = true; ?>
					<?php foreach($results as $journal) { ?>
						<div class="news__outer <?php echo $journal['type'] === 'company' ? 'comp__outer' : ''; ?> col-sm-6 col-lg-4 col-xl-3">
							<?php 
							switch($journal['type']) {
								case 'event':
								require(DIR_TEMPLATE . 'avclub/template/_include/journal-search-event.tpl');
								break;

								case 'master':
								require(DIR_TEMPLATE . 'avclub/template/_include/journal-search-master.tpl');
								break;

								case 'opinion':
								require(DIR_TEMPLATE . 'avclub/template/_include/journal-opinion.tpl');
								break;

								case 'company':
								require(DIR_TEMPLATE . 'avclub/template/_include/journal-company.tpl');
								break;

								default:
								require(DIR_TEMPLATE . 'avclub/template/_include/journal-article.tpl');
							}
							?>
						</div>
					<?php } ?>

				<?php }else{ ?>
					<div class="master__outer master__empty text col-12">
						<h4>По Вашему запросу ничего не найдено</h4>
						<div class="master__goto">
							<a href="<?php echo $continue; ?>" class="link_under goTo">Вернуться на главную</a>
						</div>
					</div>
				<?php } ?>
			
			</div>
			<?php if(!empty($pagination)) { ?>
				<div class="page__row"><?php echo $pagination; ?></div>
			<?php } ?>
		</div>

	</div>
</section>
<script>
	$(function(){
		$('.search__form-page input[name="search"]').on('focus', function(e){
			var $input = $(this);

			if(!$input.val().length) {
				$input.val($input.attr('data-value'));
			}
		}).on('focusout', function(e){
			var 
			$input = $(this),
			$value = $input.val();
			$input.attr('placeholder', $value + ' (при клике запрос можно изменить)');
			$input.attr('data-value', $value);
			$input.val('');
		})

		$('.search__form-content input[name="search_type"]').on('change', function(){
			$(this).closest('form').submit();
		})
	})
</script>
<?php echo $content_bottom; ?>

<?php echo $footer; ?>