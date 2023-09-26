<section id="point" class="section_evpoint">
	<div class="container">
		
		<div class="evpoint__row row">
			<?php foreach($plus_list as $plus) { ?>
				<div class="evpoint__outer col-lg-4">
					<div class="evpoint__item">
						<div class="evpoint__img"><img src="<?php echo $plus['image']; ?>" alt=""></div>
						<div class="evpoint__name"><?php echo $plus['title']; ?></div>
						<div class="evpoint__text">
							<?php echo $plus['text']; ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

	</div>
</section>