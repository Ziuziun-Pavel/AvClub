<style>
    .regform__inp.noedit {
        border: none;
    }

    .regform__label {
        margin-right: 1px;
    }

    .regform__input {
        padding: 0 10px 0 6px;
    }

</style>

<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_attention.tpl'); ?>
<?php
  if ($register_exists && is_bool($register_exists) || $register_exists['registration_id']) { ?>

<div class="regdata__text">Регистрация недоступна. Пользователь уже зарегистрирован.</div>

<? } else { ?>
<div class="regdata__title">Проверьте, правильны ли ваши персональные данные</div>

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

    <form class="regform row">

        <div class="regform__outer col-12">
            <div class="regform__inp noedit">
                <div class="regform__label">Email: </div>
                <input type="text" name="email" value="<?php echo $user['email']; ?>" class="regform__input" readonly/>
            </div>
        </div>

        <div class="regform__outer col-12">
            <div class="regform__inp noedit">
                <div class="regform__label">Должность: </div>
                <input type="text" name="post" value="<?php echo $user['post']; ?>" class="regform__input" readonly/>
            </div>
        </div>

        <div class="regform__outer col-12">
            <div class="regform__inp noedit">
                <div class="regform__label">Компания: </div>
                <input type="text" name="company" value='<?php echo $user["company"]; ?>' class="regform__input"
                       readonly/>
            </div>
            <div class="error-message" style="color:red; display: none;">Нужно выбрать компанию. Нажмите кнопку "Изменить данные"</div>
        </div>

        <div class="regform__btns col-12">
            <div class="row">
                <div class="regform__outer regform__outer-btn col-12 col-md-6">
                    <button type="button" class="regform__btn regform__btn-edit btn btn-red" id="button-change">
                        <svg class="ico">
                            <use xlink:href="#edit"/>
                        </svg>
                        <span>
								Изменить данные
							</span>
                    </button>
                </div>

                <div class="regform__outer regform__outer-btn col-12 col-md-6">
                    <button type="button" class="regform__btn regform__btn-next btn btn-invert" id="button-register">
                        <span><?php echo $button_register; ?></span>
                        <svg class="ico">
                            <use xlink:href="#arrow-right"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>

        <input type="hidden" name="sid" value="<?php echo $session; ?>">
    </form>

</div>
<?php } ?>
<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_fail.tpl'); ?>