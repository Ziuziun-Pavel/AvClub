<?php if(!empty($case)) { ?>
<?php if($case['template']) { ?>
<div class="icase__cont <?php echo !empty($journal_case_class) ? $journal_case_class : ''; ?>">
    <?php if($case['logo']) { ?>
    <div class="icase__logo">
        <img src="<?php echo $case['logo']; ?>" alt="">
    </div>
    <?php } ?>
    <div class="icase__data">
        <?php if($case['company_id']) {  ?>
            <span class="comp__img">
                <img src="<?= $company['image'] ?>" alt="">
            </span>
            <div class="icase__name">Номинант: <?php echo $company['title']; ?></div>
            <?php if($company['description']) { ?>
            <div class="icase__text">
                <?php echo html_entity_decode($company['description']); ?>
            </div>
            <?php } ?>
            <div class="icase__btn">
                <a href="<?= $company['href'] ?>" class="btn btn-red">
                    <span>Связаться</span>
                </a>
            </div>
        <?php } else { ?>
            <div class="icase__name">Интегратор: <?php echo $case['title']; ?></div>
            <?php if($case['description']) { ?>
            <div class="icase__text">
                <?php echo html_entity_decode($case['description']); ?>
            </div>
            <?php } ?>
            <div class="icase__btn">
                <a href="#" class="btn btn-red">
                    <span>Связаться</span>
                </a>
            </div>
        <?php }  ?>

        <div class="icase__del"></div>
        <?php if(!empty($case['attr'])) { ?>
        <ul class="icase__attr row">
            <?php foreach($case['attr'] as $attr) { ?>
            <li class="col-12"><?php echo $attr['title']; ?>: <strong><?php echo $attr['text']; ?></strong></li>
            <?php } ?>
        </ul>
        <?php } ?>
    </div>
</div>
<?php }else{ ?>
<div class="icase__cont icase__cont-short">
    <div class="icase__data">
        <ul class="icase__attr row">
            <?php if($case['company_id']) {  ?>
                <li class="col-12 col-lg-6">Интегратор: <strong><?php echo $company['title']; ?></strong></li>
            <?php } else { ?>
                <li class="col-12 col-lg-6">Интегратор: <strong><?php echo $case['title']; ?></strong></li>
            <?php }  ?>

            <?php if(!empty($case['attr'])) { ?>
            <?php foreach($case['attr'] as $attr) { ?>
            <li class="col-12 col-lg-6"><?php echo $attr['title']; ?>: <strong><?php echo $attr['text']; ?></strong></li>
            <?php } ?>
            <?php } ?>
        </ul>
    </div>
</div>
<?php } ?>
<?php } ?>