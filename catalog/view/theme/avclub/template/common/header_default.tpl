<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<header class="header fixed__header">	
	<div class="container">
		<div class="row">	
			<div class="logo__outer col-4 col-md-2 col-xl-4">
				<?php $logo_img = $theme_dir . '/images/logo.svg'; ?>
				<?php if ($home == $og_url) { ?>
					<span class="logo">
						<img src="<?php echo $logo_img; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" />
					</span>
				<?php } else { ?>
					<a href="<?php echo $home; ?>" class="logo"><img src="<?php echo $logo_img; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
				<?php } ?>
			</div>
			<div class="head__cont col-8 col-md-10 col-xl-8">

				<div class="htop__cont">
					<ul class="fs-menu htop__menu list-hor">
						<?php foreach($menu as $key=>$item) { ?>
							<li><a href="<?php echo $item['href']; ?>" class="link <?php echo $item['active'] ? 'active' : ''; ?> <?php echo $key === 'expert' ? 'htop__menu-company' : ''; ?>"><?php echo $item['title']; ?></a></li>
						<?php } ?>
						<li>
							<a href="#modal_search" class="htop__search search__show">
								<svg class="ico"><use xlink:href="#search" /></svg>
							</a>
						</li>
					</ul>

					<div class="htop__right">
						<?php if(!empty($adv['href'])) { ?>
							<div class="fs-menu htop__ad">
								<a href="<?php echo $adv['href']; ?>" class="link" target="_blank"><?php echo $adv['title'] ? $adv['title'] : 'Рекламодателям';  ?></a>
							</div>
						<?php } ?>
						
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

				<div class="hbot__cont">
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
						</ul>
						<?php break; ?>
					<?php } ?>

					<ul class="soc__list list-hor">
						<?php foreach($themeset_soc as $key=>$link) { ?>
							<?php if(!$link){continue;} ?>
							<li><a href="<?php echo $link; ?>" target="_blank" class="soc__list-<?php echo $key; ?>"><svg><use xlink:href="#soc-<?php echo $key; ?>" /></svg></a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</header>