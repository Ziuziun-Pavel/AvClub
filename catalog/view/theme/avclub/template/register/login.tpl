<style>
	#tel::placeholder {
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
<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<?php echo $header; ?>
<div class="reg__load">
	<div class="cssload-clock"></div>
</div>
<section class="section_register">
	<div class="container">

		<div class="regcont">

			<div class="reginfo">
				<div class="reginfo__logo">
					<img src="<?php echo $theme_dir; ?>/images/logo-register.svg" alt="">
				</div>
				<div class="reginfo__name">
					Вход для резидентов АВ Клуба
				</div>
			</div>

			<div class="regdata">

				<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_attention.tpl'); ?>
				<div class="regdata__title">
					Введите номер своего мобильного телефона
				</div>
				<div id="loading-message">
					Дождитесь полной загрузки страницы...
				</div>
				<form id="registration-number" action="#" class="regphone">
					<div class="regphone__inp">
						<input id="tel" type="tel" name="telephone" class="regphone__input" value="" placeholder="" style="max-width: 100%;"/>
						<div id="validation-mess" style="color: red;"></div>
						<button type="submit" class="regphone__submit btn btn-invert">
							<span>Продолжить</span>
							<svg class="ico"><use xlink:href="#arr-register" /></svg>
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
						Продолжая, вы соглашаетсь с <a href="/polices/" class="link link_under" target="_blank">политикой обработки персональных данных</a>
					</div>
					<input type="hidden" name="r" value="1">
					<input type="hidden" name="sid" value="<?php echo $session; ?>">
				</form>

				<div class="regfail">
					<div class="regfail__in">
						<div class="regfail__title">Не получается авторизоваться?</div>
						<div class="regfail__text">Попробуйте очистить кэш браузера или открыть страницу авторизации в другом браузере. Вы также можете сообщить нам о проблеме, чтобы мы могли оперативно с ней разобраться.</div>
						<div class="regfail__link">
							<a href="#reg-fail" class="link link_under modalshow">Сообщить о проблеме</a>
						</div>
					</div>
				</div>

			</div>

		</div>

	</div>
</section>
<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_fail_modal.tpl'); ?>
<script src="<?php echo $theme_dir; ?>/js/register-main.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register-main.js') ?>"></script>
<script src="catalog/view/theme/avclub/js/register-brand.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/register-brand.js') ?>"></script>
<script src="<?php echo $theme_dir; ?>/js/login.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/login.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@latest/dist/js/jquery.suggestions.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" rel="stylesheet"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>

<script>
	window.addEventListener('load', function () {
		var loadingMessage = document.getElementById('loading-message');
		loadingMessage.style.display = 'none';
	});

	$("#tel").intlTelInput({
		initialCountry: "auto",
		separateDialCode: true,
		nationalMode: false,
		utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js",
		preferredCountries:["ru","by","kz","uz"],
		geoIpLookup: callback => {
			fetch("https://ipapi.co/json")
					.then(res => res.json())
					.then(data => callback(data.country_code))
					.catch(() => callback("us"));
		}
	});

	document.getElementById('tel').addEventListener('input', function(event) {
		var placeholder = this.placeholder.replace(/\D/g,'');
		var inputValue = this.value.replace(/\D/g,'');
		var maxLength = placeholder.length;

		if (inputValue.length > maxLength) {
			inputValue = inputValue.slice(0, maxLength);
		}

		this.value = inputValue;
	});

</script>
<?php echo $footer; ?>