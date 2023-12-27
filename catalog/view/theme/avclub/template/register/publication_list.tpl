<?php echo $header; ?>
<?php echo $content_top; ?>

<section class="publ">
        <div class="container">

            <div class="publ--row row">

                <div class="publ--content col-xl-9">

                    <div class="publ--back">
                        <a href="<?php echo $back; ?>" class="link__outer">
                            <span><span class="link">Назад</span></span>
                            <svg class="ico"><use xlink:href="catalog/view/theme/avclub/img/sprite.svg#long-arrow-left">
                            </svg>
                        </a>
                    </div>
                    <div class="publ--title">
                        Размещение публикации
                    </div>

                    <div id="publ__slot" class="publ__slot">


                    </div>

                    <div id="publist" class="publist">

                    </div>
                </div>

                <div class="edit__aside col-xl-3">
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
    </section>

<link rel="stylesheet" href="catalog/view/theme/avclub/css/publication.min.css">

<script>
    var error_text = '<div class="expreg__message --loading">Ошибка загрузки данных.<br>Попробуйте обновить страницу или повторить попытку немного позже</div>';

    $.ajax({
        type: "GET",
        url: "index.php?route=register/publications/getPubApplications",
        dataType: "json",
        data: 'expert_id=<?php echo $expert_id; ?>',
        beforeSend: function (json) {
            $('#publist').html(`
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
                $('#publist').html(json['template']);
            } else if (json['error']) {
                $('#publist').html(error_text);
            }

        },
        error: function (json) {
            $('#publist').html(error_text);
            console.log('expert getPubApplications error', json);
        }
    });

    $.ajax({
        type: "GET",
        url: "index.php?route=register/publications/getPaidPublications",
        dataType: "json",
        data: 'expert_id=<?php echo $expert_id; ?>',
        beforeSend: function (json) {
            $('#publ__slot').html(`
                                <div class="expreg__message ">
                    <div class="expreg__message--preloader">
                        <div class="cssload-clock"></div>
                    </div>
                    <div class="expreg__message--text">
                        Подождите, идет поиск публикаций...
                    </div>
                </div>
                `);
        },
        complete: function (json) {
        },
        success: function (json) {
            if (json['template']) {
                $('#publ__slot').html(json['template']);
            } else if (json['error']) {
                $('#publ__slot').html(error_text);
            }

        },
        error: function (json) {
            $('#publ__slot').html(error_text);
            console.log('expert getPaidApplications error', json);
        }
    });
</script>

<?php require(DIR_TEMPLATE . 'avclub/template/register/script-change.tpl'); ?>
<?php echo $footer; ?>