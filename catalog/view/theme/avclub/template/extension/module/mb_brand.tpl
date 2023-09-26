<section class="section_brand">
  <div class="container">
   <?php if($title) { ?>
    <div class="title etitle">
      <h2><?php echo $title; ?></h2>
    </div>
  <?php } ?>
  <div class="brand__row row">
    <?php foreach($companies as $brand) { ?>
      <div class="brand__outer col-6 col-sm-3">
        <a href="<?php echo $brand['href']; ?>">
          <img src="<?php echo $brand['image']; ?>" alt="<?php echo $brand['title']; ?>">
        </a>
        <?php /* ?>
        <img src="<?php echo $brand['image']; ?>" alt="<?php echo $brand['title']; ?>">
        <?php */ ?>
      </div>
    <?php } ?>

  </div>
</div>
</section>