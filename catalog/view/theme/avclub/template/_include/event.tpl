<?php if(!empty($event['show_event'])) { ?>
	<a href="<?php echo $event['href']; ?>" class="event__item link__outer">
		<img src="<?php echo $event['thumb']; ?>" alt="" class="event__img">
		<span class="event__type">
			<span><?php echo $event['type']; ?></span>
			<span><?php echo $event['city']; ?></span>
		</span>
		<span class="event__name"><span class="link"><?php echo $event['title']; ?></span></span>
		<span class="event__address"><?php echo $event['date'] . ', ' . $event['time_start'] . ' - ' . $event['time_end'] . ', ' . $event['address']; ?></span>
	</a>
<?php }else{ ?>
	<span class="event__item ">
		<img src="<?php echo $event['thumb']; ?>" alt="" class="event__img">
		<span class="event__type">
			<span><?php echo $event['type']; ?></span>
			<span><?php echo $event['city']; ?></span>
		</span>
		<span class="event__name"><span class=""><?php echo $event['title']; ?></span></span>
		<span class="event__address"><?php echo $event['date'] . ', ' . $event['time_start'] . ' - ' . $event['time_end'] . ', ' . $event['address']; ?></span>
	</span>
	<?php } ?>