<?php if(!empty($banner)) { ?>
<a href="<?php echo $banner['link'] ? $banner['link'] : 'javascript:void(0)'; ?>" class="banner__vert banner__vert-content banner_click" <?php echo $banner['target'] ? 'target="_blank"' : ''; ?> data-id="<?php echo !empty($banner['banner_id']) ? $banner['banner_id'] : 0; ?>">
	<img src="<?php echo $banner['image']; ?>" alt="">
</a>
<?php } ?>