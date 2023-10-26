<p>(Раздел в разработке)</p>
<?php if(!empty($event_list)) { ?>
<?php foreach($event_list as $event) { ?>
<div class="expreg expreg-fut-ev d-none">
    <div class="expreg__info expreg__info-fut-ev">
        <div class="expreg__top expreg__top-fut-ev">
            <div class="expreg__logo">
                <img src="catalog/view/theme/avclub/images/logo-expert-event.svg" alt="">
            </div>
            <div class="expreg__type <?php echo $event['old'] ? '' : 'active'; ?>"><?php echo $event['type_text']; ?></div>
        </div>
        <div class="expreg__name expreg__name-fut-ev"><?php echo $event['name']; ?></div>
        <div class="expreg__date expreg__date-fut-ev">
            <span><?php echo $event['date']; ?></span>
        </div>
        <div class="expreg__date expreg__date-fut-ev price">
            <?php if ($event['sum']) { ?>
                <span><?php echo $event['sum']; ?> ₽</span>
            <?php } else { ?>
                <span>Бесплатно</span>
            <?php } ?>
        </div>

        <?php /* FORUM */ ?>
        <?php if($event['type_event'] === 'forum') { ?>
        <div class="expreg__address"><?php echo implode(', ', $event['addresses']) ?></div>
        <div class="expreg__btns expreg__btns-fut-ev" style="font-size: 13px;">
            <a class="btn btn-red" style="width: 48%; min-width: 147px;" target="_blank">Принять приглашение</a>
            <a href="<?php echo $event['landing_url']; ?>" class="btn btn-red" style="width: 48%;min-width: 147px;" target="_blank">Подробнее</a>

            <!--<div class="expreg__qr"></div>-->
        </div>
        <!--<div class="expreg__bottom">Для посещения мероприятия предъявите распечатанный билет (бейдж)</div>-->
        <?php } ?>
        <?php /* # FORUM */ ?>

        <?php /* WEBINAR */ ?>
        <?php if($event['type_event'] === 'webinar') { ?>
        <div class="expreg__btns expreg__btns-fut-ev" style="font-size: 13px;">
            <a class="btn btn-red" style="width: 48%;min-width: 147px;" target="_blank">Принять приглашение</a>
            <a href="<?php echo $event['landing_url']; ?>" class="btn btn-red" style="width: 48%;min-width: 147px;" target="_blank">Подробнее</a>
            <!--<div class="expreg__qr"></div>-->
        </div>
        <?php } ?>
        <?php /* # WEBINAR */ ?>

    </div>
    <div class="expreg__path">
        <div class="expreg__capt">Статус регистрации</div>
        <div class="expreg__status">
            <?php foreach($event['statuses'] as $key=>$status) { ?>
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
            На данный момент никаких мероприятий не планируется.
            Попробуйте зайти немного позже.
        </div>

    </div>
</div>
<script>
    let buttons = document.querySelectorAll(".expreg__btns");

    // buttons.forEach((button) => {
    //     let btn = button.querySelector('.btn');
    //     let qr = button.querySelector('.expreg__qr');
    //
    //     let qrcode = new QRCode(qr, {
    //         text: btn.href,
    //         width: 50,
    //         height: 50,
    //         colorDark: "#000000",
    //         colorLight: "#ffffff",
    //         correctLevel: QRCode.CorrectLevel.H
    //     });
    // })
</script>
<?php } ?>