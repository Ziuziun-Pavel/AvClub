<style>
    .regform__name-wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .regform__block {
        position: relative;
        padding: 1rem;
        margin-bottom: 2rem;
        border: 1px solid #b5a0a0;
        border-radius: 1rem;
    }

    .regform__block--label {
        position: absolute;
        font-size: 17px;
        font-weight: 600;
        top: -11px;
        left: 26px;
        background: #fff;
        padding: 0 11px;
    }

</style>

<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_attention.tpl'); ?>
<div class="regdata__title"><?php echo html_entity_decode($title); ?></div>

<div class="regdata__form">

    <div class="reguser">
        <div class="reguser__photo">
            <img src="<?php echo $user['avatar']; ?>" alt="">
        </div>
        <?php if(!empty($show_name)) { ?>
        <div class="reguser__name"><?php echo $user['name'] . ' ' . $user['lastname']; ?></div>
        <?php } ?>
        <div class="reguser__phone"><?php echo $phone; ?></div>
        <?php if(!empty($show_notme)) { ?>
        <div class="reguser__notme">
            <a href="#" class="link link_under" id="button-notme">Это не я</a>
        </div>
        <?php } ?>
    </div>

    <form class="regform row" id="register-newuser">
        <div class="regform__block col-12">
            <?php if(!empty($show_name_fields)) { ?>
            <div class="regform__name-wrapper">
                <div class="regform__outer col-12 col-md-6">
                    <div class="regform__inp">
                        <input type="text" name="name" value="<?php echo $user['name']; ?>"
                               class="regform__input required "
                               placeholder="Имя"/>
                    </div>
                    <div class="regform__inp-error"></div>
                </div>

                <div class="regform__outer col-12 col-md-6">
                    <div class="regform__inp">
                        <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>"
                               class="regform__input required " placeholder="Фамилия"/>
                    </div>
                    <div class="regform__inp-error"></div>
                </div>
            </div>
            <?php } ?>

            <div class="regform__outer col-12">
                <div class="regform__inp">
                    <input type="text" name="email" value="<?php echo $user['email']; ?>"
                           class="regform__input required "/>
                    <div class="regform__label ">Email</div>
                </div>
                <div class="regform__inp-error"></div>
            </div>

            <div class="regform__outer col-12">
                <div class="regform__inp">
                    <input type="text" name="post" value="<?php echo $user['post']; ?>"
                           class="regform__input required "/>
                    <div class="regform__label ">Должность</div>
                </div>
                <div class="regform__inp-error"></div>
            </div>

            <?php

                $degries = [
                    3077 => "Окончательное решение",
                    3079 => "Оказывающее влияние",
                    3081 => "Не берётся во внимание",
                    3083 => "Другое"
                ];

                $groups = [
                    3065 => "Высший руководящий состав",
                    3067 => "Руководящий состав",
                    3069 => "Инженерный/технический состав",
                    3071 => "Менеджеры проектов",
                    3073 => "Фрилансер",
                    3075 => "Другое"
                ];

            ?>

            <div class="regform__outer col-12 col-md-12">
                <div class="regform__inp regform__inp-plh regform__select dropdown">
                    <div class="regform__select--text dropdown-toggle <?= isset($user['degree']) ? 'valid' : '' ?>" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false">
                        <span><?= $degries[$user['degree']] ?></span>
                        <svg>
                            <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill">
                        </svg>
                    </div>
                    <div class="regform__plh">Cтепень влияния на покупку оборудования</div>
                    <div class="regform__select--dropdown dropdown-menu">
                        <div class="regform__select--list">
                            <? foreach ($degries as $key => $degree) : ?>
                            <label class="regform__select--input">
                                <input type="radio" name="degree" value="<?= $degree ?>" <?= ($key == $user['degree']) ? 'checked' : '' ?>">
                                <span><?= $degree ?></span>
                            </label>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="regform__inp-error"></div>
            </div>

            <div class="regform__outer col-12 col-md-12">
                <div class="regform__inp regform__inp-plh regform__select dropdown">
                    <div class="regform__select--text dropdown-toggle <?= isset($user['group']) ? 'valid' : '' ?>" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false">
                        <span><?= $groups[$user['group']] ?></span>
                        <svg>
                            <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill">
                        </svg>
                    </div>
                    <div class="regform__plh">Группа профессиональной деятельности</div>
                    <div class="regform__select--dropdown dropdown-menu">
                        <div class="regform__select--list">
                            <? foreach ($groups as $key => $group) : ?>
                                <label class="regform__select--input">
                                    <input type="radio" name="group" value="<?= $group ?>" <?= ($key == $user['group']) ? 'checked' : '' ?>>
                                    <span><?= $group ?></span>
                                </label>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="regform__inp-error"></div>
            </div>


            <div class="regform__block--label">Контактные данные</div>

            <div class="regform__outer col-12" style="display: flex;align-items: center;">
                <img src="catalog/view/theme/avclub/images/required.svg" alt="required">
                <span>- обязательное поле</span>
            </div>
        </div>
        <?php
                $types = [
                    "Поставщик AV решений",
                    "Заказчик AV решений"
                ];

                $spheries = [
                    "Информационные технологии - IT",
                    "Сельское, лесное хозяйства, охота и рыболовство",
                    "Добывающая промышленность",
                    "Обрабатывающая промышленность",
                    "Строительство ",
                    "Электричество, водоснабжение и газ ",
                    "Торговля: оптовая и розничная",
                    "Транспорт и логистика",
                    "Медицина",
                    "Образование и наука",
                    "Гостиницы и рестораны",
                    "Финансы",
                    "Государственная служба",
                    "Музеи и выставочные пространства",
                    "Театры, ДК и филармонии",
                    "Рестораны, клубы и развлекательные заведения",
                    "Телерадиовещание",
                    "АВ-индустрия",
                    "Телекоммуникации и связь",
                    "Производство",
                    "Банковские услуги",
                    "Консалтинг",
                    "Доставка",
                    "Развлечения",
                    "Другое"
                ];

            ?>
        <div class="regform__block col-12 company__block">

            <div class="regbrand col-12">
                <?php echo $company_template; ?>
            </div>

            <div class="regform__outer col-12 col-md-12" style="margin-top: 1rem;">
                <div class="regform__inp regform__inp-plh regform__select dropdown">
                    <div class="regform__select--text dropdown-toggle <?= isset($user['company_type']) ? 'valid' : '' ?>" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false">
                        <span><?= $user['company_type'] ?></span>
                        <svg>
                            <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill">
                        </svg>
                    </div>
                    <div class="regform__plh">Тип компании</div>
                    <div class="regform__select--dropdown dropdown-menu">
                        <div class="regform__select--list">
                            <? foreach ($types as $type) : ?>
                            <label class="regform__select--input">
                                <input type="radio" name="type" value="<?= $type ?>" <?= ($type === $user['company_type']) ? 'checked' : '' ?>>
                                <span><?= $type ?></span>
                            </label>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="regform__outer col-12 col-md-12">
                <div class="regform__inp regform__inp-plh regform__select dropdown">
                    <div class="regform__select--text dropdown-toggle <?= isset($user['company_sphere']) ? 'valid' : '' ?>" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false">
                        <span><?= $user['company_sphere'] ?></span>
                        <svg>
                            <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill">
                        </svg>
                    </div>
                    <div class="regform__plh">Сфера деятельности</div>
                    <div class="regform__select--dropdown dropdown-menu">
                        <div class="regform__select--list">
                            <? foreach ($spheries as $sphere) : ?>
                            <label class="regform__select--input">
                                <input type="radio" name="sphere" value="<?= $sphere ?>" <?= ($sphere === $user['company_sphere']) ? 'checked' : '' ?>>
                                <span><?= $sphere ?></span>
                            </label>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="regform__block--label">Данные компании</div>
            <div class="regform__inp-error"></div>
        </div>

        <?php /* ?>
        <div class="regform__outer regform__outer-capt col-12">
            Компания
        </div>
        <div class="regform__outer col-12">
            <div class="regform__inp">
                <input type="text" name="company" value="<?php echo $user['company']; ?>" class="regform__input"
                       autocomplete="false"/>
                <div class="regform__find">+ Найти</div>
            </div>
        </div>

        <div class="regcompany col-12 "
             style="<?php echo !empty($user['company_status']) && $user['company_status'] === 'new' ? 'display:block;' : ''; ?>">
            <div class="regcompany__row row">

                <div class="regform__outer col-12">
                    <div class="regform__inp">
                        <input type="text" name="city" value="<?php echo $user['city']; ?>" class="regform__input"
                               placeholder="Город работы"/>
                    </div>
                    <div class="regform__inp-error"></div>
                </div>
                <div class="regform__outer company--new col-12">
                    <div class="regform__inp">
                        <input type="tel" name="company_phone" value="<?php echo $user['company_phone']; ?>"
                               class="regform__input" autocomplete="false" placeholder="Телефон компании"/>
                    </div>
                </div>
                <div class="regform__outer company--new col-12">
                    <div class="regform__inp">
                        <input type="text" name="company_site" value="<?php echo $user['company_site']; ?>"
                               class="regform__input" autocomplete="false" placeholder="Сайт компании"/>
                    </div>
                    <div class="regform__inp-error"></div>
                </div>

                <?php
				$activity = array(
					'Производитель',
					'Системная интеграция',
					'Поставка и дистрибуция AV-оборудования',
					'Установка и обслуживание',
					'Прокат, аренда аудиовизуальных систем',
					'Пользователь AV-решений',
					'Другое'
				);
				?>
                <div class="regform__outer regform__outer-capt regcompact__title col-12">
                    Основная деятельность компании в области профессиональных аудиовизуальных технологий (proAV)
                </div>
                <div class="regform__outer company--new col-12">
                    <div class="regcompact__list ">
                        <?php foreach($activity as $item) { ?>
                        <?php $active_act = !empty($user['company_activity']) && in_array($item, $user['company_activity']) ? true : false; ?>
                        <label class="regcompact__item">
                            <input type="checkbox" name="company_activity[]"
                                   value="<?php echo $item; ?>" <?php echo $active_act ? 'checked' : ''; ?>>
                            <span><?php echo $item; ?></span>
                        </label>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php */ ?>

        <div class="regpromo">
            <div class="regdata__title" style="margin-bottom: 1rem">
                Введите промокод
            </div>
            <div class="regform__outer col-12">
                <div class="regpromo__inp">
                    <input type="text" name="promo" class="regpromo__input" value="" placeholder="Введите промокод"
                           style="flex: 0 0 calc(56% - 5px);max-width: calc(56% - 5px);"/>
                </div>
                <div class="regform__inp-error"></div>
            </div>
            <div class="regpromo__text" style="margin-top: 1rem;margin-bottom: 1rem">
                Ввод промокода добавляет баллы на ваш личный счёт,
                которые Вы можете потратить на активности в AV клубе.
            </div>

        </div>

        <div class="regdata__title" style="margin-bottom: 1rem">
            Введите проверочный код
        </div>

        <div class="regform__outer col-12">
            <div class="regphone__inp">
                <input type="tel" name="code" class="regphone__input" value="" placeholder="————"
                       style="flex: 0 0 calc(56% - 5px);max-width: calc(56% - 5px);"/>
            </div>
            <div class="regform__inp-error"></div>
        </div>


        <div class="regform__btns col-12">
            <div class="row">
                <div class="regform__outer regform__outer-btn col-12 col-md-12">
                    <button type="button" class="regform__btn regform__btn-save btn btn-red" id="register-user">
						<span>
							Зарегистрировать пользователя
						</span>
                    </button>
                </div>

            </div>

        </div>

        <?php /* ?>
        <input type="hidden" name="user_fields_changed" value="false">

        <input type="hidden" name="company_status"
               value="<?php echo !empty($user['company_status']) ? $user['company_status'] : ''; ?>"/>
        <input type="hidden" name="b24_company_id" value="<?php echo $user['b24_company_id']; ?>"/>
        <?php */ ?>

        <input type="hidden" name="sid" value="<?php echo $session; ?>">
    </form>

