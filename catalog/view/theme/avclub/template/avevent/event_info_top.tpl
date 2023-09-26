<section id="top" class="section_tevent">
	<div class="container">
		<div class="tevent__cont row">
			
			<div class="tevent__data col-md-7 col-lg-6">
				<div class="tevent__type"><?php echo $type; ?> <?php echo $city; ?> </div>
				<div class="tevent__name title">
					<h1><?php echo $heading_title; ?></h1>
				</div>
				<div class="tevent__attr clearfix">
					<span><?php echo $date; ?></span>
					<span><?php echo $time_start; ?> — <?php echo $time_end; ?></span>
					<span><?php echo $address_full; ?></span>
				</div>
				<div class="tevent__btn">
					<a href="<?php echo $link; ?>" class="btn btn-red" target="_blank" onclick="yaGoal('clik_zaregestrirovatsy');">
						<span>Бесплатная регистрация</span>
					</a>
				</div>
			</div>
			<div class="tevent__img col-md-5 col-lg-6">
				<?php if($image) { ?>
					<div class="tevent__image">
						<img src="<?php echo $image; ?>" alt="">
					</div>
				<?php } ?>
				<div class="tevent__btn">
					<a href="<?php echo $link; ?>" class="btn btn-red" target="_blank">
						<span>Бесплатная регистрация</span>
					</a>
				</div>
			</div>

		</div>
	</div>
</section>