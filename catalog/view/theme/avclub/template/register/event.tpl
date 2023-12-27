<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<?php echo $header; ?>
<div class="reg__load">
    <div class="cssload-clock"></div>
</div>
<section class="section_register">
    <div class="container">

        <div class="regcont">

            <?php
            if ($event_info['isClosed']) { ?>
            <div class="reginfo">
                <div class="reginfo__logo">
                    <img src="catalog/view/theme/avclub/images/logo-register.svg" alt="">
                </div>
                <div class="reginfo__name">
                    <?php echo $event_info['title']; ?>
                    закрыта
                    <svg class="ico">
                        <use xlink:href="#arr-down"></use>
                    </svg>
                </div>
            </div>

            <? } else { ?>
                <?php
                if ($register_exists) { ?>
                <div class="reginfo">
                    <div class="reginfo__logo">
                        <img src="catalog/view/theme/avclub/images/logo-register.svg" alt="">
                    </div>
                    <div class="reginfo__name">
                        <?php echo $event_info['title']; ?>
                        недоступна. Пользователь уже зарегистрирован.
                        <svg class="ico">
                            <use xlink:href="#arr-down"></use>
                        </svg>
                    </div>
                </div>

                <? } else { ?>
                    <div class="reginfo">
                        <div class="reginfo__logo">
                            <img src="<?php echo $theme_dir; ?>/images/logo-register.svg" alt="">
                        </div>
                        <div class="reginfo__name">
                            <?php echo $event_info['title']; ?>
                            <svg class="ico">
                                <use xlink:href="#arr-down"/>
                            </svg>
                        </div>
                        <div class="reginfo__data">
                            <div class="reginfo__datain">

                                <?php /* FORUM */ ?>
                                <?php if($event_info['type'] === 'forum') { ?>
                                <?php
                                        $infos = array();
                                        if(!empty($event_info['price'])) {
                                            $infos[] = $event_info['price'] . ' р.';
                                        }
                                        $infos[] = 'Бесплатно для резидентов Клуба при указании промокода';
                                        ?>
                                <div class="reginfo__price"><?php echo implode(' / ', $infos); ?> </div>
                                <div class="reginfo__text">
                                    <?php if(!empty($event_info['address'])) { ?>
                                    <span><?php echo implode(', ', $event_info['address']); ?></span>
                                    <?php } ?>
                                    <?php if($event_info['date_stop']) { ?>
                                    <span><?php echo $event_info['date']; ?> - <?php echo $event_info['date_stop']; ?></span>
                                    <?php } else { ?>
                                    <span><?php echo $event_info['date']; ?></span>
                                    <? } ?>
                                    <?php
                                            if ($event_info['isClosed']) { ?>
                                    <span>Регистрация на форум закрыта</span>
                                    <? } ?>
                                </div>
                                <?php } ?>
                                <?php /* # FORUM */ ?>

                                <?php /* WEBINAR */ ?>
                                <?php if($event_info['type'] === 'webinar') { ?>
                                <div class="reginfo__price"><?php echo $event_info['date']; ?></div>
                                <div class="reginfo__text">
                                    <span><?php echo $event_info['name']; ?></span>
                                    <?php
                                            if ($event_info['isClosed']) { ?>
                                    <span>Регистрация на вебинар закрыта</span>
                                    <? } ?>
                                </div>
                                <?php } ?>
                                <?php /* # WEBINAR */ ?>

                            </div>
                        </div>
                    </div>

                    <div class="regdata">
                        <?php echo $template; ?>
                    </div>
                    <?php } ?>
                <?php } ?>


        </div>

    </div>
</section>
<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_fail_modal.tpl'); ?>
<script src="<?php echo $theme_dir; ?>/js/register-main.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register-main.js') ?>"></script>
<script src="<?php echo $theme_dir; ?>/js/register.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register.js') ?>"></script>
<script src="catalog/view/theme/avclub/js/register-brand.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register-brand.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/css/suggestions.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/js/jquery.suggestions.min.js"></script>

<?php echo $footer; ?>