<div class="icomp__page">
	<div class="row">
		<?php if($experts) { ?>
			<?php foreach($experts as $expert) { ?>
				<div class="news__outer col-sm-6 col-xl-4">
					<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-expert.tpl'); ?>
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