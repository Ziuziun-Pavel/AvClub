<?php echo $header; ?>
<?php echo $content_top; ?>
<div class="reg__load">
    <div class="cssload-clock"></div>
</div>
<section class="section_edit">
    <div class="container">

        <div class="edit__row row">

            <form id="form-edit-expert" class="edit__content col-xl-9">

                <div class="edit__back">
                    <a href="<?php echo $back; ?>" class="link__outer">
                        <svg class="ico">
                            <use xlink:href="#long-arrow-left">
                        </svg>
                        <span><span class="link">Назад</span></span>
                    </a>
                </div>
                <?php if($alternate_count) { ?>
                <div class="edit__attention">
                    <svg class="icow">
                        <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#attention">
                    </svg>
                    Ваши данные находятся на модерации
                </div>
                <?php } ?>
                <div class="edit__image">
                    <a href="#modal-edit-image" class="edit__image--image">
                        <img src="<?php echo $image; ?>" alt="" id="edit-image"
                             data-placeholder="<?php echo $placeholder; ?>">
                        <svg class="ico ico-center">
                            <use xlink:href="#photo">
                        </svg>
                    </a>
                    <textarea name="photo"></textarea>
                </div>

                <div class="edit__inputs row">

                    <div class="regform__outer regform__outer-capt col-12">
                        Профиль
                    </div>
                    <div class="regform__outer regform__outer-sub col-12">
                        <div class="regform__edit">
                            <svg class="ico">
                                <use xlink:href="#edit"/>
                            </svg>
                            Для корректировки данных кликните на соответствующее поле
                        </div>
                    </div>
                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input type="text" name="name" value="<?php echo $user['name']; ?>"
                                   class="regform__input <?php echo $user['name'] ? 'valid' : ''; ?>"
                                   autocomplete="false" data-input-change/>
                            <div class="regform__plh">Имя</div>
                        </div>
                    </div>

                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>"
                                   class="regform__input <?php echo $user['lastname'] ? 'valid' : ''; ?>"
                                   data-input-change/>
                            <div class="regform__plh">Фамилия</div>
                        </div>
                    </div>

                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input type="text" name="email" value="<?php echo $user['email']; ?>"
                                   class="regform__input  <?php echo $user['email'] ? 'valid' : ''; ?>"
                                   data-input-change/>
                            <div class="regform__plh">E-mail</div>
                        </div>
                    </div>

                    <div class="regform__outer col-12 col-md-6 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input type="tel" name="telephone" value="<?php echo $user['telephone']; ?>"
                                   class="regform__input  <?php echo $user['telephone'] ? 'valid' : ''; ?>"
                                   data-input-change/>
                            <div class="regform__plh">Телефон</div>
                        </div>
                    </div>

                    <div class="regform__outer col-12 profile__edit">
                        <div class="regform__inp regform__inp-plh">
                            <input type="text" name="post" value="<?php echo $user['exp']; ?>"
                                   class="regform__input <?php echo $user['exp'] ? 'valid' : ''; ?>" data-input-change/>
                            <div class="regform__plh">Должность</div>
                        </div>
                    </div>


                    <div class="regbrand col-12">
                        <?php echo $company_template;  ?>
                    </div>
                    <?php /* ?>
                    <div class="regform__outer regform__outer-capt col-12">
                        Компания
                    </div>
                    <div class="regform__outer col-12">
                        <div class="regform__inp regform__inp-plh">
                            <input type="text" name="company" value="<?php echo $user['company']; ?>"
                                   class="regform__input <?php echo $user['company'] ? 'valid' : ''; ?>"
                                   autocomplete="false" data-input-change/>
                            <div class="regform__find">+ Найти</div>
                            <div class="regform__plh">Компания</div>
                        </div>
                    </div>

                    <div class="regcompany col-12 "
                         style="<?php echo !empty($user['company_status']) && $user['company_status'] === 'new' ? 'display:block;' : ''; ?>">
                        <div class="regcompany__row row">

                            <div class="regform__outer col-12">
                                <div class="regform__inp regform__inp-plh">
                                    <input type="text" name="city" value="<?php echo $user['city']; ?>"
                                           class="regform__input" data-input-change/>
                                    <div class="regform__plh">Город работы</div>
                                </div>
                            </div>
                            <div class="regform__outer company--new col-12">
                                <div class="regform__inp regform__inp-plh">
                                    <input type="tel" name="company_phone" value="<?php echo $user['company_phone']; ?>"
                                           class="regform__input" autocomplete="false" data-input-change/>
                                    <div class="regform__plh">Телефон компании</div>
                                </div>
                            </div>
                            <div class="regform__outer company--new col-12">
                                <div class="regform__inp regform__inp-plh">
                                    <input type="text" name="company_site" value="<?php echo $user['company_site']; ?>"
                                           class="regform__input" autocomplete="false" data-input-change/>
                                    <div class="regform__plh">Сайт компании</div>
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
                                Основная деятельность компании в области профессиональных аудиовизуальных технологий
                                (proAV)
                            </div>
                            <div class="regform__outer company--new col-12">
                                <div class="regcompact__list ">
                                    <?php foreach($activity as $item) { ?>
                                    <label class="regcompact__item">
                                        <input type="checkbox" name="company_activity[]" value="<?php echo $item; ?>">
                                        <span><?php echo $item; ?></span>
                                    </label>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php */ ?>

                    <div class="regform__outer regform__outer-capt col-12">
                        Информация о себе
                    </div>

                    <div class="regform__outer col-12 d-none">
                        <div class="regform__inp regform__inp-plh regform__inp-textarea">
                            <div class="regform__textarea" contentEditable="true"
                                 oninput="textareainp(this);"><?php echo $user['expertise']; ?></div>
                            <textarea name="expertise" data-input-change
                                      class="<?php echo $user['expertise'] ? 'valid' : ''; ?>"><?php echo $user['expertise']; ?></textarea>
                            <div class="regform__plh">Экспертиза</div>
                        </div>
                    </div>

                    <div class="regform__outer col-12 profile__edit">
                        <div class="regform__inp  regform__inp-plh regform__inp-textarea">
                            <div class="regform__textarea" contentEditable="true"
                                 oninput="textareainp(this);"><?php echo $user['useful']; ?></div>
                            <textarea name="useful" data-input-change
                                      class="<?php echo $user['useful'] ? 'valid' : ''; ?>"><?php echo $user['useful']; ?></textarea>
                            <div class="regform__plh">В чем могу быть полезен</div>
                        </div>
                    </div>

                    <div class="regform__outer col-12 profile__edit">
                        <div class="regform__inp  regform__inp-plh regform__inp-textarea">
                            <div class="regform__textarea" contentEditable="true"
                                 oninput="textareainp(this);"><?php echo $user['regalia']; ?></div>
                            <textarea name="regalia" data-input-change
                                      class="<?php echo $user['regalia'] ? 'valid' : ''; ?>"><?php echo $user['regalia']; ?></textarea>
                            <div class="regform__plh">Заслуги и регалии</div>
                        </div>
                    </div>

                    <div class="regform__btns regform__outer col-12">
                        <button type="button" class="regform__btn regform__btn-save btn btn-red" id="button-edit-save"
                                disabled>
								<span>
									Сохранить изменения
								</span>
                        </button>
                    </div>

                    <div class="regform__outer regform__warning col-12">
                        <div class="regform__warning--text">Произошла ошибка. Попробуйте повторить попытку позже</div>
                    </div>


                    <?php /* ?>
                    <input type="hidden" name="company_status"
                           value="<?php echo !empty($user['company_status']) ? $user['company_status'] : ''; ?>"/>
                    <input type="hidden" name="b24_company_id" value="<?php echo $user['b24_company_id']; ?>"/>
                    <?php */ ?>

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

