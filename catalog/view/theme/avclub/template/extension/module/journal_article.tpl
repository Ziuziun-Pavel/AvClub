<section class="section_news-mod">
  <div class="container">

    <div class="mod__head">
      <div class="mod__title title">
        <h2><?php echo $heading_title; ?></h2>
      </div>
      <div class="mod__more">
        <a href="<?php echo $all_article; ?>" class="link_under">ВСЕ СТАТЬИ</a>
      </div>
    </div>
    <div class="mod__cont row">

      <?php $m = 0; ?>
      <?php foreach($journals as $journal) {$m++; ?>

        <?php 
        $journal_class = '';
        $journal_outer = 'col-sm-6 col-lg-4 col-xl-3';
        if($m == 3) { 
          $journal_outer = 'col-sm-6 col-lg-4 d-lg-none d-xl-block col-xl-3';
        } 
        ?>
        <div class="news__mouter <?php echo $journal_outer; ?>">
          <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-article.tpl'); ?>
        </div>
      <?php } ?>


      <div class="mod__footer col-12">
        <div class="mod__more">
          <a href="<?php echo $all_article; ?>" class="link_under">ВСЕ СТАТЬИ</a>
        </div>
      </div>

      <?php if($telegram['status']) { ?>
        <div class="news__mouter col-sm-6 col-lg-4 col-xl-3">
          <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-telegram.tpl'); ?>
        </div>
      <?php } ?>

    </div>

  </div>
</section>