</div>
<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_fail.tpl'); ?>


<?php // require(DIR_TEMPLATE . 'avclub/template/register/script-change.tpl'); ?>

<script>
    $(function () {
        $('.regphone__inp input[name="code"]').inputmask("9999", {"placeholder": "—"});
    })

    var userFieldsChanged = false;
    var companyChanged = false;

    $('#register-newuser input[name="name"], #register-newuser input[name="lastname"], #register-newuser input[name="email"], #register-newuser input[name="post"]').on('change', function () {
        userFieldsChanged = true;
    });

    $(document).on('click', '.regbrand--choose', function (e) {
        companyChanged = true;
    })

    $(document).on('click', '#regbrand-add', function (e) {
        companyChanged = true;
    })


    function toggleDropdown(element) {
        $(element).siblings('.regform__select--dropdown').toggleClass('show');
    }

    $('.regform__select--text').click(function() {
        toggleDropdown(this);
    });

    $('.regform__select--input input[type="radio"]').change(function() {
        var selectedText = $(this).siblings('span').text();
        var $textContainer = $(this).closest('.regform__select').find('.regform__select--text span');
        $textContainer.text(selectedText);
        $(this).closest('.regform__select').find('.regform__select--text').addClass('valid'); // добавляем класс valid
        toggleDropdown($(this).closest('.regform__select').find('.regform__select--text'));
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('.regform__select').length) {
            $('.regform__select--dropdown').removeClass('show');
        }
    });



</script>