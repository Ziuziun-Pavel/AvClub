<?php if(!empty($journal['show_event'])) { ?>
	<a href="<?php echo $journal['href']; ?>" class="news__item link__outer" >
		<?php echo !empty($journal['type_text']) ? '<div class="news__type">'.$journal['type_text'].'</div>' : ''; ?>
		<span class="news__img">
			<span class="news__image"><img src="<?php echo $journal['image']; ?>" alt=""></span>
		</span>
		<span class="news__name">
			<span class="link"><?php echo $journal['title']; ?></span>
		</span>
		<span class="news__preview">
			<?php echo $journal['date'] . ', ' . $journal['time_start'] . ' - ' . $journal['time_end'] . ', ' . $journal['address']; ?>
		</span>
	</a>
<?php }else{ ?>
	<span class="news__item link__outer" >
		<?php echo !empty($journal['type_text']) ? '<div class="news__type">'.$journal['type_text'].'</div>' : ''; ?>
		<span class="news__img">
			<span class="news__image"><img src="<?php echo $journal['image']; ?>" alt=""></span>
		</span>
		<span class="news__name">
			<span class=""><?php echo $journal['title']; ?></span>
		</span>
		<span class="news__preview">
			<?php echo $journal['date'] . ', ' . $journal['time_start'] . ' - ' . $journal['time_end'] . ', ' . $journal['address']; ?>
		</span>
	</span>
	<?php } ?>