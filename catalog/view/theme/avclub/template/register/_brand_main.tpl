<div class="regbrand__start">
    <div class="regbrand__start--title">Укажите название торговой <br>марки/бренда компании в которой вы работаете</div>
    <div class="regbrand__start--subtitle">Введите название компании в поиск и мы найдем ее в каталоге</div>
    <div class="regbrand__company--subtitle__error" style="color: red; display: none;"></div>
        <div class="regbrand__start--inp">
        <div class="regform__inp regform__inp-plh">
            <input type="text" name="brand" value='<?php echo !empty($brand_search) ? $brand_search : ""; ?>'
                   class="regform__input <?php echo !empty($brand_search) ? 'valid' : ''; ?>" data-input-change/>
            <div class="regform__plh">Название компании</div>
        </div>
        <button type="button" class="btn btn-invert regbrand__start--search" id="regbrand-search">+ Найти</button>
    </div>
</div>



<div style="margin-top: 1rem" class="regbrand__start country">
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
<script>
    function updateCountry() {
        var selectedCountry = document.querySelector('input[name="company_country"]:checked').value;
        document.getElementById('country').innerText = selectedCountry;
    }
</script>
