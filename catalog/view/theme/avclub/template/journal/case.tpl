<?php echo $header; ?>
<?php echo $content_top; ?>
<section class="section_content section_case">
	<div class="container">

		<?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

		<div class="content__title">
			<h1><?php echo $heading_title; ?></h1>
		</div>
		<div class=" content__cont">

			<div class="case__row or__row row">

				<?php if($journals) { ?>

					<?php $m = 0; ?>
					<?php $journal_date = true; ?>
					<?php foreach($journals as $journal) {$m++; ?>
						<div class="case__outer col-sm-6 col-xl-6">
							<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-case.tpl'); ?>
						</div>
					<?php } ?>

					<div class="case__outer case__outer-master col-sm-6 col-lg-3 col-xl-3">
						<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-master.tpl'); ?>
					</div>

					<?php if($banner) { ?>
						<div class="case__outer case__outer-banner col-sm-6 col-lg-3 col-xl-3">
							<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-banner.tpl'); ?>
						</div>
					<?php } ?>


				<?php }else{ ?>
					<div class="master__outer master__empty text col-12">
						<h4>Раздел мнений обновляется</h4>
						<p>Подождите еще немного ;)</p>
						<div class="master__goto">
							<a href="<?php echo $continue; ?>" class="link_under goTo">Вернуться на главную</a>
						</div>
					</div>
				<?php } ?>

				

			</div>

			<?php if($pagination) { ?>
				<div class="page__row">
					<?php echo $pagination; ?>
				</div>
			<?php } ?>

		</div>

	</div>
</section>
<?php echo $content_bottom; ?>
<?php echo $footer; ?>