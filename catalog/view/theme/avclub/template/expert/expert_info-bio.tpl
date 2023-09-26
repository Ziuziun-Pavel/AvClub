<div class="expert__bio">
	<div class="expert__bio--title">биография эксперта</div>
	<?php foreach($bio as $bio_key=>$bio_item) { ?>
		<?php echo $bio_key == 1 ? '<div class="expert__bio--more">' : ''; ?>
		<div class="expert__bio--item">
			<div class="expert__bio--caption"><?php echo $bio_item['title']; ?></div>
			<div class="expert__bio--text">
				<?php echo nl2br($bio_item['text']); ?>
			</div>
		</div>
	<?php } ?>
	<?php echo count($bio) > 1 ? '</div>' : ''; ?>
	<?php if(count($bio) > 1) { ?>
		<div class="expert__bio--btn">
			<a href="#" class="link link_under" data-passive="Подробнее" data-active="Скрыть">Подробнее</a>
		</div>
	<?php } ?>
</div>