<section class="section_opinion-mod">
  <div class="container">

    <div class="mod__head">
      <div class="mod__title title">
        <h2><?php echo $heading_title; ?></h2>
      </div>
      <div class="mod__more">
        <a href="<?php echo $all_opinion; ?>" class="link_under">ВСЕ МНЕНИЯ</a>
      </div>
    </div>
    <div class="mod__cont row">

      <?php foreach($journals as $journal) { ?>
        <div class="opinion__modcol col-sm-6 col-lg-3">
          <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-opinion.tpl'); ?>
        </div>
      <?php } ?>

      <div class="mod__footer col-12">
        <div class="mod__more">
          <a href="<?php echo $all_opinion; ?>" class="link_under">ВСЕ МНЕНИЯ</a>
        </div>
      </div>

      <div class="opinion__modcol col-sm-6 col-lg-3">
        <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-master.tpl'); ?>
      </div>


    </div>

  </div>
</section>