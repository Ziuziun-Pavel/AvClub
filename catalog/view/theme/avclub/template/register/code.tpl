<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_attention.tpl'); ?>
<div class="regdata__title">
	Введите проверочный код
</div>
<?php if(!empty($info_text)) { ?>
	<div class="regdata__text"><?php echo $info_text; ?></div>
<?php } ?>
<form action="#" class="regphone" id="registration-code">
	<div class="regphone__inp">
		<input type="tel" name="code" class="regphone__input" value="" placeholder="————"/>
		<button type="submit" class="regphone__submit btn btn-invert">
			<span>Продолжить</span>
			<svg class="ico"><use xlink:href="#arr-register" /></svg>
		</button>
	</div>
	<div class="rephone__error reg__error"></div>
	<input type="hidden" name="sid" value="<?php echo $session; ?>">
</form>
<script>
	$(function(){
		$('.regphone__inp input[name="code"]').inputmask("9999", {"placeholder": "—"});
	})
</script>