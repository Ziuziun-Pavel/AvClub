<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<section class="section_ehead">
	<div class="container">
		<div class="ehead__row">

			<div class="ehead__back">
				<a href="<?php echo $all_events; ?>" class="nav__item nav__prev nav__slide">
					<span>
						<svg class="ico ico-center"><use xlink:href="#arr-left" /></svg>
					</span>
				</a>
			</div>
			<div class="ehead__logo">
				<?php $logo_img = $theme_dir . '/images/logo.svg'; ?>
				<a href="<?php echo $home; ?>"><img src="<?php echo $logo_img; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
			</div>
			<div class="ehead__attr">
				<span><?php echo $type; ?> <?php echo $city; ?></span>
				<span><?php echo $date; ?></span>
				<span><?php echo $time_start; ?> — <?php echo $time_end; ?></span>
				<span><?php echo $address_full; ?></span>
			</div>
			<div class="ehead__btn">
				<a href="<?php echo $register; ?>" class="btn btn-red" target="_blank" onclick="yaGoal('clik_zaregestrirovatsy');">
					<span>Зарегистрироваться</span>
				</a>
			</div>

		</div>
	</div>
</section>