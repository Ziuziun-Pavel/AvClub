<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_attention.tpl'); ?>
<div class="regdata__title"><?php echo html_entity_decode($title); ?></div>

<div class="regdata__form">

	<div class="reguser">
		<div class="reguser__photo">
			<img src="<?php echo $user['avatar']; ?>" alt="">
		</div>
		<div class="reguser__name"><?php echo $user['name'] . ' ' . $user['lastname']; ?></div>
		<div class="reguser__phone"><?php echo $phone; ?></div>
	</div>

	<div class="regpromo">

		<div class="regpromo__inp">
			<input type="text" name="promo" class="regpromo__input" value="" placeholder="Введите промокод" />
			<button type="submit" class="regpromo__submit regpromo__button btn btn-invert" id="button-savepromo" data-promo="1">
				<span>Применить</span>
				<svg class="ico"><use xlink:href="#arr-register" /></svg>
			</button>
		</div>
		<div class="regpromo__error reg__error"></div>

		<div class="regpromo__bottom">
			<button type="button" class="regpromo__without regpromo__button btn btn-red" id="button-nopromo" data-promo="0">
				<span>Продолжить без промокода</span>
			</button>
			<div class="regpromo__text">
				<!--Укажите промокод при регистрации на форуме,
				чтобы получить эксклюзивные преимущества: доступ в закрытые зоны,
				участие в специальных мероприятиях,
				а также бонусы и подарки от участников и организаторов.-->
				Ввод промокода добавляет баллы на ваш личный счёт,
				которые Вы можете потратить на активности в AV клубе.
			</div>
		</div>

		<input type="hidden" name="sid" value="<?php echo $session; ?>">
	</div>

</div>
<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_fail.tpl'); ?>
