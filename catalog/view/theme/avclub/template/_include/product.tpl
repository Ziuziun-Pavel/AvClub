<div class="pr__item">
	<a href="<?php echo $product['href']; ?>" class="pr__img">
		<img src="<?php echo $product['thumb']; ?>" alt="">
		<span class="pr__images">
			<span><img src="<?php echo $product['thumb']; ?>" alt=""></span>
			<?php if(!empty($product['images'])) { ?>
			<?php foreach($product['images'] as $image) { ?>
			<span><img src="<?php echo $image; ?>" alt=""></span>
		<?php } ?>
		<?php } ?>
		</span>

	</a>
	<div class="pr__data">
		<div class="pr__name">
			<a href="<?php echo $product['href']; ?>" class="link"><?php echo $product['name']; ?></a>
		</div>
		<div class="pr__price price">
			<div class="price__val"><?php echo $product['price']; ?></div>
			<?php /* ?>
			<div class="price__curr">≈ 12 700 $</div>
			<?php */ ?>
		</div>
		<div class="pr__attrs">
			<?php if(!empty($product['options'])) { ?>
				<?php foreach($product['options'] as $option) { ?>
					<div class="pr__attr">
						<div class="pr__icon"><svg><use xlink:href="#auto-<?php echo $option['icon']; ?>" /></svg></div>
							<div class="pr__val">
								<?php if($option['type'] === 'text') { echo $option['value']; } ?>
								<?php if($option['type'] === 'select' && !empty($option['product_option_value'][0])) { echo $option['product_option_value'][0]['name']; } ?>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
			<div class="pr__buttons">
				<div class="pr__btn">
					<a href="<?php echo $product['href']; ?>" class="btn btn-blue">
						<span>Подробнее</span>
					</a>
				</div>
				<div class="pr__btn">
					<a href="#" class="btn btn-trans pr__bid" data-id="<?php echo $product['product_id']; ?>" data-name="<?php echo $product['name']; ?>">
						<span>Оставить заявку</span>
					</a>
				</div>
			</div>
		</div>
	</div>