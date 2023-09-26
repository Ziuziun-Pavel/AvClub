<section class="section_news-mod section_news-video">
  <div class="container">

    <div class="mod__head">
      <div class="mod__title title">
        <h2><?php echo $heading_title; ?></h2>
      </div>
      <div class="mod__more">
        <a href="<?php echo $all_video; ?>" class="link_under">ВСЕ ВИДЕО</a>
      </div>
    </div>
    <div class="mod__cont row">

      <?php $m = 0; ?>
      <?php foreach($journals as $journal) {$m++; ?>

        <?php 
        $journal_class = '';
        $journal_outer = 'col-lg-8 col-xl-6';
        if($m == 1) { 
          $journal_class = 'news__big';
        } else if($m == 2) {
          $journal_outer = 'col-sm-6 col-lg-4 col-xl-3';
        } else if($m == 3) {
          $journal_outer = 'col-sm-6 d-lg-none d-xl-block col-xl-3';
        }
        ?>

        <div class="news__mouter <?php echo $journal_outer; ?>">
          <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-article.tpl'); ?>
        </div>

      <?php } ?>

    </div>
    <div class="mod__footer">
      <div class="mod__more">
        <a href="<?php echo $all_video; ?>" class="link_under">ВСЕ ВИДЕО-МАТЕРИАЛЫ</a>
      </div>
    </div>

  </div>
</section>