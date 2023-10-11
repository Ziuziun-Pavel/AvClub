<?php echo $header; ?>
<?php echo $content_top; ?>

<section class="section_expert">
    <div class="container">

        <?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

        <div class="row expert__row">

            <div class="expert__info col-md-4">

                <div class="expertinfo">
                    <?php if($image) { ?>
                    <div class="expertinfo__img">
                        <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>"/>
                    </div>
                    <?php } ?>
                    <div class="expertinfo__name">
                        <?php echo $name; ?>
                    </div>
                    <div class="expertinfo__exp">
                        <?php echo $exp; ?>
                    </div>
                    <?php if($edit_info) { ?>
                    <div class="expertinfo__btn">
                        <a href="<?php echo $edit_info; ?>" class="btn btn-red">
                            <span>Изменить профиль</span>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if($edit_info && $alternate_count) { ?>
                    <div class="expertinfo__attention">
                        <svg class="icow">
                            <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#attention">
                        </svg>
                        Ваши данные <br>
                        находятся на модерации
                    </div>
                    <?php } ?>
                    <?php if($is_email && !$edit_info) { ?>
                    <div class="expertinfo__btn">
                        <a href="#modal_write_expert" class="btn btn-red modalshow">
                            <span>Написать эксперту</span>
                        </a>
                    </div>
                    <?php } ?>
                </div>

                <?php if($company) { ?>
                <div class="expertcom">
                    <div class="expertcom__capt">
                        Работает в компании
                    </div>
                    <div class="expertcom__img">
                        <a href="<?php echo $company['href']; ?>">
                            <img src="<?php echo $company['image']; ?>" alt="<?php echo $company['title']; ?>"/>
                        </a>
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($tags)) { ?>
                <div class="experttag">
                    <div class="experttag__capt">Экспертность</div>
                    <div class="experttag__list">
                        <ul class="clearfix list-hor">
                            <?php foreach($tags as $tag) { ?>
                            <li><?php echo $tag['title']; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($branches)) { ?>
                <div class="experttag">
                    <div class="experttag__capt">Отрасли</div>
                    <div class="experttag__list">
                        <ul class="clearfix list-hor">
                            <?php foreach($branches as $tag) { ?>
                            <li><?php echo $tag['title']; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>

            </div>
            <div class="expert__data col-md-8">

                <div class="expertnav" data-id="<?php echo $expert_id; ?>">

                    <?php if(!empty($logged)) { ?>
                    <?php $active_tab = false; ?>
                    <div class="expertnav__tabs">


                        <?php if($tabs) { ?>
                        <a href="#" class="expertnav__tab link <?php echo !$active_tab ? 'active' : ''; ?>"
                           data-type="article">Мои выступления в АВ Клубе</a>
                        <?php $active_tab = true; ?>
                        <?php } ?>

                        <?php if(!$tabs) { ?>
                        <a href="#" class="expertnav__tab link <?php echo !$active_tab ? 'active' : ''; ?>"
                           data-type="bio">Биография</a>
                        <?php $active_tab = true; ?>
                        <?php } ?>

                        <?php if(!empty($event_list)) { ?>
                        <a href="#" class="expertnav__tab reg link <?php echo !$active_tab ? 'active' : ''; ?>"
                           data-type="register">Прошедшие мероприятия</a>
                        <?php $active_tab = true; ?>
                        <?php } ?>

                        <?php if(!$tabs) { ?>
                        <a href="#" class="expertnav__tab fut_ev link <?php echo !$active_tab ? 'active' : ''; ?>"
                           data-type="future_events">Ближайшие мероприятия</a>
                        <?php $active_tab = true; ?>
                        <?php } ?>

                    </div>
                    <?php }else if(!empty($tabs)){ ?>
                    <div class="expertnav__capt">
                        Выступления эксперта в АВ Клубе
                    </div>
                    <?php }else{ ?>
                    <div class="expertnav__capt">
                        Нет выступлений эксперта
                    </div>
                    <?php } ?>

                    <?php $active_tab = false; ?>
                    <?php if($tabs) { ?>
                    <div id="navlist-article"
                         class="expertnav__list <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                        <ul class="list-hor">
                            <?php foreach($tabs as $key=>$tab) { ?>
                            <li><a href="#" class="expertnav__change <?php echo $tab['active']; ?>"
                                   data-type="<?php echo $tab['key']; ?>"><?php echo $tab['title']; ?></a></li>
                            <?php } ?>
                        </ul>
                        <div class="expertnav__sub">
                            <?php foreach($tabs as $key=>$tab) { ?>
                            <div class="expertnav__sub-pane expertnav__sub-<?php echo $tab['key']; ?> <?php echo $tab['active']; ?>">
                                <?php if(!empty($tab['children'])) { ?>
                                <ul class="list-hor">
                                    <?php foreach($tab['children'] as $key_child=>$child) { ?>
                                    <li><a href="#" class="expertnav__change <?php echo $child['active']; ?>"
                                           data-type="<?php echo $child['key']; ?>"><?php echo $child['title']; ?></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php $active_tab = true; ?>
                    <?php } ?>

                    <?php if(!$tabs && !empty($bio)) { ?>
                    <div id="navlist-bio" class="expertnav__list <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                        <ul class="list-hor">
                            <li><a href="#" class="expertnav__change disable" data-type="">&nbsp;</a></li>
                        </ul>
                    </div>
                    <?php $active_tab = true; ?>
                    <?php } ?>

                    <?php if(!empty($event_list)) { ?>
                    <div id="navlist-register"
                         class="expertnav__list <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                        <ul class="list-hor">
                            <li><a href="#" class="expertnav__change disable" data-type="">&nbsp;</a></li>
                        </ul>
                    </div>
                    <?php $active_tab = true; ?>
                    <?php } ?>

                    <?php if(empty($event_list) && !$tabs) { ?>
                    <div class="expertnav__list active">&nbsp;</div>
                    <?php } ?>

                </div>

                <div class="expertrow row">

                    <div class="expertrow__content col-xl-8">
                        <?php $active_tab = false; ?>

                        <?php if(!empty($tabs)) { ?>
                        <div id="content-article"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                            <?php $active = true; ?>

                            <?php foreach($tabs as $tab) { ?>
                            <?php $active = false; ?>
                            <div class="expert__pane expert__pane-<?php echo $tab['key']; ?> <?php echo $tab['active']; ?>"
                                 data-key="<?php echo $tab['key']; ?>">

                                <?php if($tab['key'] === 'all' && !empty($bio) ) { ?>
                                <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_info-bio.tpl'); ?>
                                <?php } ?>

                                <?php if(!empty($tab['children'])) { ?>
                                <?php foreach($tab['children'] as $child) { ?>
                                <div class="expert__pane expert__pane-<?php echo $child['key']; ?> <?php echo $child['active']; ?>"
                                     data-key="<?php echo $child['key']; ?>">
                                    <?php echo $child['isactive'] ? $content : ''; ?>
                                </div>
                                <?php } ?>
                                <?php }else{ ?>
                                <?php echo $tab['isactive'] ? $content : ''; ?>
                                <?php } ?>


                            </div>
                            <?php } ?>

                            <?php $active_tab = true; ?>
                        </div>
                        <?php } ?>

                        <?php if(!$tabs && !empty($bio)) { ?>
                        <div id="content-bio"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                            <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_info-bio.tpl'); ?>
                        </div>
                        <?php $active_tab = true; ?>
                        <?php } ?>

                        <?php if(!empty($event_list)) { ?>
                        <div id="content-register" class="expert__content d-none">
                            <div class="expreg__message ">
                                <div class="expreg__message--preloader">
                                    <div class="cssload-clock"></div>
                                </div>
                                <div class="expreg__message--text">
                                    Подождите, идет поиск регистраций...
                                </div>
                            </div>
                        </div>
                        <?php $active_tab = true; ?>
                        <?php } ?>

                        <div id="content-events" class="expert__content d-none">

                        </div>
                        <?php $active_tab = true; ?>

                    </div>
                    <div class="expertrow__aside col-xl-4">

                        <?php if(!empty($banner)) { ?>
                        <div class="abanner__cont">
                            <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-banner.tpl'); ?>
                        </div>
                        <?php } ?>

                        <div class="expert__master expert__master-short">
                            <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-master.tpl'); ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<?php if($edit_success) { ?>
<div class="d-none">
    <div id="modal-edit-success" class="imgedit">
        <button type="button" class="modal__close" data-fancybox-close>
            <svg class="ico ico-center">
                <use xlink:href="#close"></use>
            </svg>
        </button>
        <div class="imgedit__title">Данные отправлены на модерацию</div>
        <div class="imgedit__text">Среднее время проверки данных составляет 7 дней</div>
        <div class="imgedit__btns">
            <button type="button" class="btn btn-red imgedit__btn imgedit__btn-short" data-fancybox-close>
                Понятно
            </button>
        </div>
    </div>
</div>
<script>
    $(function () {

        showModal('#modal-edit-success', 'fancy-standart');
    })
</script>
<?php } ?>

<?php echo $content_bottom; ?>

<script src="catalog/view/theme/avclub/js/expert-info.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/expert-info.js') ?>"></script>
<?php if($is_email) { ?>
<div class="d-none">
    <div id="modal_write_expert" class="modal__cont modal__cont-expert modal__cont-form">
        <div class="modal__inner">
            <button type="button" class="modal__close" data-fancybox-close>
                <svg class="ico ico-center">
                    <use xlink:href="#close"/>
                </svg>
            </button>

            <div class="modal__title">
                Отправьте ваше сообщение эксперту
            </div>
            <form action="#" class="modal__form row form__expert">

                <div class="modal__inp col-12">
                    <textarea name="message" class="modal__textarea req" placeholder="Напишите ваш вопрос *"></textarea>
                </div>
                <div class="modal__text modal__inp col-12">
                    Укажите контактные данные для обратной связи
                </div>
                <div class="modal__inp col-md-6">
                    <input type="text" name="name" class="modal__input" placeholder="Имя и фамилия *">
                </div>
                <div class="modal__inp col-md-6">
                    <input type="text" name="email" class="modal__input" placeholder="E-mail *">
                </div>
                <div class="modal__inp col-md-6">
                    <input type="text" name="phone" class="modal__input not_req" placeholder="Телефон">
                </div>
                <div class="modal__inp col-md-6">
                    <input type="text" name="company" class="modal__input not_req" placeholder="Компания">
                </div>

                <div class="modal__inp col-12 col-md-6">
                    <button type="submit" class="modal__submit btn btn-red">
                        <span>Отправить</span>
                    </button>
                </div>
                <input type="hidden" name="expert_id" value="<?php echo $expert_id; ?>">
            </form>
        </div>
    </div>
    <div id="expert_success" class="modal__cont">
        <div class="modal__inner">
            <button type="button" class="modal__close" data-fancybox-close>
                <svg class="ico ico-center">
                    <use xlink:href="#close"/>
                </svg>
            </button>

            <div class="modal__title">
                Сообщение успешно отправлено эксперту
            </div>
            <div class="modal__text">

            </div>
            <div class="modal__image modal__image-letter-success">
                <img src="<?php echo $theme_dir; ?>/img/modal-subscribe.svg" alt="">
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if(!empty($event_list)) { ?>
<script src="catalog/view/theme/avclub/js/qrcode.min.js"></script>
<script>

    $(function () {
        var error_text = '<div class="expreg__message --loading">Ошибка загрузки данных.<br>Попробуйте обновить страницу или повторить попытку немного позже</div>';

        $.ajax({
            type: "GET",
            url: "index.php?route=expert/expert/getVisited",
            dataType: "json",
            data: 'expert_id=<?php echo $expert_id; ?>',
            beforeSend: function (json) {
            },
            complete: function (json) {
            },
            success: function (json) {

                if (json['template']) {
                    $('#content-register').html(json['template']);
                } else if (json['error']) {
                    $('#content-register').html(error_text);
                }
                if ($('.expertnav__tabs a.reg').hasClass('active')) {
                    $('.expreg').removeClass('d-none');
                }
            },
            error: function (json) {
                $('#content-register').html(error_text);
                console.log('expert getVisited error', json);
            }
        });

        $.ajax({
            type: "GET",
            url: "index.php?route=expert/expert/getFutureEvents",
            dataType: "json",
            data: '',
            beforeSend: function (json) {
                $('#content-events').html(`
                                            <div class="expreg__message ">
                                <div class="expreg__message--preloader">
                                    <div class="cssload-clock"></div>
                                </div>
                                <div class="expreg__message--text">
                                    Подождите, идет поиск мероприятий...
                                </div>
                            </div>
                `);
            },
            complete: function (json) {
            },
            success: function (json) {
                if (json['template']) {
                    $('#content-events').html(json['template']);
                } else if (json['error']) {
                    $('#content-events').html(error_text);
                }
                if ($('.expertnav__tabs a.fut_ev').hasClass('active')) {
                    $('.expreg').removeClass('d-none');
                    $('.expert__content').removeClass('d-none');
                }
            },
            error: function (json) {
                $('#content-events').html(error_text);
                console.log('expert getFutureEvents error', json);
            }
        });

    })
</script>
<?php } ?>

<?php echo $footer; ?>