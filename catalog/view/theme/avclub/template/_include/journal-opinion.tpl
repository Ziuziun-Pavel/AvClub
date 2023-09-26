<a href="<?php echo $journal['href']; ?>" class="opinion__item wish__outer link__outer">
	<?php echo !empty($journal['type_text']) ? '<div class="news__type">'.$journal['type_text'].'</div>' : ''; ?>
	<span class="opinion__inner">
		<span class="opinion__img">
			<span class="opinion__image">
				<img src="<?php echo $journal['thumb']; ?>" alt="">
			</span>
		</span>
		<span class="opinion__name"><?php echo $journal['author']; ?></span>
		<span class="opinion__exp"><?php echo $journal['exp']; ?></span>
	</span>
	<span class="opinion__preview">
		<span class="link"><?php echo $journal['title']; ?></span>
	</span>
	<?php if(!empty($journal_date) && !empty($journal['date'])) { ?>
		<span class="news__item_date date"><?php echo $journal['date']; ?></span>
	<?php } ?>
	<button class="wish wish-<?php echo $journal['journal_id']; ?> <?php echo $journal['wish'] ? 'active' : ''; ?>" type="button" data-id="<?php echo $journal['journal_id'];  ?>"><svg class="ico"><use xlink:href="#wish" /></svg></button>
</a>