<section class="section_evideo">
  <div class="container">
    <?php if($title) { ?>
      <div class="title etitle">
        <h2><?php echo $title; ?></h2>
      </div>
    <?php } ?>
    <a href="<?php echo $you; ?>" class="evideo__video" data-fancybox>
      <img src="<?php echo $image; ?>" alt="">
      <span class="eplay">
        <svg class="ico ico-center"><use xlink:href="#play" /></svg>
      </span>
    </a>
  </div>
</section>