<?php echo $header; ?>
<?php echo $content_top; ?>
<section class="section_content section_company-item">
	<div class="container">

		<?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

		<div class="content__title">
			<h1><?php echo $heading_title; ?></h1>
		</div>
		<div class="content__cont">

			<div class="icomp__cont row" data-id="<?php echo $company_id; ?>">

				<div class="icomp__data col-md-4 col-xl-3">
					<div class="icomp__info">
						<?php if($image) { ?>
							<div class="icomp__logo">
								<img src="<?php echo $image; ?>" alt="">
							</div>
						<?php } ?>
						<?php if(!empty($preview) || !empty($activity)) { ?>
							<div class="icomp__preview">
								<?php echo $activity ? '<p><strong>' . $activity . '</strong></p>' : ''; ?>
								<?php echo $preview; ?>
							</div>
						<?php } ?>
						<?php if(!empty($phones)) { ?>
							<ul class="icomp__phones list-vert">
								<?php foreach($phones as $phone) { ?>
									<li><a href="tel:+<?php echo preg_replace('/[^0-9]/', '', $phone); ?>" class="link"><?php echo $phone; ?></a></li>
								<?php } ?>
							</ul>
						<?php } ?>
						<?php if(!empty($links) || !empty($social)) { ?>
							<div class="icomp__more">
								<?php if(!empty($links)) { ?>
									<ul class="icomp__links list-vert">
										<?php foreach($links as $item) { 
											switch($item['type']) {
												case 'mail':
												echo '<li><a href="mailto:'.$item['href'].'" class="link">'.$item['link'].'</a></li>';
												break;

												default:
												echo '<li><a href="'.$item['href'].'" class="link_under" target="_blank">'.$item['link'].'</a></li>';
											}
										} ?>
									</ul>
								<?php } ?>
								<?php if(!empty($social)) { ?>
									<div class="clearfix"></div>
									<ul class="icomp__soc soc__list soc__list-bg list-hor">
										<?php foreach($social as $key=>$item) { ?>
											<li><a href="<?php echo $item['href']; ?>" target="_blank" class="soc__list-<?php echo $item['icon']; ?>"><svg class="ico ico-center"><use xlink:href="#soc-<?php echo $item['icon']; ?>" /></svg></a></li>
											<?php } ?>
										</ul>
									<?php } ?>
								</div>
								<div class="icomp__show">
									<button ><span class="link_under">показать все контакты</span></button>
								</div>
							<?php } ?>
						</div>
						<?php if($tags) { ?>
							<div class="icomp__branch">
								<ul class="clearfix">
									<?php foreach($tags as $item) { ?>
										<li><?php echo $item['title']; ?></li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>
					</div>

					<div class="icomp__content col-md-8 col-xl-9">

						<div class="icomp__tabs">
							<ul class="icomp__tabs_main list-hor">
								<?php if(!empty($tabs)) { ?>
									<?php foreach($tabs as $tab) { ?>
										<li><a href="#" class="icomp__change link <?php echo $tab['active']; ?>" data-type="<?php echo $tab['key']; ?>"><?php echo $tab['title']; ?></a></li>
									<?php } ?>
								<?php } ?>
							</ul>

							<div class="icomp__tabs_content">
								<?php if(!empty($tabs)) { ?>
									<?php foreach($tabs as $tab) { ?>
										<div class="icomp__pane icomp__pane-<?php echo $tab['key']; ?> tab-pane <?php echo $tab['active']; ?>">
											<?php if($tab['children']) { ?>
												<ul class="icomp__tabs_sec list-hor">
													<?php foreach($tab['children'] as $child) { ?>
														<li><a href="#" class="icomp__change <?php echo $child['active']; ?>" data-type="<?php echo $child['key']; ?>"><?php echo $child['title']; ?></a></li>
													<?php } ?>
												</ul>
											<?php } ?>
										</div>
									<?php } ?>
								<?php } ?>
							</div>


						</div>

						<div class="icomp__inner">
							<?php $active = true; ?>
							<?php if(!empty($tabs)) { ?>
								<?php foreach($tabs as $tab) { ?>
									<?php if($tab['key'] === 'info') {continue;} ?>
									<?php if($tab['active']) {$active = false;} ?>

									<div class="icomp__pane icomp__pane-<?php echo $tab['key']; ?> <?php echo $tab['active']; ?>" data-key="<?php echo $tab['key']; ?>">

										<?php if(!empty($tab['children'])) { ?>
											<?php foreach($tab['children'] as $child) { ?>
												<div class="icomp__pane icomp__pane-<?php echo $child['key']; ?> <?php echo $child['active']; ?>" data-key="<?php echo $child['key']; ?>">
													<?php echo $child['isactive'] ? $content : ''; ?>
												</div>
											<?php } ?>
										<?php }else{ ?>
											<?php echo $tab['isactive'] ? $content : ''; ?>
										<?php } ?>

									</div>

								<?php } ?>
							<?php } ?>


							<!-- INFO -->
							<?php if($info_status) { ?>
								<div class="icomp__pane icomp__pane-info <?php echo $active ? 'active' : ''; ?>">

									<?php if($description) { ?>
										<div class="icomp__text text">
											<?php echo nl2br($description); ?>
										</div>
									<?php } ?>


									<?php /*if($brands) { ?>
										<div class="icomp__text_cont">
											<div class="icomp__text_name">Бренды</div>
											<ul class="icomp__text_list row">
												<?php foreach($brands as $key=>$item) { ?>
													<li class="col-sm-6 col-lg-4 <?php echo $key > 9 ? 'icomp__text_h' : ''; ?>"><?php echo $item['title']; ?></li>
													<?php if($key == 9) { ?>
														<li class="icomp__text_list_btn col-12">
															<button class="showmore">
																<svg><use xlink:href="#points" /></svg><svg><use xlink:href="#arr-down" /></svg>
															</button>
														</li>
													<?php } ?>
												<?php } ?>
											</ul>
										</div>
									<?php }*/ ?>

									<?php if($branches) { ?>
										<div class="icomp__text_cont">
											<div class="icomp__text_name">Отрасли</div>
											<ul class="icomp__text_list row">
												<?php foreach($branches as $key=>$item) { ?>
													<li class="col-sm-6 col-lg-4 <?php echo $key > 9 ? 'icomp__text_h' : ''; ?>"><?php echo $item['title']; ?></li>
													<?php if($key == 9) { ?>
														<li class="icomp__text_list_btn col-12">
															<button class="showmore">
																<svg><use xlink:href="#points" /></svg><svg><use xlink:href="#arr-down" /></svg>
															</button>
														</li>
													<?php } ?>
												<?php } ?>
											</ul>
										</div>
									<?php } ?>


									<?php if($gallery) { ?>
										<div class="icomp__text_cont">
											<div class="icomp__text_name">Фотогалерея</div>
											<div class="icomp__text_slider">
												<?php foreach($gallery as $image) { ?>
													<a href="<?php echo $image['image']; ?>" data-fancybox="company"><img src="<?php echo $image['thumb']; ?>" alt=""></a>
												<?php } ?>
											</div>
											<div class="icomp__text_nav nav__cont2">
												<button type="button" class="nav__item nav__prev nav__slide"><span><svg><use xlink:href="#arr-left" /></svg></span></button>
												<div class="nav__dots2"></div>
												<button type="button" class="nav__item nav__next nav__slide"><span><svg><use xlink:href="#arr-right" /></svg></span></button>
											</div>
										</div>
									<?php } ?>

								</div>
							<?php } ?>
							<!-- INFO -->

						</div>




					</div>

				</div>

				<div class="icomp__bottom">

					<?php $active = true; ?>
					<?php if(!empty($tabs)) { ?>
						<?php foreach($tabs as $tab) { ?>
							<div class="icomp__pane <?php echo !in_array($tab['key'], array('master', 'expert', 'info')) ? 'icomp__pane_empty' : ''; ?> icomp__pane-<?php echo $tab['key']; ?> <?php echo $tab['active']; ?>">
								<?php if($tab['key'] === 'master') { ?>
									<div class="icomp__master imaster__center">
										<?php require(DIR_TEMPLATE . 'avclub/template/_include/master-add.tpl'); ?>
									</div>
								<?php } ?>

								<?php if($tab['key'] === 'expert' || $tab['key'] === 'info') { ?>
									<div class="icomp__new">
										<?php require(DIR_TEMPLATE . 'avclub/template/_include/company-add.tpl'); ?>
									</div>
								<?php } ?>

							</div>

							<?php $active = false; ?>
						<?php } ?>
					<?php } ?>

					

				</div>

			</div>

		</div>
	</section>

	<script src="catalog/view/theme/avclub/js/company-info.js"></script>

	<?php if($gallery) { ?>
		<script>
			$(function(){
				var icompSlider = $('.icomp__text_slider').slick({
					infinite: false,
					slidesToShow: 4,
					slidesToScroll: 1,
					dots: true,
					appendDots: $('.icomp__text_nav .nav__dots2'),
					prevArrow: '.icomp__text_nav .nav__prev',
					nextArrow: '.icomp__text_nav .nav__next',
					responsive: [				
					{
						breakpoint: 1600,
						settings: {
							slidesToShow: 3
						},
					},
					{
						breakpoint: 992,
						settings: {
							slidesToShow: 3
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
							slidesToShow: 1
						},
					}
					]
				})
			})
		</script>
	<?php } ?>
	<?php echo $content_bottom; ?>
	<?php echo $footer; ?>