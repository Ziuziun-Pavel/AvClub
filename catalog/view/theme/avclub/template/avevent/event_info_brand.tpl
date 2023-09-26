<section id="brands" class="section_brand section_brand-page">
	<div class="container">
		<?php if($brand_title) { ?>
			<div class="title etitle"><h2><?php echo $brand_title; ?></h2></div>
		<?php } ?>

		<?php if($brand_template == 1) { ?>
			<?php /* SLIDER */ ?>
			<div class="brand__cont">
				<div class="brand__slider">	
					<?php foreach($brand_list as $brand) { ?>
						<<?php echo $brand['href'] ? 'a href="' . $brand['href'] . '"' : 'span'; ?>>
							<img src="<?php echo $brand['image']; ?>" alt="<?php echo $brand['title']; ?>">
						</<?php echo $brand['href'] ? 'a' : 'span'; ?>>
						<?php /* ?>
						<img src="<?php echo $brand['image']; ?>" alt="<?php echo $brand['title']; ?>">
						<?php */ ?>
					<?php } ?>
				</div>
				<div class="brand__nav nav__cont2">
					<button type="button" class="nav__item nav__prev nav__slide"><span><svg><use xlink:href="#arr-left" /></svg></span></button>
					<div class="nav__dots2"></div>
					<button type="button" class="nav__item nav__next nav__slide"><span><svg><use xlink:href="#arr-right" /></svg></span></button>
				</div>
			</div>
			<script>
				$(function(){
					var brandSlider = $('.brand__slider').slick({
						infinite: true,
						slidesToShow: 6,
						rows: 2,
						slidesToScroll: 1,
						dots: true,
						appendDots: $('.brand__cont .nav__dots2'),
						prevArrow: '.section_brand .nav__prev',
						nextArrow: '.section_brand .nav__next',
						responsive: [				
						{
							breakpoint: 1300,
							settings: {
								slidesToShow: 5,
								rows: 2
							},
						},
						{
							breakpoint: 992,
							settings: {
								slidesToShow: 4,
								rows: 3
							},
						},
						{
							breakpoint: 768,
							settings: {
								slidesToShow: 3,
								rows: 3
							},
						}
						]
					})
				})
			</script>
			<?php /* # SLIDER */ ?>
		<?php }else{ ?>
			<?php /* DEFAULT */ ?>
			<div class="brand__row row">
				<?php foreach($brand_list as $brand) { ?>
					<div class="brand__outer col-6 col-sm-3">
						<a href="<?php echo $brand['href']; ?>">
							<img src="<?php echo $brand['image']; ?>" alt="<?php echo $brand['title']; ?>">
						</a>
						<?php /* ?>
						<img src="<?php echo $brand['image']; ?>" alt="<?php echo $brand['title']; ?>">
						<?php */ ?>
					</div>
				<?php } ?>

			</div>
			<?php /* # DEFAULT */ ?>
		<?php } ?>

		
	</div>
</section>