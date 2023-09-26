<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<div class="nlist__cont nlist__col nlist__master <?php echo $master_list ? '' : 'nlist__cont-masteradd'; ?>">
	<?php if($master_list) { ?>
		<div class="title">ОНЛАЙН-СОБЫТИЯ</div>
		<ul class="nlist__list list-vert">
			<?php foreach($master_list as $item) { ?>
				<li>
					<a href="<?php echo $item['href']; ?>" class="nlist__item link_outer" target="_blank">
						<span class="nlist__title link"><?php echo $item['title']; ?></span>
						<span class="nlist__name"><?php echo $item['author']; ?></span>
						<span class="nlist__date date"><?php echo $item['date']; ?> <span><?php echo $item['time']; ?></span></span>
					</a>
				</li>
			<?php } ?>
		</ul>
		<div class="nlist__more">
			<a href="<?php echo $master_all; ?>" class="link_under">ВСЕ ОНЛАЙН-СОБЫТИЯ</a>
		</div>
	<?php }else{ ?>
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

	<?php } ?>

</div>
