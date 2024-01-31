<section class="linemore">
    <div class="container">
        <?php if($heading_title) { ?>
        <!--<div class="linemore--title"><?php echo $heading_title; ?></div>-->
        <div class="linemore--title">Похожие материалы </div>
        <?php } ?>

        <div class="linemore--cont search__row row">

            <?php $journal_date = true; ?>
            <?php foreach($journals as $journal) { ?>
            <div class="news__outer col-sm-6 col-lg-4 col-xl-3">
                <?php if($journal['type'] === 'opinion') { ?>
                <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-opinion.tpl'); ?>
                <?php }else{ ?>
                <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-article.tpl'); ?>
                <?php } ?>
            </div>
            <?php } ?>

        </div>
    </div>
</section>