<a href="<?php echo $journal['href']; ?>" class="case__item wish__outer link__outer" target="_blank">
	<img src="<?php echo $journal['thumb']; ?>" alt="">
	<span class="case__data">
		<span class="case__name link"><?php echo $journal['title']; ?></span>
	</span>
	<button class="wish wish-<?php echo $journal['journal_id']; ?> <?php echo $journal['wish'] ? 'active' : ''; ?>" type="button" data-id="<?php echo $journal['journal_id'];  ?>"><svg class="ico"><use xlink:href="#wish" /></svg></button>
</a>