<?php echo $header; ?>
<?php echo $content_top; ?>

<section class="publ">
    <div class="container">

        <div class="publ--row row">

            <div class="publ--content col-xl-9">

                <div class="publ--back">
                    <a href="<?php echo $back; ?>" class="link__outer">
                        <span><span class="link">Назад</span></span>
                        <svg class="ico">
                            <use xlink:href="./img/sprite.svg#long-arrow-left">
                        </svg>
                    </a>
                </div>
                <div class="publ--title">
                    Размещение публикации
                </div>

                <form action="#" class="publ--form row">

                    <div class="regform__outer regform__outer-main col-12">
                        <div class="regform__name">Описание публикации <span>*</span></div>
                        <div class="regform__inp regform__inp-plh regform__inp-textarea">
                            <div class="regform__textarea" contentEditable="true"
                                 oninput="textareainp(this);"></div>
                            <textarea name="expertise" data-input-change
                                      class=""></textarea>
                            <div class="regform__plh">Заголовок публикации</div>
                        </div>
                    </div>

                    <div class="regform__outer col-12">
                        <div class="regform__name">Дополнительная информация к публикации <span>*</span></div>
                        <div class="regform__inp regform__inp-plh regform__inp-textarea">
                            <div class="regform__textarea regform__textarea-limit" contentEditable="true"
                                 oninput="textareainp(this);"></div>
                            <textarea name="expertise" data-input-change
                                      class=""></textarea>
                            <div class="regform__plh d-none d-md-block">Добавьте описание публикации и ссылки на
                                материалы
                            </div>
                            <div class="regform__limit">Осталось символов: 2000</div>
                        </div>
                    </div>

                    <div class="regform__outer col-12">
                        <div class="regfile">
                            <div class="regfile--name">Прикрепить файлы</div>
                            <div class="regfile--text">Загрузите файл с публикацией. <br class="d-md-none">Формат файла:
                                docx
                            </div>
                            <div class="regfile--media"></div>
                            <div class="regfile--link">
                                <a href="#" class="link regfile--add">+ Прикрепить файл</a>
                            </div>
                        </div>
                    </div>

                    <div class="regform__outer col-12">
                        <div class="regfile">
                            <div class="regfile--name">Добавить остальные материалы</div>
                            <div class="regfile--text">
                                Загрузите материал относящийся к публикации.
                                <br class="d-none d-md-inline">Формат: jpg, pdf, png. Максимальный размер: до 10Mb
                            </div>
                            <div class="regfile--media"></div>
                            <div class="regfile--link">
                                <a href="#" class="link regfile--add" data-type="img">+ Прикрепить файл</a>
                            </div>
                        </div>
                    </div>

                    <div class="regbrand__start regbrand col-12">
                        <?php echo $company_template;  ?>
                    </div>

                    <div class="regform__outer col-12">
                        <div class="regform__agree">
                            Нажимая на кнопку, я принимаю условия пользовательского
                            соглашения и даю согласие на обработку моих персональных данных. *
                        </div>
                    </div>
                    <div class="regform__btns regform__outer col-12">
                        <button type="button" class="regform__btn btn btn-invert submit">
                            <span>Отправить</span>
                        </button>
                    </div>

                </form>

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
<!--<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_fail.tpl'); ?>-->

