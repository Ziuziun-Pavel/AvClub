<?php echo $header; ?>
<?php echo $content_top; ?>
<section class="section_content section_article">
  <div class="container">

    <?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

    <div class="content__title">
      <h1><?php echo $heading_title; ?></h1>
    </div>
    <div class="content__cont text__cont">

      <div class="content__row row">

        <div class="content__text col-md-8 col-lg-8 col-xl-9">


          <div class="text">
            <?php echo $description; ?>
          </div>

        </div>
        
        <?php echo $column_right; ?>

      </div>


    </div>

    <?php echo $column_left; ?>

  </div>
</section>
<?php echo $content_bottom; ?>
<?php echo $footer; ?>