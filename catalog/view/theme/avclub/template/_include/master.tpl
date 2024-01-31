<div class="master__item">
	<div class="master__img">
		<div class="master__left">
			<a href="<?php echo $master['href']; ?>" class="master__image">
				<?php /* if(!empty($master['logo'])) { ?>
					<img src="<?php echo $master['logo']; ?>" alt="" class="master__logo">
				<?php } */ ?>
				<img src="<?php echo $master['image']; ?>" alt="">
			</a>
			<a href="<?php echo $master['link']; ?>" class="btn btn-red master__reg" target="_blank">
				<span>Зарегистрироваться</span>
			</a>
		</div>
		
	</div>
	<div class="master__data">
		<div class="master__date date"><?php echo $master['date']; ?> <span><?php echo $master['time']; ?></span></div>
		<a href="<?php echo $master['href']; ?>"><div class="master__name"><?php echo $master['title']; ?></div></a>
		<div class="master__preview">
			<p><strong>В программе:</strong></p>
			<?php echo html_entity_decode($master['preview']); ?>
		</div>
		<?php /* ?>
		<div class="master__more">
			<a href="<?php echo $master['link']; ?>" target="_blank"><svg class="ico ico-center"><use xlink:href="#dots" /></svg></a>
		</div>
		<?php */ ?>
		<?php if($master['author']) { ?>
			<div class="master__author">
				<p><strong><?php echo $master['author']; ?></strong></p>
				<p><?php echo $master['exp']; ?></p>
			</div>
		<?php } ?>
	</div>
</div>