<script>
    $(function () {
        let additionFiles = [];
        let files = [];

        function showLoadingMessage() {
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            $('.publ').html(`
            <div class="publ__success">
                <div class="container">
                    <div class="expreg__message ">
                        <div class="expreg__message--preloader">
                            <div class="cssload-clock"></div>
                        </div>
                        <div class="expreg__message--text">
                            Подождите, идет отправка данных на сервер...
                        </div>
                    </div>
                </div>
            </div>
        `);
        }

        function showMessage(title) {
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            $('.publ').html(`
            <div class="publ__success">
                <div class="container">
                    <div class="publ__success--inner">
                        <div class="publ__success--title">
                            ${title}
                        </div>
                        <div class="publ__success--btn">
                            <a href="/account/" class="btn btn-invert">
                                Вернуться в личный кабинет
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `);
        }

        $(document).on('click', '.regfile--add', function (e) {
            e.preventDefault();

            let self = $(this),
                cont = self.closest('.regfile'),
                list = $('.regfile--media', cont),
                type = self.attr('data-type') ? self.attr('data-type') : 'default';

            // Создаем динамический элемент input для загрузки файла
            let inputFile = $('<input type="file" name="file" style="display:none;" />');

            // Добавляем input в форму (если формы нет, то создаем)
            if ($('#form-upload').length === 0) {
                $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display:none;"></form>');
            }
            $('#form-upload').html('').append(inputFile);

            // Вызываем клик на input для открытия диалогового окна выбора файла
            inputFile.trigger('click');

            // Проверяем изменение значения input (когда пользователь выбрал файл)
            inputFile.on('change', function () {
                // Отображаем информацию о выбранном файле
                let file = this.files[0];
                let item = '';

                switch (type) {
                    case 'img':
                        item += '<div class="regfile--item regfile--item-img">';
                        let imgUrl = URL.createObjectURL(file);
                        item += '<img src="' + imgUrl + '" alt="' + file.name + '">';
                        item += '<span>' + file.name + '</span>';
                        item += '</div>';
                        break;

                    default:
                        item += '<div class="regfile--item regfile--item-default">';
                        item += '<span>' + file.name + '</span>';
                        item += '</div>';
                }

                list.addClass('--active regfile--media-' + type).append(item);

                let formData = new FormData();
                formData.append('file', file);

                $.ajax({
                    type: 'POST',
                    url: 'index.php?route=register/publication/uploadImage',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        let jsonResponse;

                        if (typeof response === 'string') {
                            try {
                                jsonResponse = JSON.parse(response);
                            } catch (error) {
                                console.error('Ошибка при парсинге JSON:', error);
                                return;
                            }
                        } else if (typeof response === 'object') {
                            jsonResponse = response;
                        } else {
                            console.error('Ошибка: response не является строкой или объектом');
                            return;
                        }

                        if (jsonResponse.name && (jsonResponse.name.endsWith('.doc') || jsonResponse.name.endsWith('.docx'))) {
                            console.log('Файл с расширением .doc или .docx:', jsonResponse);
                            files.push(jsonResponse)
                            console.log(files)
                        } else {
                            console.log('Файл с другим расширением:', jsonResponse);
                            additionFiles.push(jsonResponse)
                            console.log(additionFiles)
                        }

                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });
        });

        $(document).on('click', '.regform__btn.submit', function (e) {
            e.preventDefault();

            const title = $('.regform__textarea:first').text();
            const additionalInfo = $('.regform__textarea-limit').text();
            const companyId = $('input[name="b24_company_id"]').val();

            // const data = {
            //     data: {
            //         dealType: 'publication',
            //         additionFiles: [
            //             {
            //                 url: 'https://www.avclub.pro/image/no_image.png',
            //                 name: 'no_image.png'
            //             },
            //         ],
            //         files: {
            //             url: 'https://www.avclub.pro/image/no_image.png',
            //             name: 'no_image.png'
            //         },
            //         company_id: 51625,
            //         title: 'тест',
            //         addition_info: 'дополнительная информация'
            //     },
            //     expert_id: '<?php echo $expert_id; ?>'
            // }

            const data = {
                data: {
                    dealType: 'publication',
                    additionFiles: additionFiles,
                    files: files,
                    company_id: +companyId,
                    title: title,
                    addition_info: additionalInfo
                },
                expert_id: '<?php echo $expert_id; ?>'
            }

            console.log(data)

            $.ajax({
                type: "POST",
                url: "index.php?route=expert/expert/sendPublication",
                dataType: "json",
                data: data,
                beforeSend: function (json) {
                    showLoadingMessage();
                },
                complete: function (json) {
                },
                success: function (json) {
                    if (json['code'] === 200) {
                        showMessage('Заявка на размещение<br>публикации отправлена');
                    } else if (json['error']) {
                        showMessage('Ошибка размещения<br> публикации');
                    }
                },
                error: function (json) {
                    showMessage('Ошибка размещения<br> публикации');
                    console.log('publication sendPublication error', json);
                }
            });
        });

    });

</script>

<link rel="stylesheet" href="<?php echo $theme_dir; ?>/libs/croppie/croppie.css">
<link rel="stylesheet" href="<?php echo $theme_dir; ?>/css/publication.min.css">
<script src="<?php echo $theme_dir; ?>/libs/croppie/croppie.min.js"></script>

<script src="<?php echo $theme_dir; ?>/js/register-main.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register-main.js') ?>"></script>
<script src="<?php echo $theme_dir; ?>/js/register-edit.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register-edit.js') ?>"></script>
<script src="<?php echo $theme_dir; ?>/js/register-brand.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register-brand.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/css/suggestions.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/js/jquery.suggestions.min.js"></script>
<?php require(DIR_TEMPLATE . 'avclub/template/register/script-change.tpl'); ?>
<?php echo $footer; ?>