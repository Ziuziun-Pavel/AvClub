<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<section class="section_fixed fixed__item">
	<div class="container">
		<div class="row">
			<div class="fixed__logo col-4 col-md-2 col-xl-3">
				<?php $logo_img = $theme_dir . '/images/logo.svg'; ?>
				<?php if ($home == $og_url) { ?>
					<span>
						<img src="<?php echo $logo_img; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" />
					</span>
				<?php } else { ?>
					<a href="<?php echo $home; ?>"><img src="<?php echo $logo_img; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
				<?php } ?>
			</div>
			<div class="fixed__menu col-4 col-md-8 col-xl-7">
				<?php foreach($menu as $key_parent=>$item) { ?>
					<?php if(!$item['active']){continue;} ?>
					<ul class="hbot__menu list-hor fs-menu-sub">
						<?php foreach($item['children'] as $key=>$child) { ?>
							<?php if($key === 'tag') { ?>
								<li><a href="#modal_tag" class="link hbot__tag modalshow"><svg class="ico"><use xlink:href="#hash" /></svg> <?php echo $child['title']; ?></a></li>
							<?php }else{ ?>
								<li><a href="<?php echo $child['href']; ?>" class="link <?php echo $child['active'] ? 'active' : ''; ?>"><?php echo $child['title']; ?></a></li>
							<?php } ?>
						<?php } ?>
						<li>
							<a href="#modal_search" class="hbot__search search__show">
								<svg class="ico"><use xlink:href="#search" /></svg>
							</a>
						</li>
					</ul>
					<?php break; ?>
				<?php } ?>
			</div>
			<div class="fixed__links col-4 col-md-2">
				<ul class="hlinks list-hor">
					<li class="hlinks__wish">
						<a href="<?php echo $wishlist; ?>" class="<?php echo $wishlist_active ? 'active' : ''; ?>">
							<svg class="ico ico-center"><use xlink:href="#wish" /></svg>
						</a>
					</li>
					<li class="hlinks__search">
						<a href="#modal_search" class="search__show">
							<svg class="ico ico-center"><use xlink:href="#search" /></svg>
						</a>
					</li>
					<li class="hlinks__login">
						<a href="<?php echo $logged ? $account : $login; ?>" >
							<svg class="ico ico-center"><use xlink:href="#<?php echo $logged ? 'cabinet' : 'login'; ?>" /></svg>
						</a>
					</li>
					<li class="hlinks__menu">
						<a href="#" class="mmenu__btn">
							<span class="mmenu__btn_in">
								<span></span>
								<span></span>
								<span></span>
							</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</section>