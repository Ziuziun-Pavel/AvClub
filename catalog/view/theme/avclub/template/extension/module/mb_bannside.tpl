<div class="abanner__cont">
	<a href="<?php echo $href ? $href : 'javascript:void(0)'; ?>" class="banner__vert <?php echo !empty($banner_id) ? 'banner_click' : ''; ?>" <?php echo $target ? 'target="_blank"' : ''; ?> data-id="<?php echo !empty($banner_id) ? $banner_id : 0; ?>">
		<img src="<?php echo $image_pc; ?>" alt="">
	</a>
</div>