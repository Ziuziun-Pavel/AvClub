<section class="section_banner <?php echo $template === 'pc' ? 'd-none d-md-block' : 'd-md-none'; ?>">
	<div class="container">
		<a href="<?php echo $href ? $href : 'javascript:void(0)'; ?>" class="bann__outer <?php echo !empty($banner_id) ? 'banner_click' : ''; ?>" <?php echo $target ? 'target="_blank"' : ''; ?>  data-id="<?php echo !empty($banner_id) ? $banner_id : 0; ?>">
			<span class="<?php echo $template === 'pc' ? 'bann__pc' : 'bann__mob'; ?>">
				<img src="<?php echo $image; ?>" alt="">
			</span>
		</a>
	</div>
</section>