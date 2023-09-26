<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<div class="imaster__cont">
	<div class="imaster__img">
		<img src="<?php echo $theme_dir; ?>/images/master/imaster.svg" alt="">
	</div>
	<div class="imaster__data">
		<div class="imaster__title"><?php echo $master_info['title']; ?></div>
		<div class="imaster__text"><?php echo $master_info['description']; ?></div>
		<?php if($master_info['link']) { ?>
			<div class="imaster__link">
				<a href="<?php echo $master_info['link']; ?>" class="link_under" target="_blank"><?php echo $master_info['button'] ? $master_info['button'] : 'Подробнее'; ?></a>
			</div>
		<?php } ?>
	</div>
</div>