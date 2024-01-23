<div class="regbrand__result">

    <div class="regbrand__result--top">
        <div class="regbrand__result--left">
            <div class="regbrand__result--title">Результаты поиска</div>
        </div>
    </div>

    <div class="regbrand__result--data">

        <div class="regbrand__list">
            <div class="regbrand__list--top">
                <div class="regbrand__list--text">
                    <?php echo html_entity_decode($text_result); ?>
                </div>
                <div class="regbrand__list--change">
                    <a href="#" class="link" id="regbrand-change" data-search="<?php echo $search; ?>">+ Изменить
                        поиск</a>
                </div>
            </div>
            <div class="regbrand__list--title">Выберите вашу компанию из&nbsp;списка</div>
            <div class="regbrand__list--list">
                <?php foreach($companies as $company) { ?>
                <?php if ($dadata): ?>
                <div style="padding: .5rem;" class="btn company__block--item regbrand--choose" data-name='<?php echo $company["title"]; ?>' data-inn='<?php echo implode(", ", array_filter([$company["inn"], $company["unp"], $company["bik"]], "strlen")); ?>' >
                    <div style="text-align: left"><span style="font-size: .9rem; font-weight: 500">Название компании: </span><?php echo ' ' . $company['title']; ?></div>
                    <?php if ($company['manager']): ?>
                        <div style="text-align: left"><span style="font-size: .9rem; font-weight: 500">Директор: </span><?php echo ' ' . $company['manager']; ?></div>
                    <?php endif; ?>
                    <?php if ($company['inn']): ?>
                        <div style="text-align: left"><span style="font-size: .9rem; font-weight: 500">ИНН: </span><?php echo ' ' . $company['inn']; ?></div>
                    <?php endif; ?>
                    <?php if ($company['unp']): ?>
                        <div style="text-align: left"><span style="font-size: .9rem; font-weight: 500">УНП: </span><?php echo ' ' . $company['unp']; ?></div>
                    <?php endif; ?>
                    <?php if ($company['bik']): ?>
                        <div style="text-align: left"><span style="font-size: .9rem; font-weight: 500">БИК: </span><?php echo ' ' . $company['bik']; ?></div>
                    <?php endif; ?>
                    <?php if ($company['address']): ?>
                        <div style="text-align: left"><span style="font-size: .9rem; font-weight: 500">Адрес: </span><?php echo ' ' . $company['address']; ?></div>
                    <?php endif; ?>

                </div>
                <?php else: ?>
                <button type="button" class="btn btn-invert regbrand--choose"
                        data-id="<?php echo $company['id']; ?>"><?php echo $company['title']; ?></button>
                <?php endif; ?>
                <?php } ?>
            </div>
            <div class="regbrand__list--line"></div>
            <div class="regbrand__list--title">
                Если вашей компании нет в списке, <br>
                вы можете добавить новую компанию
            </div>
            <div class="regbrand__list--add">
                <button type="button" class="btn btn-invert" id="regbrand-add" data-search="<?php echo $search; ?>">
                    Добавить компанию <?php echo $search; ?>
                </button>
            </div>
        </div>

    </div>

</div>

<style>
    .company__block--item {
        flex-direction: column;
        align-items: start;
        width: 100%;
        text-align: center;
        -ms-flex-pack: center;
        justify-content: center;
        font-weight: 600;
        border: 1px solid var(--red);
        background: #fff;
        min-height: 46px;
        color: #000;
    }

    .company__block--item:hover {
        background: #c14949;
        color: #ffffff;
    }
</style>