<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<section class="section_letter">
	<div class="container">
		<div class="letter__cont ">
			<div class="row">
				<div class="letter__img col-sm-5 col-md-6 col-xl-5 order-sm-2">
					<div class="letter__image">
						<img src="<?php echo $theme_dir; ?>/img/letter.svg" alt="">
					</div>
				</div>
				<div class="letter__data col-sm-7 col-md-6 col-xl-7 order-sm-1">
					<div class="letter__title"><?php echo html_entity_decode($title); ?></div>
					<div class="letter__text">
						<?php echo html_entity_decode($text); ?>
					</div>
					<form action="#" class="letter__form">
						<input type="text" name="email" class="letter__input" placeholder="Ваш e-mail">
						<button type="submit" class="letter__submit">
							<svg class="ico"><use xlink:href="#arrow-right" /></svg>
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>