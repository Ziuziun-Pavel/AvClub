<div class="bread__cont">
	<ul>
		<?php $count = count($breadcrumbs); $m = 0;foreach ($breadcrumbs as $breadcrumb) {$m++; ?>
			<?php if($m == $count - 1) { ?>
			<li>
				<a href="<?php echo $breadcrumb['href']; ?>" class="link__outer"><span class="link"><?php echo $breadcrumb['text']; ?></span> <svg class="ico"><use xlink:href="#arrow-left" /></svg></a>
			</li>
		<?php } ?>
	<?php	} ?>
</ul>
</div>
