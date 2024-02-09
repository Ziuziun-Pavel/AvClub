<style>
    .invoice--link {
        word-wrap: break-word;
    }

    .company--block {
        font-size: .875rem;
        line-height: 1.1;
        position: relative;
        display: -ms-inline-flexbox;
        display: inline-flex;
        -ms-flex-align: center;
        align-items: center;
        color: #0561c8;
    }
</style>
<?php if(!empty($catalog_list)) { ?>
<?php foreach($catalog_list as $catalog_item) { ?>
<div class="expreg d-none">
    <div class="expreg__info">
        <div class="expreg__top">
            <div class="company--block"><?php echo $catalog_item['company']; ?></div>
        </div>
        <div class="expreg__name"><?php echo $catalog_item['title']; ?></div>
        <div class="expreg__date">
            <span><?php echo $catalog_item['date']; ?></span>
        </div>

            <a class="invoice--link" href="<?php echo $catalog_item['invoice']; ?>" ><?php echo $catalog_item['invoice']; ?></a>

    </div>
    <div class="expreg__path">
        <div class="expreg__capt">Статус регистрации</div>
        <div class="expreg__status">
            <?php foreach($catalog_item['statuses'] as $key=>$status) { ?>
            <div class="expreg__status--item <?php echo $status['preactive'] ? '--preactive' : ''; ?> <?php echo $status['active'] ? '' : '--passive'; ?>">
                <span></span> <?php echo $status['text']; ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php }?>
<?php } else { ?>
<div class="expreg d-none">
    <div class="expreg__info">
        <div class="imaster__text">
            На данный момент регистраций не найдено.
            Перейдите на вкладку "Ближайшие" и зарегистрируйтесь на новые мероприятия.
        </div>

    </div>
</div>
<script>

</script>
<?php } ?>