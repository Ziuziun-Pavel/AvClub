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
                </div>

                <div class="regform__outer col-12 col-md-6">
                    <div class="regform__inp">
                        <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>"
                               class="regform__input required " placeholder="Фамилия"/>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="regform__outer col-12">
                <div class="regform__inp">
                    <input type="text" name="email" value="<?php echo $user['email']; ?>"
                           class="regform__input required "/>
                    <div class="regform__label ">Email</div>
                </div>
            </div>

            <div class="regform__outer col-12">
                <div class="regform__inp">
                    <input type="text" name="post" value="<?php echo $user['post']; ?>"
                           class="regform__input required "/>
                    <div class="regform__label ">Должность</div>
                </div>
            </div>

            <div class="regform__block--label">Контактные данные</div>

            <div class="regform__outer col-12" style="display: flex;align-items: center;">
                <img src="catalog/view/theme/avclub/images/required.svg" alt="required">
                <span>- обязательное поле</span>
            </div>
        </div>

        <div class="regform__block col-12">

            <div class="regbrand col-12">
                <?php echo $company_template; ?>
            </div>
            <div class="regform__block--label">Данные компании</div>
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

        <div class="regform__btns col-12">
            <div class="row">
                <div class="regform__outer regform__outer-btn col-12 col-md-6">
                    <button type="button" class="regform__btn regform__btn-save btn btn-red" id="button-save"
                            disabled="<?= $save_btn_disabled ?>">
						<span>
							Сохранить изменения
						</span>
                    </button>
                </div>

            </div>

        </div>

        <?php /* ?>
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
    var userFieldsChanged = false;

    $('#register-newuser .regform__input').on('input', function () {
        userFieldsChanged = true;
    });

    // $('#register-newuser .regform__select--dropdown').on('click', function() {
    // 	formChanged = true;
    // });
</script>