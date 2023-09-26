<?php 
$item_class = ''; 
$item_class .= $journal['type'] === 'video' ? ' news__video ' : '';
$item_class .= (!empty($journal_class)) ? ' ' . $journal_class . ' ' : '';
?>
<a href="<?php echo $journal['href']; ?>" class="news__item <?php echo $item_class; ?> wish__outer link__outer" <?php echo !empty($journal['blank']) && $journal['blank'] ? 'target="_blank"' : ''; ?>>
	<?php echo !empty($journal['type_text']) ? '<div class="news__type">'.$journal['type_text'].'</div>' : ''; ?>
	<span class="news__img">
		<span class="news__image"><img src="<?php echo $journal['thumb']; ?>" alt=""></span>
		<?php if($journal['type'] === 'video') { ?>
			<span class="news__play">
				<svg class="ico"><use xlink:href="#play" /></svg>
			</span>
		<?php } ?>
	</span>
	<span class="news__name">

		<?php if(!empty($journal_date) && !empty($journal['date'])) { ?>
			<span class="news__item_date date"><?php echo $journal['date']; ?></span>
		<?php } ?>
		<span class="link"><?php echo $journal['title']; ?></span>
	</span>
	<?php if($journal['type'] === 'case' && !empty($journal['case'])) { ?>
		<span class="news__case">
			<span>Интегратор: <strong><?php echo $journal['case']['title']; ?></strong></span>
			<?php if(!empty($journal['case']['attr'])) { ?>
				<?php foreach($journal['case']['attr'] as $attr) { ?>
					<?php if(!$attr['catalog']){continue;} ?>
					<span><?php echo $attr['title']; ?>: <strong><?php echo $attr['text']; ?></strong></span>
				<?php } ?>
			<?php } ?>
		</span>
		<?php if(!empty($journal_date) && !empty($journal['date'])) { ?>
			<span class="news__item_date date"><?php echo $journal['date']; ?></span>
		<?php } ?>
	<?php }else{ ?>
		<span class="news__preview"><?php echo $journal['preview']; ?></span>
		<?php if(!empty($journal_date) && !empty($journal['date'])) { ?>
			<span class="news__item_date date"><?php echo $journal['date']; ?></span>
		<?php } ?>
	<?php } ?>
	<button class="wish wish-<?php echo $journal['journal_id']; ?> <?php echo $journal['wish'] ? 'active' : ''; ?>" type="button" data-id="<?php echo $journal['journal_id'];  ?>"><svg class="ico"><use xlink:href="#wish" /></svg></button>
</a>