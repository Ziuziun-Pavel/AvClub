<section id="speaker" class="section_speak">
	<div class="container">
		<?php if($speaker_title) { ?>
			<div class="title etitle">
				<h2><?php echo $speaker_title; ?></h2>
			</div>
		<?php } ?>
		<div class="speak__cont">

			
			<div class="speak__slider">
				
				<?php foreach($speaker_list as $speaker) { ?>
					<div class="speak__slide">
						<<?php echo !empty($speaker['href']) ? 'a href="'.$speaker['href'].'"' : 'span'; ?> class="opinion__item link__outer">
						<span class="opinion__inner">
							<span class="opinion__img">
								<span class="opinion__image">
									<img src="<?php echo $speaker['image']; ?>" alt="">
									<?php if(!empty($speaker['href'])) { ?>
										<span class="opinion__expert"><svg class="ico ico-center"><use xlink:href="#star" /></svg></span>
									<?php } ?>
								</span>

							</span>
							<span class="opinion__name"><?php echo $speaker['name']; ?></span>
							<span class="opinion__exp"><?php echo $speaker['exp']; ?></span>
						</span>
						</<?php echo !empty($speaker['href']) ? 'a' : 'span'; ?>>
					</div>
				<?php } ?>

			</div>
			<div class="speak__nav nav__cont2">
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
		var speakSlider = $('.speak__slider').slick({
			infinite: true,
			slidesToShow: 4,
			rows: <?php echo count($speaker_list) > 8 ? '2' : '1'; ?>,
			slidesToScroll: 1,
			dots: true,
			appendDots: $('.speak__nav .nav__dots2'),
			prevArrow: '.section_speak .nav__prev',
			nextArrow: '.section_speak .nav__next',
			responsive: [
			{
				breakpoint: 1300,
				settings: {
					slidesToShow: 3,
					rows: 1,
				},
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2
				},
			},
			{
				breakpoint: 510,
				settings: {
					rows: 1,
					slidesToShow: 1
				},
			}
			]
		})
	})
</script>