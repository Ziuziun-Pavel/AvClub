<?php $theme_dir = 'catalog/view/theme/avclub'; ?>

<?php echo $header; ?>
<?php echo $content_top; ?>
<section class="section_content section_wish">
	<div class="container">

		<?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

		<div class="content__title">
			<h1><?php echo $heading_title; ?></h1>
		</div>

		<?php if($journals) { ?>
			<ul class="wish__tabs list-hor">
				<?php foreach($filters as $key=>$item) { ?>
					<?php if(!$item['visible']) {continue;} ?>
					<li><button type="button" class="wish__tab wish__tab-<?php echo $key; ?> <?php echo $key === '' ? 'active' : ''; ?>" data-filter="<?php echo $key; ?>"><?php echo $item['title']; ?></button></li>
				<?php } ?>
			</ul>
		<?php } ?>

		<div class="content__cont content__cont-search">
			<div class="search__row or__row row">
				<?php if($journals) { ?>

					<?php $journal_date = true; ?>
					<?php foreach($journals as $journal) { ?>
						<div class="news__outer filter__item <?php echo 'filter-' . $journal['type']; ?> col-sm-6 col-lg-4 col-xl-3">
							<?php if($journal['type'] === 'opinion') { ?>
								<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-opinion.tpl'); ?>
							<?php }else{ ?>
								<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-article.tpl'); ?>
							<?php } ?>
						</div>
					<?php } ?>

				<?php } ?>

				<div class="master__outer filter__empty master__empty text col-12 <?php echo $journals ? 'd-none' : ''; ?>">
					<h4>В избранном пока ничего нет</h4>
					<div class="master__goto">
						<a href="<?php echo $continue; ?>" class="link_under goTo">Вернуться на главную</a>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>
<?php echo $content_bottom; ?>

<script>
	$(function(){
		$(document).on('click', '.wish__tab', function(e) {
			e.preventDefault();
			var $filter = $(this).attr('data-filter');

			if(!$(this).hasClass('active')) {
				$('.wish__tab.active').removeClass('active');
				$(this).addClass('active');

				$('.filter__item').removeClass('d-none');
				if($filter) {	
					$('.filter__item').not('.filter-' + $filter).addClass('d-none');
				}
			}

		})
	})
</script>
<?php echo $footer; ?>