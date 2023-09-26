<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<section class="section_ehead section_ehead-fixed fixed__item">
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
				<a href="<?php echo $link; ?>" class="btn btn-red" target="_blank" onclick="yaGoal('clik_zaregestrirovatsy');">
					<span>Зарегистрироваться</span>
				</a>
			</div>
			<div class="ehead__reg">
				<a href="<?php echo $link; ?>" class="link_under" target="_blank">
					<span>Зарегистрироваться</span>
				</a>
			</div>
			<div class="ehead__login">
				<a href="<?php echo $logged ? $account : $login; ?>" >
					<svg class="ico ico-center"><use xlink:href="#<?php echo $logged ? 'cabinet' : 'login'; ?>" /></svg>
				</a>
			</div>

		</div>
	</div>
</section>