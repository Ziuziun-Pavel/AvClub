<section class="section__case-mod">
  <div class="container">

    <div class="mod__head">
      <div class="mod__title title">
       <h2><?php echo $heading_title; ?></h2>
     </div>
     <div class="mod__more">
      <a href="<?php echo $all_case; ?>" class="link_under">ВСЕ КЕЙСЫ</a>
    </div>
    <div class="mod__btn">
      <a href="#modal-case" class="btn btn-plus modalshow">
        <span>Разместить кейс</span>
        <svg class="ico"><use xlink:href="#plus" /></svg>
      </a>
    </div>
  </div>
  <div class="mod__cont row">

    <?php $m = 0; ?>
    <?php foreach($journals as $journal) {$m++; ?>
      <div class="case__mouter col-sm-6 col-xl-6">
        <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-case.tpl'); ?>
      </div>
    <?php } ?>


  </div>
  <div class="mod__footer d-sm-none">
    <div class="mod__more">
      <a href="<?php echo $all_case; ?>" class="link_under">ВСЕ КЕЙСЫ</a>
    </div>
  </div>

</div>
</section>