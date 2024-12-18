<style>
    .expreg__btns .message {
        color: var(--red);
        font-size: 14px;
        font-weight: 700;
    }

    .loader {
        width: 48px;
        height: 48px;
        border: 2px solid var(--red);
        border-radius: 50%;
        display: inline-block;
        position: relative;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }

    .loader::after {
        content: '';
        box-sizing: border-box;
        position: absolute;
        left: 50%;
        top: 0;
        background: var(--red);
        width: 3px;
        height: 20px;
        transform: translateX(-50%);
    }

    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
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
                    <?php if($is_modified && $is_expert) { ?>
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

                        <?php if($tabs) { $show_tab === 'speeches'?>
                        <a href="#"
                           class="expertnav__tab expertnav__tab-tab expertnav__tab-tab link <?php echo !$active_tab ? 'active' : ''; ?>"
                           data-type="article">Мои выступления в АВ Клубе</a>
                        <?php $active_tab = true; ?>
                        <?php } ?>

                        <?php if(!empty($event_list)) { ?>
                        <a href="#"
                           class="expertnav__tab expertnav__tab-tab events link <?php echo $show_tab === '' ? 'active' : ''; ?>"
                           data-type="events">Мои приглашения</a>
                        <?php $active_tab = true; ?>
                        <?php } ?>

                        <!--  <?php if(!$tabs) { ?>
                          <a href="#"
                             class="expertnav__tab expertnav__tab-tab bio link <?php echo !$active_tab ? 'active' : ''; ?>"
                             data-type="bio">Биография</a>
                          <?php $active_tab = true; ?>
                          <?php } ?>-->

                        <?php if(!empty($event_list)) { ?>
                        <a href="#"
                           class="expertnav__tab expertnav__tab-tab voting link <?php echo $show_tab === 'votes' ? 'active' : ''; ?>"
                           data-type="voting">Голосование</a>
                        <?php $active_tab = true; ?>
                        <?php } ?>

                        <!-- <?php if(!empty($event_list)) { ?>
                            <a href="#"
                               class="expertnav__tab expertnav__tab-tab services link <?php echo $show_tab === 'services' ? 'active' : ''; ?>"
                               data-type="services">Услуги</a>
                        <?php $active_tab = true; ?>
                        <?php } ?> -->

                        <div class="expertnav__tab expertnav__tab-right" style="opacity: 0">Профиль</div>
                        <?php if(!empty($event_list)) { ?>
                        <a href="#"
                           class="expertnav__tab expertnav__tab-tab apps link <?php echo $show_tab === 'apps' ? 'active' : ''; ?>"
                           data-type="apps">Мои заявки<span class="drpd--icon">
                                            <svg class="ico"><use xlink:href="image/icons/sprite.svg#plus">
                                            </use></svg>
                                        </span></a>
                        <?php $active_tab = true; ?>
                        <?php } ?>
                        <!--  <div class="expertnav__tab drpd">
                              <a href="#" class="link__outer drpd--btn">
                                  <span class="drpd--text link">Мои заявки</span>
                                  <span class="drpd--icon">
                                            <svg class="ico"><use xlink:href="image/icons/sprite.svg#plus">
                                            </use></svg>
                                        </span>
                              </a>
                              <div class="drpd--list">
                                  <a href="<?php echo $company_add_href; ?>" class="link">Добавить новую компанию</a>
                                  <a href="<?php echo $publication; ?>" class="link">Добавить публикацию в журнал</a>
                                   <a href="#" class="link">Добавить заявку на участие в форумах</a>
                                <a href="#" class="link">Добавить заявку на участие в вебинаре</a>
                            </div>
                        </div>-->
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
                    <div id="navlist-voting"
                         class="expertnav__list voting__tabs <?php echo $show_tab === 'votes' ? 'active' : ''; ?>">
                        <ul class="list-hor">
                            <li><a href="#"
                                   class="expertnav__change voting_active expertnav__tab-tab active <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="voting_active">АКТИВНЫЕ</a></li>
                            <?php $active_tab = true; ?>
                            <li><a href="#"
                                   class="expertnav__change voting_finished expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="voting_finished">ЗАВЕРШЁННЫЕ</a></li>
                            <?php $active_tab = true; ?>
                            <!--<li><a href="#"
                                   class="expertnav__change publications expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="publications">ПУБЛИКАЦИИ</a></li>
                            <?php $active_tab = true; ?>
                            <?php if(!empty($event_list)) { ?>
                            <li><a href="#"
                                   class="expertnav__change catalog_list expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="catalog_list">Компании</a></li>
                            <?php $active_tab = true; ?>
                            <?php } ?>-->
                        </ul>
                    </div>
                    <div id="navlist-events" class="expertnav__list events__tabs">
                        <ul class="list-hor">
                            <li><a href="#"
                                   class="expertnav__change fut_ev expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="future_events">ФОРУМЫ</a></li>
                            <?php $active_tab = true; ?>
                            <li><a href="#"
                                   class="expertnav__change reg expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="webinars">ВЕБИНАРЫ</a></li>
                            <?php $active_tab = true; ?>
                            <!--<li><a href="#"
                                   class="expertnav__change publications expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="publications">ПУБЛИКАЦИИ</a></li>
                            <?php $active_tab = true; ?>
                            <?php if(!empty($event_list)) { ?>
                            <li><a href="#"
                                   class="expertnav__change catalog_list expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="catalog_list">Компании</a></li>
                            <?php $active_tab = true; ?>
                            <?php } ?>-->
                        </ul>
                    </div>
                    <div id="navlist-services"
                         class="expertnav__list services__tabs <?php echo $show_tab === 'services' ? 'active' : ''; ?>">
                        <ul class="list-hor">
                            <li><a href="#"
                                   class="expertnav__change services_active expertnav__tab-tab active <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="services_active">АКТИВНЫЕ</a></li>
                            <?php $active_tab = true; ?>
                            <li><a href="#"
                                   class="expertnav__change services_finished expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="services_finished">ЗАВЕРШЁННЫЕ</a></li>
                            <?php $active_tab = true; ?>
                        </ul>
                    </div>
                    <div id="navlist-apps"
                         class="expertnav__list apps__tabs <?php echo $show_tab === 'apps' ? 'active' : ''; ?>">
                        <ul class="list-hor">
                            <li><a href="#"
                                   class="expertnav__change apps_forums expertnav__tab-tab active <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="apps_forums">ФОРУМЫ</a></li>
                            <?php $active_tab = true; ?>
                            <li><a href="#"
                                   class="expertnav__change apps_webinars expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="apps_webinars">ВЕБИНАРЫ</a></li>
                            <?php $active_tab = true; ?>
                            <li><a href="#"
                                   class="expertnav__change apps_pubs expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="apps_pubs">ПУБЛИКАЦИИ</a></li>
                            <?php $active_tab = true; ?>
                            <li><a href="#"
                                   class="expertnav__change apps_ads expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="apps_ads">РЕКЛАМА</a></li>
                            <?php $active_tab = true; ?>
                            <li><a href="#"
                                   class="expertnav__change apps_finished expertnav__tab-tab <?php echo !$active_tab ? 'active' : ''; ?>"
                                   data-type="apps_finished">ЗАВЕРШЁННЫЕ</a></li>
                            <?php $active_tab = true; ?>
                        </ul>
                    </div>


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

                            <?php $active_tab = false; ?>
                        </div>
                        <?php } ?>

                        <div id="content-webinars"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?> d-none">

                        </div>
                        <?php $active_tab = true; ?>

                        <div id="content-events"
                             class="expert__content <?php echo $show_tab === '' ? 'active' : ''; ?> d-none">

                        </div>
                        <?php $active_tab = true; ?>

                        <div id="publist"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?> d-none">

                        </div>
                        <?php $active_tab = true; ?>

                        <div id="catalog_list"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?> d-none">

                        </div>
                        <?php $active_tab = true; ?>

                        <div id="content-active-votes"
                             class="expert__content <?php echo $show_tab === 'votes' ? 'active' : ''; ?>">
                            <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_votes.tpl'); ?>
                        </div>

                        <div id="content-finished-votes"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                            <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_votes.tpl'); ?>
                        </div>

                        <div id="content-active-services"
                             class="expert__content <?php echo $show_tab === 'votes' ? 'active' : ''; ?>">
                            <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_services.tpl'); ?>
                        </div>

                        <div id="content-finished-services"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                            <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_services.tpl'); ?>
                        </div>

                        <div id="content-apps-forums"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                            <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_apps_forums.tpl'); ?>
                        </div>

                        <div id="content-apps-webinars"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                            <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_apps_webinars.tpl'); ?>
                        </div>

                        <div id="content-apps-pubs"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                            <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_apps_pubs.tpl'); ?>
                        </div>

                        <div id="content-apps-ads"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                            <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_apps_ads.tpl'); ?>
                        </div>

                        <div id="content-apps-finished"
                             class="expert__content <?php echo !empty($active_tab) ? '' : 'active'; ?>">
                            <?php require(DIR_TEMPLATE . 'avclub/template/expert/expert_apps_finished.tpl'); ?>
                        </div>
                    </div>
                    <div class="expertrow__aside col-xl-4">

                        <?php if($logged) { ?>
                        <?php if(!empty($banner)) { ?>
                        <div class="abanner__cont">
                            <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-banner.tpl'); ?>
                        </div>
                        <?php } ?>
                        <?php } else { ?>
                        <div class="expert__master expert__master-short">
                            <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-master.tpl'); ?>
                        </div>
                        <?php } ?>


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
        <?php if($is_expert) { ?>
        <div class="imgedit__title">Данные отправлены на модерацию</div>
        <div class="imgedit__text">Среднее время проверки данных составляет 7 дней</div>
        <?php } else { ?>
        <div class="imgedit__title">Данные успешно изменены!</div>
        <?php } ?>

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
<link rel="stylesheet" href="catalog/view/theme/avclub/css/publication.min.css">
<?php if(!empty($event_list)) { ?>
<script src="catalog/view/theme/avclub/js/qrcode.min.js"></script>
<script>

    $(function () {
        localStorage.setItem('selectedYear', '2024');

        var error_text = '<div class="expreg__message --loading">Ошибка загрузки данных.<br>Попробуйте обновить страницу или повторить попытку немного позже</div>';
        console.log(1111)
        $.ajax({
            type: "GET",
            url: "index.php?route=expert/expert/getRegistrations",
            dataType: "json",
            data: {
                'expert_id': '<?php echo $expert_id; ?>',
                'event_type': 'webinar'
            },
            beforeSend: function (json) {
                $('#content-webinars').html(`
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
                    $('#content-webinars').html(json['template']);
                } else if (json['error']) {
                    $('#content-webinars').html(error_text);
                }
                if ($('.expertnav__change.reg').hasClass('active')) {
                    $('.expreg').removeClass('d-none');
                }
            },
            error: function (json) {
                $('#content-webinars').html(error_text);
                console.log('expert getVisited error', json);
            }
        });

        $.ajax({
            type: "GET",
            url: "index.php?route=expert/expert/getRegistrations",
            dataType: "json",
            data: {
                'expert_id': '<?php echo $expert_id; ?>',
                'event_type': 'forum'
            },
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
                if ($('.expertnav__tabs a.events').hasClass('active')) {
                    $('.events__tabs').removeClass('d-none');
                    $('#navlist-bio').addClass('d-none');
                    $('.expertnav__tab-tab.reg').removeClass('active');
                    $('.expertnav__tab-tab.fut_ev').addClass('active');
                    $('#navlist-events').addClass('active');
                    $('.expertnav__tab-tab.publications').removeClass('active');

                    $('#content-events').removeClass('d-none');
                    $('#content-events .expreg').removeClass('d-none');
                    $('#content-events.expert__content').css("display", "block");
                    $('#content-events').removeClass('d-none')
                }

            },
            error: function (json) {
                $('#content-events').html(error_text);
                console.log('expert getFutureEvents error', json);
            }
        });

        $.ajax({
            url: 'index.php?route=expert/content',
            type: 'get',
            dataType: 'json',
            data: {
                'expert_id': '<?php echo $expert_id; ?>',
                'type': 'all'
            },
            beforeSend: function () {
                $('.expertrow__content').addClass('loading');
            },
            complete: function () {
                setTimeout(function () {
                    $('.expertrow__content').removeClass('loading');
                }, 200);
            },
            success: function (data) {
                if (data['template']) {
                    $('.expert__pane-all').html(data['template']);
                }
            },
            error: function (data) {
                console.log('error', data);
            }
        });

        // $.ajax({
        //     type: "GET",
        //     url: "index.php?route=register/publications/getPubApplications",
        //     dataType: "json",
        //     data: 'expert_id=<?php echo $expert_id; ?>',
        //     beforeSend: function (json) {
        //         $('#publist').html(`
        //            <div class="expreg__message ">
        //                 <div class="expreg__message--preloader">
        //                     <div class="cssload-clock"></div>
        //                 </div>
        //                 <div class="expreg__message--text">
        //                     Подождите, идет поиск заявок...
        //                 </div>
        //            </div>
        //         `);
        //     },
        //     complete: function (json) {
        //     },
        //     success: function (json) {
        //         if (json['template']) {
        //             $('#publist').html(json['template']);
        //         } else if (json['error']) {
        //             $('#publist').html(error_text);
        //         }
        //         if ($('.expertnav__tabs a.publications').hasClass('active')) {
        //             $('.expreg').removeClass('d-none');
        //             $('.expert__content').removeClass('d-none');
        //             $('#navlist-bio').removeClass('active');
        //         }
        //
        //     },
        //     error: function (json) {
        //         $('#publist').html(error_text);
        //         console.log('expert getPubApplications error', json);
        //     }
        // });

        // $.ajax({
        //     type: "GET",
        //     url: "index.php?route=expert/expert/getCatalogList",
        //     dataType: "json",
        //     data: 'expert_id=<?php echo $expert_id; ?>',
        //     beforeSend: function (json) {
        //         $('#catalog_list').html(`
        //             <div class="expreg__message ">
        //                 <div class="expreg__message--preloader">
        //                     <div class="cssload-clock"></div>
        //                 </div>
        //                 <div class="expreg__message--text">
        //                     Подождите, идет поиск заявок...
        //                 </div>
        //             </div>
        //         `);
        //     },
        //     complete: function (json) {
        //     },
        //     success: function (json) {
        //         if (json['template']) {
        //             $('#catalog_list').html(json['template']);
        //         } else if (json['error']) {
        //             $('#catalog_list').html(error_text);
        //         }
        //         if ($('.expertnav__tabs a.catalog_list').hasClass('active')) {
        //             $('.expreg').removeClass('d-none');
        //             $('.expert__content').removeClass('d-none');
        //             $('#navlist-bio').removeClass('active');
        //         }
        //
        //     },
        //     error: function (json) {
        //         $('#catalog_list').html(error_text);
        //         console.log('expert getCatalogList error', json);
        //     }
        // });

        $.ajax({
            type: "GET",
            url: "index.php?route=expert/expert/getActiveVotes",
            dataType: "json",
            data: {
                'expert_id': '<?php echo $expert_id; ?>'
            },
            beforeSend: function (json) {
                $('#content-active-votes').html(`
                    <div class="expreg__message ">
                        <div class="expreg__message--preloader">
                            <div class="cssload-clock"></div>
                        </div>
                        <div class="expreg__message--text">
                            Подождите, идет поиск опросов...
                        </div>
                    </div>
                `);
            },
            complete: function (json) {
            },
            success: function (json) {
                if (json['template']) {
                    $('#content-active-votes').html(json['template']);
                } else if (json['error']) {
                    $('#content-active-votes').html(error_text);
                }
                if ($('.expertnav__tabs a.voting').hasClass('active')) {
                    $('#content-active-votes').removeClass('d-none');
                    $('#content-active-votes .expreg-votes').removeClass('d-none');
                    $('#navlist-bio').removeClass('active');
                    $('#navlist-events').removeClass('active');
                }

            },
            error: function (json) {
                $('#catalog_list').html(error_text);
                console.log('expert getCatalogList error', json);
            }
        });

        $.ajax({
            type: "GET",
            url: "index.php?route=expert/expert/getFinishedVotes",
            dataType: "json",
            data: {
                'expert_id': '<?php echo $expert_id; ?>'
            },
            beforeSend: function (json) {
                $('#content-finished-votes').html(`
                    <div class="expreg__message ">
                        <div class="expreg__message--preloader">
                            <div class="cssload-clock"></div>
                        </div>
                        <div class="expreg__message--text">
                            Подождите, идет поиск опросов...
                        </div>
                    </div>
                `);
            },
            complete: function (json) {
            },
            success: function (json) {
                if (json['template']) {
                    $('#content-finished-votes').html(json['template']);
                } else if (json['error']) {
                    $('#content-finished-votes').html(error_text);
                }
                if ($('.expertnav__tabs a.voting').hasClass('active')) {
                    $('#content-finished-votes').removeClass('d-none');
                    $('#content-finished-votes .expreg-votes').removeClass('d-none');
                    $('#navlist-bio').removeClass('active');
                    $('#navlist-events').removeClass('active');
                }

            },
            error: function (json) {
                $('#catalog_list').html(error_text);
                console.log('expert getCatalogList error', json);
            }
        });

        $(document).on('click', '.invitation', function (event) {
            event.stopPropagation();

            var dealId = $(this).data('deal-id');
            var eventType = $(this).data('event-type');
            var $btns = $(this).closest('.expreg__btns');
            $btns.html('<span class="loader" style="margin: 0 auto"></span>');

            console.log(dealId);
            console.log(eventType);
            var self = this;

            $.ajax({
                type: "GET",
                url: "index.php?route=expert/expert/confirmParticipation",
                dataType: "json",
                data: {type: eventType, deal_id: dealId},
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (json) {
                    $btns.find('.loader').hide();
                    $btns.html('<span class="message">Приглашение успешно принято!</span>');

                    $btns.closest('.expreg').find('.expreg__status').html(`
                        <div class="expreg__status--item" style="display: none;">
                            <span></span>
                            Выслано приглашение
                        </div>
                        <div class="expreg__status--item" style="display: none;">
                            <span></span>
                            Участие одобрено
                        </div>
                    `).find('.expreg__status--item').fadeIn(1000);


                    var $message = $btns.find('.message');
                    setTimeout(() => {
                        $message.hide();
                        console.log(json);
                    }, 2000);
                },
                error: function () {
                    $btns.find('.loader').hide();
                    $btns.html('<span class="message">Ошибка принятия приглашения</span>');

                    var $message = $btns.find('.message');
                    setTimeout(() => {
                        $message.hide();
                    }, 2000);

                    console.log('ERROR');
                }
            });
        });

        // $.ajax({
        //     type: "GET",
        //     url: "index.php?route=expert/expert/getServices",
        //     dataType: "json",
        //     data: {
        //         'expert_id': '<?php echo $expert_id; ?>',
        //         'type': 'future'
        //     },
        //     beforeSend: function (json) {
        //         $('#content-active-services').html(`
        //            <div class="expreg__message ">
        //                 <div class="expreg__message--preloader">
        //                     <div class="cssload-clock"></div>
        //                 </div>
        //                 <div class="expreg__message--text">
        //                     Подождите, идет поиск услуг...
        //                 </div>
        //            </div>
        //         `);
        //     },
        //     complete: function (json) {
        //     },
        //     success: function (json) {
        //         if (json['template']) {
        //             $('#content-active-services').html(json['template']);
        //         } else if (json['error']) {
        //             $('#content-active-services').html(error_text);
        //         }
        //
        //     },
        //     error: function (json) {
        //         $('#content-active-services').html(error_text);
        //         console.log('expert active services error', json);
        //     }
        // });
        //
        // $.ajax({
        //     type: "GET",
        //     url: "index.php?route=expert/expert/getServices",
        //     dataType: "json",
        //     data: {
        //         'expert_id': '<?php echo $expert_id; ?>',
        //         'type': 'last'
        //     },
        //     beforeSend: function (json) {
        //         $('#content-finished-services').html(`
        //            <div class="expreg__message ">
        //                 <div class="expreg__message--preloader">
        //                     <div class="cssload-clock"></div>
        //                 </div>
        //                 <div class="expreg__message--text">
        //                     Подождите, идет поиск услуг...
        //                 </div>
        //            </div>
        //         `);
        //     },
        //     complete: function (json) {
        //     },
        //     success: function (json) {
        //         if (json['template']) {
        //             $('#content-finished-services').html(json['template']);
        //         } else if (json['error']) {
        //             $('#content-finished-services').html(error_text);
        //         }
        //
        //     },
        //     error: function (json) {
        //         $('#content-finished-services').html(error_text);
        //         console.log('expert finished services error', json);
        //     }
        // });


        // Флаг для отслеживания выполнения запроса
        let isRequestSent = false;

        $('.expertnav__tab-tab.apps').on('click', function (e) {
            e.preventDefault();

            // Проверяем, был ли запрос уже отправлен
            if (!isRequestSent) {
                isRequestSent = true; // Устанавливаем флаг, чтобы запрос больше не отправлялся

                console.log('1212')

                $.ajax({
                    type: "GET",
                    url: "index.php?route=expert/expert/getApps",
                    dataType: "json",
                    data: {
                        'expert_id': '<?php echo $expert_id; ?>',
                        'type': 'forum'
                    },
                    beforeSend: function (json) {
                        $('#content-apps-forums').html(`
                        <div class="expreg__message ">
                            <div class="expreg__message--preloader">
                                <div class="cssload-clock"></div>
                            </div>
                            <div class="expreg__message--text">
                                Подождите, идет поиск заявок...
                            </div>
                        </div>
                    `);
                    },
                    complete: function (json) {
                    },
                    success: function (json) {
                        if (json['template']) {
                            $('#content-apps-forums').html(json['template']);
                        } else if (json['error']) {
                            $('#content-apps-forums').html(error_text);
                        }
                        if ($('.expertnav__tabs a.apps_forums').hasClass('active')) {
                            $('#content-apps-forums').removeClass('d-none');
                            $('#content-apps-forums .expreg-apps-forums').removeClass('d-none');
                            $('#navlist-bio').removeClass('active');
                            $('#navlist-events').removeClass('active');
                        }

                    },
                    error: function (json) {
                        $('#content-apps-forums').html(error_text);
                        console.log('expert getAppForum error', json);
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "index.php?route=expert/expert/getApps",
                    dataType: "json",
                    data: {
                        'expert_id': '<?php echo $expert_id; ?>',
                        'type': 'webinar'
                    },
                    beforeSend: function (json) {
                        $('#content-apps-webinars').html(`
                        <div class="expreg__message ">
                            <div class="expreg__message--preloader">
                                <div class="cssload-clock"></div>
                            </div>
                            <div class="expreg__message--text">
                                Подождите, идет поиск заявок...
                            </div>
                        </div>
                    `);
                    },
                    complete: function (json) {
                    },
                    success: function (json) {
                        if (json['template']) {
                            $('#content-apps-webinars').html(json['template']);
                        } else if (json['error']) {
                            $('#content-apps-webinars').html(error_text);
                        }
                        // if ($('.expertnav__tabs a.voting').hasClass('active')) {
                        //     $('#content-finished-votes').removeClass('d-none');
                        //     $('#content-finished-votes .expreg-votes').removeClass('d-none');
                        //     $('#navlist-bio').removeClass('active');
                        //     $('#navlist-events').removeClass('active');
                        // }

                    },
                    error: function (json) {
                        $('#content-apps-webinars').html(error_text);
                        console.log('expert getAppWebinar error', json);
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "index.php?route=expert/expert/getApps",
                    dataType: "json",
                    data: {
                        'expert_id': '<?php echo $expert_id; ?>',
                        'type': 'pubs'
                    },
                    beforeSend: function (json) {
                        $('#content-apps-pubs').html(`
                        <div class="expreg__message ">
                            <div class="expreg__message--preloader">
                                <div class="cssload-clock"></div>
                            </div>
                            <div class="expreg__message--text">
                                Подождите, идет поиск заявок...
                            </div>
                        </div>
                    `);
                    },
                    complete: function (json) {
                    },
                    success: function (json) {
                        if (json['template']) {
                            $('#content-apps-pubs').html(json['template']);
                        } else if (json['error']) {
                            $('#content-apps-pubs').html(error_text);
                        }
                        // if ($('.expertnav__tabs a.voting').hasClass('active')) {
                        //     $('#content-finished-votes').removeClass('d-none');
                        //     $('#content-finished-votes .expreg-votes').removeClass('d-none');
                        //     $('#navlist-bio').removeClass('active');
                        //     $('#navlist-events').removeClass('active');
                        // }

                    },
                    error: function (json) {
                        $('#content-apps-pubs').html(error_text);
                        console.log('expert getAppPubs error', json);
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "index.php?route=expert/expert/getApps",
                    dataType: "json",
                    data: {
                        'expert_id': '<?php echo $expert_id; ?>',
                        'type': 'ads'
                    },
                    beforeSend: function (json) {
                        $('#content-apps-ads').html(`
                        <div class="expreg__message ">
                            <div class="expreg__message--preloader">
                                <div class="cssload-clock"></div>
                            </div>
                            <div class="expreg__message--text">
                                Подождите, идет поиск заявок...
                            </div>
                        </div>
                    `);
                    },
                    complete: function (json) {
                    },
                    success: function (json) {
                        if (json['template']) {
                            $('#content-apps-ads').html(json['template']);
                        } else if (json['error']) {
                            $('#content-apps-ads').html(error_text);
                        }
                        // if ($('.expertnav__tabs a.voting').hasClass('active')) {
                        //     $('#content-finished-votes').removeClass('d-none');
                        //     $('#content-finished-votes .expreg-votes').removeClass('d-none');
                        //     $('#navlist-bio').removeClass('active');
                        //     $('#navlist-events').removeClass('active');
                        // }

                    },
                    error: function (json) {
                        $('#content-apps-ads').html(error_text);
                        console.log('expert getAppAds error', json);
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "index.php?route=expert/expert/getFinishedApps",
                    dataType: "json",
                    data: {
                        'expert_id': '<?php echo $expert_id; ?>'
                    },
                    beforeSend: function (json) {
                        $('#content-apps-finished').html(`
                        <div class="expreg__message ">
                            <div class="expreg__message--preloader">
                                <div class="cssload-clock"></div>
                            </div>
                            <div class="expreg__message--text">
                                Подождите, идет поиск заявок...
                            </div>
                        </div>
                    `);
                    },
                    complete: function (json) {
                    },
                    success: function (json) {
                        if (json['template']) {
                            $('#content-apps-finished').html(json['template']);
                        } else if (json['error']) {
                            $('#content-apps-finished').html(error_text);
                        }
                        // if ($('.expertnav__tabs a.voting').hasClass('active')) {
                        //     $('#content-finished-votes').removeClass('d-none');
                        //     $('#content-finished-votes .expreg-votes').removeClass('d-none');
                        //     $('#navlist-bio').removeClass('active');
                        //     $('#navlist-events').removeClass('active');
                        // }

                    },
                    error: function (json) {
                        $('#content-apps-finished').html(error_text);
                        console.log('expert getAppAds error', json);
                    }
                });

            }
        });

    })

</script>

<style>
    .events__tabs {
        width: 100%;
        margin-top: 14px;
        font-size: .8rem;
    }

    .preactive-before:before {
        background-color: black;
    }
</style>
<?php } ?>

<?php echo $footer; ?>