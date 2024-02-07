<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_attention.tpl'); ?>
<div class="regdata__title">
	Введите номер своего мобильного телефона
</div>
<form id="registration-number" action="#" class="regphone">
	<div class="regphone__inp">
		<input id="telephone" type="tel" name="telephone" class="regphone__input" value="" placeholder="+" style="max-width: 100%;"/>
		<div id="validation-message" style="color: red;"></div>

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

<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" rel="stylesheet"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>


<script>
	$(document).ready(function() {
		$("#telephone").intlTelInput({
			initialCountry: "ru",
			separateDialCode: false,
			nationalMode: false,
			utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js",
		});

		// $(document).on('input', '#telephone', function () {
		// 	var isValid = $('#telephone').intlTelInput('isValidNumber');
		//
		// })
	});
</script>

<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_fail.tpl'); ?>