<div class="d-none">
    <div id="modal-edit-image" class="imgedit">
        <button type="button" class="modal__close" data-fancybox-close>
            <svg class="ico ico-center">
                <use xlink:href="#close"></use>
            </svg>
        </button>
        <div class="imgedit__title">Изменить фото профиля</div>
        <div class="imgedit__photo">
            <div id="upload-demo"></div>
        </div>
        <div class="imgedit__btns imgedit__btns-upload">
            <a type="button" class="btn btn-red imgedit__btn imgedit__btn-upload" id="edit-image-upload">
                Загрузить фото
                <input type="file" accept="image/png,image/jpeg" id="edit-image-file">
            </a>
            <button type="button" class="btn btn-red btn-red-invert imgedit__btn" id="edit-image-delete">
                Удалить фото
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

<script src="<?php echo $theme_dir; ?>/js/register-main.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register-main.js') ?>"></script>
<script src="<?php echo $theme_dir; ?>/js/register-edit.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register-edit.js') ?>"></script>
<script src="<?php echo $theme_dir; ?>/js/register-brand.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register-brand.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/css/suggestions.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/js/jquery.suggestions.min.js"></script>
<?php require(DIR_TEMPLATE . 'avclub/template/register/script-change.tpl'); ?>

<?php echo $footer; ?>