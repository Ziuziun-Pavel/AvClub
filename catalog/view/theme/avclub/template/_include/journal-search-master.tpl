<a href="<?php echo $journal['href']; ?>" class="news__item link__outer" target="_blank">
	<?php echo !empty($journal['type_text']) ? '<div class="news__type">'.$journal['type_text'].'</div>' : ''; ?>
	<span class="news__img">
		<span class="news__image">
			<?php if(!empty($journal['logo'])) { ?>
				<img src="<?php echo $journal['logo']; ?>" alt="" class="master__logo">
			<?php } ?>
			<img src="<?php echo $journal['image']; ?>" alt="">
			</span>
	</span>
	<span class="news__name">
		<span class="link"><?php echo $journal['title']; ?></span>
	</span>
	<span class="news__author news__author-master">
		<span><strong><?php echo $journal['author']; ?></strong></span>
		<span><?php echo $journal['exp']; ?></span>
	</span>
</a>