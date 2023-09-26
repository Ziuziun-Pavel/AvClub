<?php $theme_dir = 'catalog/view/theme/avclub'; ?>

<?php echo $header; ?>
<?php echo $content_top; ?>
<section class="section_content section_tag">
	<div class="container">

		<?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

		<div class="content__title">
			<h1>Все материалы по тегу «<?php echo $heading_title; ?>»</h1>
		</div>
		<div class=" content__cont">

			<?php if($journals) { ?>

				<div class="search__row or__row row">

					<?php $journal_date = true; ?>
					<?php foreach($journals as $journal) { ?>
						<div class="news__outer col-sm-6 col-lg-4 col-xl-3">
							<?php if($journal['type'] === 'opinion') { ?>
								<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-opinion.tpl'); ?>
							<?php }else{ ?>
								<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-article.tpl'); ?>
							<?php } ?>
						</div>
					<?php } ?>

					<?php if($banner) { ?>
						<div class="news__outer news__outer-banner col-sm-6 col-lg-4 col-xl-3">
							<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-banner.tpl'); ?>
						</div>
					<?php } ?>

					<div class="news__outer news__outer-master col-sm-6 col-lg-4 col-xl-3">
						<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-master.tpl'); ?>
					</div>

				</div>

				<?php if($pagination) { ?>
					<div class="page__row"><?php echo $pagination; ?></div>
				<?php } ?>

			<?php }else{ ?>
				<div class="master__outer master__empty text col-12">
					<h4>Данный тег обновляется</h4>
					<p>Подождите еще немного ;)</p>
					<div class="master__goto">
						<a href="<?php echo $continue; ?>" class="link_under goTo">Вернуться на главную</a>
					</div>
				</div>
			<?php } ?>

		</div>

	</div>
</section>
<?php echo $content_bottom; ?>
<?php echo $footer; ?>