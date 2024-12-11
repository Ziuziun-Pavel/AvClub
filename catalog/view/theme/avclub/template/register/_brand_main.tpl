<div class="regbrand__start">
    <div class="regbrand__start--title">Укажите название торговой <br>марки/бренда компании в которой вы работаете</div>

    <div style="margin-top: 1rem; margin-bottom: 1rem;" class="regbrand__start country">
        <div class="regbrand__start--subtitle">Выберите название страны, в которой производить поиск компании</div>
        <div class="regbrand__country--subtitle__error" style="color: red; display: none;"></div>
        <div class="regbrand__start--inp">
            <div class="regform__inp regform__inp-plh regform__select dropdown">
                <div class="regform__select--text dropdown-toggle <?php echo $main_activity ? 'valid' : ''; ?>"
                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span style="margin-top: -1rem" id="country">Страна:</span>
                    <svg>
                        <use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill">
                    </svg>
                </div>
                <div class="regform__select--dropdown dropdown-menu">
                    <div class="regform__select--list">
                        <?php
        $countries = array(
            "Россия",
            "Беларусь",
            "Казахстан",
            "Узбекистан",
            "Кипр",
            "Словакия",
            "Ватикан",
            "Сербия",
            "Фарерские острова",
            "Албания",
            "Италия",
            "Испания",
            "Ирландия",
            "Хорватия",
            "Эстония",
            "Великобритания",
            "Гибралтар",
            "Финляндия",
            "Швеция",
            "Исландия",
            "Швейцария",
            "Латвия",
            "Польша",
            "Литва",
            "Андорра",
            "Джерси",
            "Мальта",
            "Германия",
            "Сан-Марино",
            "Люксембург",
            "Румыния",
            "Шпицберген и Ян-Майен",
            "Аландские острова",
            "Гернси",
            "Норвегия",
            "Бельгия",
            "Португалия",
            "Дания",
            "Чехия",
            "Греция",
            "Австрия",
            "Монако",
            "Словения",
            "Босния и Герцеговина",
            "Франция",
            "Болгария",
            "Молдавия",
            "Остров Мэн",
            "Черногория",
            "Венгрия",
            "Северная Македония",
            "Республика Косово",
            "Нидерланды",
            "Украина",
            "Лихтенштейн",
            "Китай"
        );

?>
                        <?php foreach($countries as $country) { ?>
                        <label class="regform__select--input valid">
                            <input type="radio" name="company_country"
                                   value="<?php echo $country; ?>" onchange="updateCountry()">
                            <span><?php echo $country; ?></span>
                        </label>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="regbrand__start--subtitle">Введите название компании и нажмите кнопку "Найти"</div>
    <div class="regbrand__company--subtitle__error" style="color: red; display: none;"></div>
    <div class="regbrand__start--inp">
        <div class="regform__inp regform__inp-plh">
            <input type="text" name="brand" value='<?php echo !empty($brand_search) ? $brand_search : ""; ?>'
                   class="regform__input <?php echo !empty($brand_search) ? 'valid' : ''; ?>" data-input-change/>
            <div class="regform__plh">Название компании</div>
        </div>
        <button type="button" class="btn btn-invert regbrand__start--search" id="regbrand-search">+ Найти</button>
    </div>

    <div class="error-message" style="color:red; margin-top: .5rem; display: none;">Нужно выбрать компанию. Выберите страну, введите название компании и нажмите кнопку "Найти"</div>

</div>

<script>
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
