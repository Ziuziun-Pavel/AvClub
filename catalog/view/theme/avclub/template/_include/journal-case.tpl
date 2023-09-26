<a href="<?php echo $journal['href']; ?>" class="case__item wish__outer link__outer">
	<span class="case__img">
		<img src="<?php echo $journal['thumb']; ?>" alt="">
	</span>
	<span class="case__data">
		<?php if(!empty($journal_date) && !empty($journal['date'])) { ?>
			<span class="case__date date"><?php echo $journal['date']; ?></span>
		<?php } ?>
		<span class="case__name link"><?php echo $journal['title']; ?></span>
		<?php if(!empty($journal['case'])) { ?>
			<span class="case__info">
				<span class="case__attr">Интегратор: <strong><?php echo $journal['case']['title']; ?></strong></span>
				<?php if(!empty($journal['case']['attr'])) { ?>
					<?php foreach($journal['case']['attr'] as $attr) { ?>
						<?php if(!$attr['catalog']){continue;} ?>
						<span class="case__attr"><?php echo $attr['title']; ?>: <strong><?php echo $attr['text']; ?></strong></span>
					<?php } ?>
				<?php } ?>
			</span>
		<?php } ?>
	</span>
	<button class="wish wish-<?php echo $journal['journal_id']; ?> <?php echo $journal['wish'] ? 'active' : ''; ?>" type="button" data-id="<?php echo $journal['journal_id'];  ?>"><svg class="ico"><use xlink:href="#wish" /></svg></button>
</a>