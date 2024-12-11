<style>
    #telephone::placeholder {
        color: #cdcdcd;
    }

    #loading-message {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 50px;
        background-color: #f0f0f0;
        text-align: center;
        line-height: 50px;
        z-index: 9999;
    }
</style>

<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_attention.tpl'); ?>
<div class="regdata__title">
    Введите номер своего мобильного телефона
</div>
<div id="loading-message">
    Дождитесь полной загрузки страницы...
</div>
<form id="registration-number" action="#" class="regphone">
    <div class="regphone__inp">
        <input id="telephone" type="tel" name="telephone" class="regphone__input" value="" placeholder=""
               style="max-width: 100%;"/>
        <div id="validation-message" style="color: red;"></div>

        <button id="registration-number-btn" type="submit" class="regphone__submit btn btn-invert">
            <span>Продолжить</span>
            <svg class="ico">
                <use xlink:href="#arr-register"/>
            </svg>
        </button>
    </div>
    <div class="regphone__email">
        <div class="regform__inp">
            <input type="text" name="email" class="regform__input" value="" placeholder=""/>
            <div class="regform__label">E-mail</div>
        </div>
    </div>
    <div class="rephone__error reg__error"></div>
    <div class="regphone__agree">
        Продолжая, вы соглашаетсь с <a href="/polices/" class="link link_under" target="_blank">политикой обработки
            персональных данных</a>
    </div>
    <input type="hidden" name="r" value="1">
    <input type="hidden" name="sid" value="<?php echo $session; ?>">
</form>

<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" rel="stylesheet"/>
<!--<link href="<?php echo $theme_dir; ?>/css/intlTelInput.css" rel="stylesheet"/>-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
<!--<script src="<?php echo $theme_dir; ?>/js/intlTelInput-jquery.min.js"></script>-->


<script>
    $('#registration-number-btn').prop("disabled",true);
    window.addEventListener('load', function () {
        var loadingMessage = document.getElementById('loading-message');
        loadingMessage.style.display = 'none';
        $('#registration-number-btn').prop("disabled",false);
    });
    $("#telephone").intlTelInput({
        initialCountry: "auto",
        separateDialCode: true,
        nationalMode: false,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js",
        preferredCountries: ["ru", "by", "kz", "kz"],
        geoIpLookup: callback => {
            fetch("https://ipapi.co/json")
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback("us"));
        }
    });

    document.getElementById('telephone').addEventListener('input', function (event) {
        var placeholder = this.placeholder.replace(/\D/g, '');
        var inputValue = this.value.replace(/\D/g, '');
        var maxLength = placeholder.length;

        if (inputValue.length > maxLength) {
            inputValue = inputValue.slice(0, maxLength);
        }

        this.value = inputValue;
    });

</script>

<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_fail.tpl'); ?>