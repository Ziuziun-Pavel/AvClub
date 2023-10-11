<?php if(!empty($event_list)) { ?>
<?php foreach($event_list as $event) { ?>
<div class="expreg d-none">
    <div class="expreg__info">
        <div class="expreg__top">
            <div class="expreg__logo">
                <img src="catalog/view/theme/avclub/images/logo-expert-event.svg" alt="">
            </div>
            <div class="expreg__type <?php echo $event['old'] ? '' : 'active'; ?>"><?php echo $event['type_text']; ?></div>
        </div>
        <div class="expreg__name"><?php echo $event['name']; ?></div>
        <div class="expreg__date">
            <span><?php echo $event['date']; ?></span>
        </div>

        <?php /* FORUM */ ?>
        <?php if($event['type_event'] === 'forum') { ?>
        <div class="expreg__address"><?php echo implode(', ', $event['addresses']) ?></div>
        <?php if(!$event['old'] && !empty($event['link'])) { ?>
        <div class="expreg__btns">
            <a href="<?php echo $event['link']; ?>" class="btn btn-red" target="_blank">Зарегистрироваться</a>
            <div class="expreg__qr"></div>
        </div>
        <!--<div class="expreg__bottom">Для посещения мероприятия предъявите распечатанный билет (бейдж)</div>-->
        <?php } ?>
        <?php } ?>
        <?php /* # FORUM */ ?>

        <?php /* WEBINAR */ ?>
        <?php if($event['type_event'] === 'webinar') { ?>
        <div class="expreg__btns">
            <a href="<?php echo $event['url']; ?>" class="btn btn-red" target="_blank">Ссылка на вебинар</a>
            <div class="expreg__qr"></div>
        </div>
        <?php } ?>
        <?php /* # WEBINAR */ ?>

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

    buttons.forEach((button) => {
        let btn = button.querySelector('.btn');
        let qr = button.querySelector('.expreg__qr');

        let qrcode = new QRCode(qr, {
            text: btn.href,
            width: 50,
            height: 50,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    })
</script>
<?php } ?>