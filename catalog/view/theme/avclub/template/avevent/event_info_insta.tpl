<section id="insta" class="section_einsta">
	<div class="container">
		<?php if($insta_title) { ?>
		<div class="title etitle">
			<h2>
				<?php echo html_entity_decode($insta_title); ?>
			</h2>
		</div>
	<?php } ?>
		<div class="einsta__row row">
			<?php foreach($insta_list as $insta) { ?>
			<div class="einsta__outer col-4">
				<?php echo $insta['href'] ? '<a href="' . $insta['href'] . '" target="_blank">' : ''; ?>
				<img src="<?php echo $insta['image']; ?>" alt="">
				<?php echo $insta['href'] ? '</a>' : ''; ?>
			</div>
		<?php } ?>
		</div>
	</div>
</section>