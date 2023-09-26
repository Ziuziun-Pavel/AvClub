<section id="present" class="section_equip">
	<div class="container">
		<?php if($present_title) { ?>
			<div class="title etitle">
				<h2><?php echo $present_title; ?></h2>
			</div>
		<?php } ?>
		<div class="equip__cont">
			<div class="equip__slider">
				<?php foreach($present_list as $present) { ?>
					<div class="equip__slide">
						<?php echo $present['href'] ? '<a href="'.$present['href'].'">' : ''; ?>
						<img src="<?php echo $present['image']; ?>" alt="">
						<?php echo $present['href'] ? '</a>' : ''; ?>
					</div>
				<?php } ?>

			</div>
			<div class="equip__nav nav__cont2">
				<button type="button" class="nav__item nav__prev nav__slide"><span><svg><use xlink:href="#arr-left" /></svg></span></button>
				<div class="nav__drag"><svg class="ico"><use xlink:href="#drag" /></svg></div>
				<div class="nav__dots2"></div>
				<button type="button" class="nav__item nav__next nav__slide"><span><svg><use xlink:href="#arr-right" /></svg></span></button>
			</div>
		</div>
	</div>
</section>
<script>
	$(function(){
		var equipSlider = $('.equip__slider').slick({
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 1,
			dots: true,
			appendDots: $('.equip__nav .nav__dots2'),
			prevArrow: '.section_equip .nav__prev',
			nextArrow: '.section_equip .nav__next',
			responsive: [
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 3,
				},
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
				},
			},
			{
				breakpoint: 510,
				settings: {
					slidesToShow: 1,
				},
			}
			]
		})
	})
</script>