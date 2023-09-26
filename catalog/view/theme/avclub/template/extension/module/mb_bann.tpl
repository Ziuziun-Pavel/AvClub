 <section class="section_banner">
  <div class="container">
    <a href="<?php echo $href ? $href : 'javascript:void(0)'; ?>" class="bann__outer <?php echo !empty($banner_id) ? 'banner_click' : ''; ?>" <?php echo $target ? 'target="_blank"' : ''; ?>  data-id="<?php echo !empty($banner_id) ? $banner_id : 0; ?>">
      <?php if($image_pc) { ?>
        <span class="bann__pc d-none d-md-inline">
          <img src="<?php echo $image_pc; ?>" alt="">
        </span>
      <?php } ?>
      <?php if($image_mob) { ?>
        <span class="bann__mob d-md-none">
          <img src="<?php echo $image_mob; ?>" alt="">
        </span>
      <?php } ?>
    </a>
  </div>
</section>