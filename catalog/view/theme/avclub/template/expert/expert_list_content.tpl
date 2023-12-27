<?php if(!empty($experts)) { ?>

<?php foreach($experts as $expert) { ?>
    <div class="explist__col">
        <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-expert.tpl'); ?>
    </div>

<?php } ?>

<div class="explist__col explist__col-add">
    <?php require(DIR_TEMPLATE . 'avclub/template/_include/company-add.tpl'); ?>
</div>

<?php if($pagination) { ?>
<div class="page__row">
    <?php echo $pagination; ?>
</div>
<?php } ?>

<?php }else{ ?>
<div class="master__outer master__empty text col-12">
    <h4>Никого не нашли</h4>
    <p>Попробуйте изменить параметры фильтра</p>
    <div class="master__goto">
        <a href="#" class="link_under cofilter__clear_all">Очистить все</a>
    </div>
</div>

<?php } ?>