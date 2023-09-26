<section class="section_top">
  <div class="container">
    <div class="row">

      <div class="top__main col-12 col-lg-6 col-xl-6">
        <?php if($main) { ?>
          <a href="<?php echo $main['href']; ?>" class="news__item news__big news__home link__outer" <?php echo !empty($main['target']) && $main['target'] ? 'target="_blank"' : ''; ?>>
            <span class="news__img">
              <span class="news__image"><img src="<?php echo $main['thumb']; ?>" alt=""></span>
            </span>
            <span class="news__name">
              <span class="link"><?php echo $main['title']; ?></span>
            </span>
          </a>
        <?php } ?>

      </div>
      <div class="top__news col-sm-6 col-lg-3 col-xl-3">
        <?php if(count($journals)) { ?>

          <div class="nlist__cont">
            <ul class="nlist__list list-vert">
              <?php foreach($journals as $key=>$journal) { ?>
                <li>
                  <a href="<?php echo $journal['href']; ?>" class="nlist__item wish__outer link__outer">
                    <span class="nlist__title link"><?php echo $journal['title']; ?></span>
                    <span class="nlist__date date"><?php echo $journal['date']; ?></span>
                    <button class="wish wish-<?php echo $journal['journal_id']; ?> <?php echo $journal['wish'] ? 'active' : ''; ?>" type="button" data-id="<?php echo $journal['journal_id']; ?>"><svg class="ico"><use xlink:href="#wish" /></svg></button>
                  </a>
                </li>
              <?php } ?>
            </ul>
            <div class="nlist__more">
              <a href="<?php echo $all_news; ?>" class="link_under">ВСЕ НОВОСТИ</a>
            </div>
          </div>
        <?php } ?>
      </div>

      <?php if(!empty($banner)) { ?>
        <div class="top__banner col-sm-6 col-lg-3 col-xl-3">
          <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-banner.tpl'); ?>
        </div>
      <?php } ?>

    </div>
  </div>
</section>