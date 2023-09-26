<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<section class="section_afix fixed__item">
	<div class="fixed__progress"></div>
	<div class="container">
		<div class="row">
			<div class="afix__logo col-4 col-md-2 col-xl-3">
				<?php $logo_img = $theme_dir . '/images/logo.svg'; ?>
				<?php if ($home == $og_url) { ?>
					<span>
						<img src="<?php echo $logo_img; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" />
					</span>
				<?php } else { ?>
					<a href="<?php echo $home; ?>"><img src="<?php echo $logo_img; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
				<?php } ?>
			</div>
			<div class="afix__title">
				<?php echo $heading_title; ?>
			</div>
			<div class="afix__right">
				<div class="afix__wish hlinks__wish">
					<a href="<?php echo $wishlist; ?>" class="wish <?php echo $wishlist_active ? 'active' : ''; ?>" data-id="<?php echo $journal_id;  ?>">
						<svg class="ico"><use xlink:href="#wish" /></svg>
					</a>
				</div>
				<div class="afix__share_">
					<script src="https://yastatic.net/share2/share.js"></script>
					<div class="ya-share2" data-curtain data-shape="round" data-limit="0" data-more-button-type="short" data-services="vkontakte,facebook,odnoklassniki,telegram,twitter,viber,whatsapp" data-direction="vertical"></div>

				</div>
			</div>
		</div>
	</div>
</section>