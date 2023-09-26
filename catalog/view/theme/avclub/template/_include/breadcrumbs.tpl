<div class="bread2__cont">
	<ul itemscope itemtype="https://schema.org/BreadcrumbList">
		<?php $m = 0;foreach ($breadcrumbs as $breadcrumb) {$m++; ?>

			<?php if($m == count($breadcrumbs)){ ?>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<link itemprop="item" href="<?php echo $breadcrumb['href']; ?>">
					<span itemprop="name">
						<?php echo $breadcrumb['text']; ?>
					</span>
					<meta itemprop="position" content="<?php echo $m; ?>" />
				</li>
			<?php }else{ ?>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<a itemprop="item" href="<?php echo $breadcrumb['href']; ?>" class="link"><span itemprop="name"><?php echo $breadcrumb['text']; ?></span></a>
					<meta itemprop="position" content="<?php echo $m; ?>" />
				</li>
			<?php } ?>

		<?php } ?>
	</ul>
</div>
