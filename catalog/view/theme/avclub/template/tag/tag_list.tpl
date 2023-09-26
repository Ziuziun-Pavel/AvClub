<?php $theme_dir = 'catalog/view/theme/avclub'; ?>

<?php echo $header; ?>
<?php echo $content_top; ?>
<section class="section_content section_tag">
	<div class="container">

		<?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

		<div class="content__title">
			<h1><?php echo $heading_title; ?></h1>
		</div>
		<div class=" content__cont">

			<div class="tag__row row">

				<?php if($tags) { ?>

					<?php foreach(array_chunk($tags, ceil(count($tags) / 4)) as $key=>$column) { ?>
						<ul class="tag__list order-1 list-vert col-sm-6 col-md-3">
							<?php foreach($column as $tag) { ?>
								<li><a href="<?php echo $tag['href']; ?>" class="link"><?php echo $tag['title']; ?></a></li>
							<?php } ?>
						</ul>
					<?php } ?>

				<?php }else{ ?>
					<div class="master__outer master__empty text col-12">
						<h4>Раздел тегов обновляется</h4>
						<p>Подождите еще немного ;)</p>
						<div class="master__goto">
							<a href="<?php echo $continue; ?>" class="link_under goTo">Вернуться на главную</a>
						</div>
					</div>
				<?php } ?>

			</div>

		</div>

	</div>
</section>
<?php echo $content_bottom; ?>
<?php echo $footer; ?>