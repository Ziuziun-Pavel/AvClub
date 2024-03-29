<style>
    .regform__inp-error {
        color: var(--red);
        font-size: .9rem;
        margin-top: .5rem;
    }
</style>

<?php echo $header; ?>
<?php echo $content_top; ?>
(Раздел в разработке)
<section class="section_edit">
    <div class="container">

        <div class="edit__row row">

            <form id="form-edit-company" class="edit__content col-xl-9">

                <div class="edit__back edit__back-sm edit__back-reverse">
                    <a href="<?php echo $back; ?>" class="link__outer">
                        <span><span class="link">Назад</span></span>
                        <svg class="ico">
                            <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#long-arrow-left">
                        </svg>
                    </a>
                </div>
                <div class="edit__title">Размещение информации о&nbsp;новой компании в каталоге</div>

                <div class="regform__outer regform__outer-capt">
                    Добавить логотип
                </div>
                <div class="edit__image edit__image-company">
                    <a href="#modal-edit-image" class="edit__image--image">
                        <img src="image/placeholder_empty.png" alt="" id="edit-image"
                             data-placeholder="image/cache/placeholder_empty-220x220.png">
                        <svg class="ico ico-center">
                            <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#photo">
                        </svg>
                    </a>
                    <textarea name="photo"></textarea>
                    <div class="regform__inp-error"></div>
                </div>

                <div class="edit__inputs row">

                    <div class="regform__outer regform__outer-capt col-12">
                        Основная информация
                    </div>
                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input
                                    type="text"
                                    name="name"
                                    value=""
                                    class="regform__input"
                                    autocomplete="false"
                                    data-input-change/>
                            <div class="regform__plh">Название компании</div>
                        </div>
                        <div class="regform__inp-error"></div>
                    </div>
                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input
                                    type="text"
                                    name="city"
                                    value=""
                                    class="regform__input"
                                    autocomplete="false"
                                    data-input-change/>
                            <div class="regform__plh">Город</div>
                        </div>
                        <div class="regform__inp-error"></div>

                    </div>

                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input
                                    type="tel"
                                    name="telephone"
                                    value=""
                                    class="regform__input "
                                    data-input-change/>
                            <div class="regform__plh">Телефон</div>
                        </div>
                        <div class="regform__inp-error"></div>
                    </div>

                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input
                                    type="text"
                                    name="email"
                                    value=""
                                    class="regform__input "
                                    data-input-change/>
                            <div class="regform__plh">E-mail</div>
                        </div>
                        <div class="regform__inp-error"></div>
                    </div>

                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input
                                    type="text"
                                    name="web"
                                    value=""
                                    class="regform__input "
                                    data-input-change/>
                            <div class="regform__plh">Сайт</div>
                        </div>
                        <div class="regform__inp-error"></div>
                    </div>

                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh regform__select dropdown">
                            <div class="regform__select--text dropdown-toggle" data-toggle="dropdown"
                                 aria-haspopup="true" aria-expanded="false">
                                <span></span>
                                <svg>
                                    <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill">
                                </svg>
                            </div>
                            <div class="regform__plh">Активность в proAV</div>
                            <div class="regform__select--dropdown dropdown-menu">
                                <div class="regform__select--list">
                                    <? foreach ($activities as $activity) : ?>
                                    <label class="regform__select--input">
                                        <input type="radio" name="company_activity" value="<?= $activity ?>">
                                        <span><?= $activity ?></span>
                                    </label>
                                    <? endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="regform__inp-error"></div>
                    </div>

                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh regform__select dropdown">
                            <div class="regform__select--text dropdown-toggle" data-toggle="dropdown"
                                 aria-haspopup="true" aria-expanded="false">
                                <div class="adding__list--product"></div>
                                <svg>
                                    <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill">
                                </svg>
                            </div>
                            <div class="regform__plh">Тег продукции</div>
                            <div id="company_tag_product" class="regform__select--dropdown dropdown-menu">
                                <div class="regform__select--list">

                                    <? foreach ($product_tags as $tag) : ?>
                                    <label class="regform__select--input">
                                        <input class="tag_product" type="checkbox" name="tab"
                                               value="<?= $tag['tag_id'] ?>">
                                        <span><?= $tag['title'] ?></span>
                                    </label>
                                    <? endforeach; ?>

                                </div>
                            </div>
                        </div>
                        <div class="regform__inp-error"></div>
                    </div>

                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh regform__select dropdown">
                            <div class="regform__select--text dropdown-toggle" data-toggle="dropdown"
                                 aria-haspopup="true" aria-expanded="false">
                                <div class="adding__list--industry"></div>
                                <svg>
                                    <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill">
                                </svg>
                            </div>
                            <div class="regform__plh">Тег отрасли</div>
                            <div id="company_tag_industry" class="regform__select--dropdown dropdown-menu">
                                <div class="regform__select--list">

                                    <? foreach ($industry_tags as $tag) : ?>
                                    <label class="regform__select--input">
                                        <input class="tag_industry" type="checkbox" name="tab1"
                                               value="<?= $tag['tag_id'] ?>">
                                        <span><?= $tag['title'] ?></span>
                                    </label>
                                    <? endforeach; ?>

                                </div>
                            </div>
                        </div>
                        <div class="regform__inp-error"></div>
                    </div>


                    <div class="regform__outer col-12 col-md-6 profile__edit">
                    </div>


                    <div class="regform__outer regform__outer-capt col-12" style="margin-top: 0;">
                        Альтернативные названия компании
                    </div>
                    <div class="regform__outer regform__outer-sub col-12">
                        Добавьте альтернативные названия компаний через enter
                    </div>

                    <div class="regform__outer col-12">
                        <div class="regform__inp regform__inp-items">
                            <div class="regform__items">
                                <textarea
                                        type="text"
                                        placeholder="Альтернативное название"
                                        class="regform__items--input"
                                        data-name="alternate[]"
                                        tabindex="-1"></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="regform__outer regform__outer-capt col-12">
                        Описание компании
                    </div>

                    <div class="regform__outer col-12">
                        <div class="regform__inp regform__inp-plh regform__inp-textarea">
                            <div class="regform__textarea regform__textarea-xl" contentEditable="true"
                                 oninput="textareainp(this);"></div>
                            <textarea name="description" data-input-change
                                      class=""></textarea>
                            <div class="regform__plh">Описание вашей компании</div>
                        </div>
                        <div class="regform__inp-error"></div>
                    </div>

                    <div class="regform__outer regform__outer-capt col-12">
                        Заполните реквизиты компании
                    </div>
                    <div class="regform__outer col-12 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input
                                    type="text"
                                    name="inn"
                                    value=""
                                    class="regform__input"
                                    autocomplete="false"
                                    data-input-change/>
                            <div class="regform__plh">ИНН</div>
                        </div>
                        <div class="regform__inp-error"></div>
                    </div>

                    <div class="regform__outer col-12 profile__edit">
                        <div class="regform__inp regform__inp-plh regform__select dropdown">
                            <div class="regform__select--text dropdown-toggle" data-toggle="dropdown"
                                 aria-haspopup="true" aria-expanded="false">
                                <span></span>
                                <svg>
                                    <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill">
                                </svg>
                            </div>
                            <div class="regform__plh">Выбор валюты для счета</div>
                            <div class="regform__select--dropdown dropdown-menu">
                                <div class="regform__select--list">
                                    <label class="regform__select--input">
                                        <input type="radio" name="currency" value="RUB">
                                        <span>RUB</span>
                                    </label>
                                    <label class="regform__select--input">
                                        <input type="radio" name="currency" value="USD">
                                        <span>USD</span>
                                    </label>
                                    <label class="regform__select--input">
                                        <input type="radio" name="currency" value="EUR">
                                        <span>EUR</span>
                                    </label>
                                    <label class="regform__select--input">
                                        <input type="radio" name="currency" value="CNY">
                                        <span>CNY</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="regform__outer col-12 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input
                                    type="text"
                                    name="bank"
                                    value=""
                                    class="regform__input"
                                    autocomplete="false"
                                    data-input-change/>
                            <div class="regform__plh">Название банка</div>
                        </div>
                    </div>
                    <div class="regform__outer col-12 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input
                                    type="text"
                                    name="bik"
                                    value=""
                                    class="regform__input"
                                    autocomplete="false"
                                    data-input-change/>
                            <div class="regform__plh">БИК</div>
                        </div>
                    </div>
                    <div class="regform__outer col-12 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input
                                    type="text"
                                    name="bill"
                                    value=""
                                    class="regform__input"
                                    autocomplete="false"
                                    data-input-change/>
                            <div class="regform__plh">Расчётный счёт</div>
                        </div>
                    </div>
                    <div class="regform__outer col-12 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input
                                    type="text"
                                    name="cor_bill"
                                    value=""
                                    class="regform__input"
                                    autocomplete="false"
                                    data-input-change/>
                            <div class="regform__plh">Корреспондентский счёт</div>
                        </div>
                    </div>
                    <input
                            type="text"
                            name="contact_id"
                            value="<?= $contact_id ?>"
                            class="regform__input"
                            autocomplete="false"
                            hidden/>
                    <div class="regform__btns regform__outer col-12">
                        <button type="submit" class="regform__btn regform__btn-save btn btn-invert">
                            <span>
                                Сохранить изменения
                            </span>
                        </button>
                    </div>

                    <div class="regform__outer regform__warning col-12">
                        <div class="regform__warning--text">Произошла ошибка. Попробуйте повторить попытку позже</div>
                    </div>

                </div>
            </form>

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

<script>
    $(function () {
        let data = {
            data: {

            }
        };

        var selectedProductTags = [];
        var selectedIndustryTags = [];

        $(document).on('change', '.regform__select--dropdown input', function () {
            var select = $(this).closest('.regform__select'),
                input = select.find('input:checked'),
                text = select.find('.regform__select--text');

            text.addClass('valid').find('span').text(input.siblings('span').text()); // использование text() вместо html()
        });


        $(document).on('keydown', '.regform__items--input', function (e) {
            let input = $(this);
            if ((e.keyCode === 13 || e.keyCode === 9)) {
                e.preventDefault();

                if (input.val().length > 0) {
                    let html = '';

                    html += '<div class="regform__items--item">';
                    html += '<span>' + input.val() + '</span>';
                    html += '<input class="company_alternative" type="hidden"	name="' + input.attr('data-name') + '" value="' + input.val() + '" />';
                    html += '<button type="button" class="regform__items--delete"><svg><use xlink:href="catalog/view/theme/avclub/img/sprite.svg#close"></svg></button>';
                    html += '</div>';

                    input.val('').before(html);
                }

                return false;
            }

        });
        $(document).on('click', '.regform__items--delete', function (e) {
            e.preventDefault();
            $(this).closest('.regform__items--item').remove();
        })

        function showLoadingMessage() {
            $('html, body').animate({scrollTop: 0}, 'slow');
            $('#form-edit-company').html(`
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
            $('html, body').animate({scrollTop: 0}, 'slow');
            $('.section_edit').html(`
            <div class="reg__success">
                <div class="container">

                    <div class="reg__success--inner">
                        <div class="reg__success--title">
                            Заявка на размещение <br>
                            профайла прошла успешно
                        </div>
                        <div class="reg__success--btn">
                            <a href="/account/" class="btn btn-invert">
                                Вернуться в личный кабинет
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        `);
        }

        $(document).on('click', '#edit-image-save', function (e) {
            e.preventDefault();

            let fileInput = $('input[type="file"]');
            let file = fileInput.prop('files')[0];
            let formData = new FormData();
            formData.append('file', file);
            console.log(file)

            $.ajax({
                type: 'POST',
                url: 'index.php?route=register/company/uploadImage',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    data.data.company_initial_logo = response.url;
                },
                error: function (error) {
                    console.log(error);
                }
            });
        })

        // Обработчик для чекбоксов тегов продукции
        $('.tag_product').change(function() {
            updateSelectedTags('product');
            console.log(selectedProductTags)
        });

        // Обработчик для чекбоксов тегов отрасли
        $('.tag_industry').change(function() {
            updateSelectedTags('industry');
            console.log(selectedIndustryTags)
        });

        // Функция для обновления выбранных тегов и их отображения
        function updateSelectedTags(type) {
            var selectedTags = [];
            var selectedTagsText = [];
            var selector = type === 'product' ? '.tag_product' : '.tag_industry';

            $(selector + ':checked').each(function() {
                var tagId = +$(this).val();
                var tagName = $(this).next('span').text();
                selectedTags.push(tagId);
                selectedTagsText.push(tagName);
                if (type === 'product') {
                    $('.regform__select--text').find('.adding__list--product').html(selectedTagsText + '</br>');
                } else {
                    $('.regform__select--text').find('.adding__list--industry').html(selectedTagsText + '</br>');
                }

            });

            var $dropdown = $('#company_tag_' + type);
            var $selectedText = $dropdown.prev('.regform__select--text').find('span');
            //
            // var addingHTML = [];
            // for ( var i=0; i < selectedTagsText.length; i++ ) {
            //     addingHTML.push ('<span style="background: red">' + selectedTagsText[i] + '</span>');
            // }
            // console.log(addingHTML);
            // $('.regform__select--text').find('.adding__list').html(addingHTML.join(", "));
            //
            // console.log(selectedTagsText)

            // Передача выбранных тегов в массив
            if (type === 'product') {
                selectedProductTags = selectedTags;
            } else {
                selectedIndustryTags = selectedTags;
            }
        }


        $(document).on('submit', '#form-edit-company', function (e) {
            e.preventDefault();

            var companyName = $("input[name='name']").val();
            var companyCity = $("input[name='city']").val();
            var companyPhone = $("input[name='telephone']").val();
            var companyEmail = $("input[name='email']").val();
            var companyWeb = $("input[name='web']").val();
            var companyActivity = $("input[name='company_activity']:checked").val();
            var companyTagProd = $("#company_tag_product .tag_product:checked").val();
            var companyTagIndustry = $("#company_tag_industry .tag_industry:checked").val();
            var companyInn = $("input[name='inn']").val();
            var companyDescription = $("textarea[name='description']").val();

            if (!data.data.company_initial_logo) {
                $('.edit__image--image').css("border-color", "red");
                $('.edit__image').find('.regform__inp-error').text('Добавьте логотип').css('color', 'red');
            } else {
                $('.edit__image--image').css("border-color", "#c4c4c4");
                $('.edit__image').find('.regform__inp-error').text('');
            }

            if (!companyName) {
                $("input[name='name']").closest('.regform__inp').addClass('invalid');
                $("input[name='name']").closest('.regform__outer').find('.regform__inp-error').text('Введите название компании').css('color', 'red');
            } else {
                $("input[name='name']").closest('.regform__inp').removeClass('invalid');
                $("input[name='name']").closest('.regform__outer').find('.regform__inp-error').text('');
            }

            if (!companyDescription) {
                $("textarea[name='description']").closest('.regform__inp').addClass('invalid');
                $("textarea[name='description']").closest('.regform__outer').find('.regform__inp-error').text('Введите описание компании').css('color', 'red');
            } else {
                $("textarea[name='description']").closest('.regform__inp').removeClass('invalid');
                $("textarea[name='description']").closest('.regform__outer').find('.regform__inp-error').text('');
            }

            if (!companyCity) {
                $("input[name='city']").closest('.regform__inp').addClass('invalid');
                $("input[name='city']").closest('.regform__outer').find('.regform__inp-error').text('Введите название города').css('color', 'red');
            } else {
                $("input[name='city']").closest('.regform__inp').removeClass('invalid');
                $("input[name='city']").closest('.regform__outer').find('.regform__inp-error').text('');
            }

            if (!companyPhone) {
                $("input[name='telephone']").closest('.regform__inp').addClass('invalid');
                $("input[name='telephone']").closest('.regform__outer').find('.regform__inp-error').text('Введите телефон').css('color', 'red');
            } else {
                $("input[name='telephone']").closest('.regform__inp').removeClass('invalid');
                $("input[name='telephone']").closest('.regform__outer').find('.regform__inp-error').text('');
            }

            if (!companyEmail) {
                $("input[name='email']").closest('.regform__inp').addClass('invalid');
                $("input[name='email']").closest('.regform__outer').find('.regform__inp-error').text('Введите email').css('color', 'red');
            } else {
                $("input[name='email']").closest('.regform__inp').removeClass('invalid');
                $("input[name='email']").closest('.regform__outer').find('.regform__inp-error').text('');
            }

            if (!companyWeb) {
                $("input[name='web']").closest('.regform__inp').addClass('invalid');
                $("input[name='web']").closest('.regform__outer').find('.regform__inp-error').text('Введите название сайта').css('color', 'red');
            } else {
                $("input[name='web']").closest('.regform__inp').removeClass('invalid');
                $("input[name='web']").closest('.regform__outer').find('.regform__inp-error').text('');
            }

            if (!companyActivity) {
                $("input[name='company_activity']").closest('.regform__inp').addClass('invalid');
                $("input[name='company_activity']").closest('.regform__outer').find('.regform__inp-error').text('Выберите активность').css('color', 'red');
            } else {
                $("input[name='company_activity']").closest('.regform__inp').removeClass('invalid');
                $("input[name='company_activity']").closest('.regform__outer').find('.regform__inp-error').text('');
            }

            if (!companyTagProd) {
                $("#company_tag_product").closest('.regform__inp').addClass('invalid');
                $("#company_tag_product").closest('.regform__outer').find('.regform__inp-error').text('Выберите тег продукции').css('color', 'red');
            } else {
                $("#company_tag_product").closest('.regform__inp').removeClass('invalid');
                $("#company_tag_product").closest('.regform__outer').find('.regform__inp-error').text('');
            }

            if (!companyTagIndustry) {
                $("#company_tag_industry").closest('.regform__inp').addClass('invalid');
                $("#company_tag_industry").closest('.regform__outer').find('.regform__inp-error').text('Выберите тег отрасли').css('color', 'red');
            } else {
                $("#company_tag_industry").closest('.regform__inp').removeClass('invalid');
                $("#company_tag_industry").closest('.regform__outer').find('.regform__inp-error').text('');
            }

            if (!companyInn) {
                $("input[name='inn']").closest('.regform__inp').addClass('invalid');
                $("input[name='inn']").closest('.regform__outer').find('.regform__inp-error').text('Заполните реквизиты').css('color', 'red');
            } else {
                $("input[name='inn']").closest('.regform__inp').removeClass('invalid');
                $("input[name='inn']").closest('.regform__outer').find('.regform__inp-error').text('');
            }

            if (!companyName ||
                !companyCity ||
                !companyPhone ||
                !companyEmail ||
                !data.data.company_initial_logo ||
                !companyWeb ||
                !companyActivity ||
                !companyTagProd ||
                !companyTagIndustry ||
                !companyInn ||
                !companyDescription
            ) {
                if (!companyInn) {
                    $("html, body").animate({
                        scrollTop: $("textarea[name='description']").closest('.regform__outer').offset().top
                    });
                    return
                } else {
                    $("html, body").animate({
                        scrollTop: $(".section_edit").offset().top
                    });
                }

                return;
            }

            const alternativeNames = [];
            $(".company_alternative").each(function () {
                alternativeNames.push($(this).val());
            });

            data.data.company_name = $("input[name='name']").val()
            data.data.company_site = $("input[name='web']").val()
            data.data.company_phone = $("input[name='telephone']").val()
            data.data.company_email = $("input[name='email']").val()
            data.data.company_activity = [$("input[name='company_activity']:checked").val()]
            data.data.company_description = $("textarea[name='description']").val()
            data.data.company_city = $("input[name='city']").val()
            data.data.company_tag_product = selectedProductTags
            data.data.company_tag_industry = selectedIndustryTags
            data.data.company_alternative_names = alternativeNames
            data.data.currency = $("input[name='currency']:checked").val() ?? 'RUB'
            data.data.company_inn = +$("input[name='inn']").val()
            data.data.company_bank_name = $("input[name='bank']").val()
            data.data.company_bik = $("input[name='bik']").val()
            data.data.company_acc_num = $("input[name='bill']").val()
            data.data.company_cor_acc_num = $("input[name='cor_bill']").val()
            data.data.contact = $("input[name='contact']").val()
            data.data.contact_id = +$("input[name='contact_id']").val()
            data.expert_id = +'<?php echo $expert_id; ?>'

            console.log(data)

            $.ajax({
                type: "POST",
                url: "index.php?route=register/company/addCompanyProfile",
                dataType: "json",
                data: data,
                beforeSend: function (json) {
                    showLoadingMessage();
                },
                complete: function (json) {
                },
                success: function (json) {
                    console.log(json['code'])
                    if (json['code'] === 200) {
                        showMessage('Заявка на размещение<br>профайла отправлена');
                    } else if (json['error'] && json['code'] === 400) {
                        showMessage('Ошибка размещения<br> профайла. Не все поля заполнены');
                    } else if (json['error']) {
                        showMessage('Ошибка размещения<br> профайла');
                    }
                },
                error: function (json) {
                    showMessage('Ошибка размещения<br> профайла');
                    console.log('profile addCompanyProfile error', json);
                }
            });
        });

    })
</script>

<div class="d-none">
    <div id="modal-edit-image" class="imgedit imgedit-company">
        <button type="button" class="modal__close" data-fancybox-close>
            <svg class="ico ico-center">
                <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#close"></use>
            </svg>
        </button>
        <div class="imgedit__title">Добавить логотип</div>
        <div class="imgedit__text">
            Рекомендуем использовать изображение размером не менее 168 х 73 пикселей в формате PNG или JPG.
        </div>
        <div class="imgedit__photo">
            <div id="upload-demo"></div>
        </div>
        <div class="imgedit__btns imgedit__btns-upload">
            <a type="button" class="btn btn-red imgedit__btn imgedit__btn-upload" id="edit-image-upload">
                Загрузить логотип
                <input type="file" accept="image/png,image/jpeg" id="edit-image-file">
            </a>
            <button type="button" class="btn btn-red btn-red-invert imgedit__btn" id="edit-image-delete">
                Удалить логотип
            </button>
        </div>

        <div class="imgedit__btns imgedit__btns-save">
            <button type="button" class="btn btn-red imgedit__btn" id="edit-image-save">
                Кадрировать и сохранить
            </button>
            <button type="button" class="btn btn-red btn-red-invert imgedit__btn" id="edit-image-cancel">
                Отмена
            </button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo $theme_dir; ?>/libs/croppie/croppie.css">
<script src="<?php echo $theme_dir; ?>/libs/croppie/croppie.min.js"></script>

<script src="<?php echo $theme_dir; ?>/js/addcompany.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/addcompany.js') ?>"></script>
<?php echo $footer; ?>