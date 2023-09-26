<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_attention.tpl'); ?>
<div class="regdata__title"><?php echo html_entity_decode($title); ?></div>

<div class="regdata__form">

	<div class="reguser">
		<div class="reguser__photo">
			<img src="<?php echo $user['avatar']; ?>" alt="">
		</div>
		<div class="reguser__phone"><?php echo $phone; ?></div>
	</div>

	<form class="regform row" id="register-newuser">

		<div class="regform__outer col-12 col-md-6">
			<div class="regform__inp">
				<input type="text" name="name" value="" class="regform__input" placeholder="Имя" />
			</div>
		</div>

		<div class="regform__outer col-12 col-md-6">
			<div class="regform__inp">
				<input type="text" name="lastname" value="" class="regform__input" placeholder="Фамилия" />
			</div>
		</div>

		<div class="regform__outer col-12">
			<div class="regform__inp">
				<input type="text" name="email" value="" class="regform__input" />
				<div class="regform__label">Email</div>
			</div>
		</div>

		<div class="regform__outer col-12">
			<div class="regform__inp">
				<input type="text" name="post" value="" class="regform__input" />
				<div class="regform__label">Должность</div>
			</div>
		</div>

		<div class="regform__outer col-12">
			<div class="regform__inp">
				<input type="text" name="city" value="" class="regform__input" />
				<div class="regform__label">Город работы</div>
			</div>
		</div>

		<div class="regform__outer regform__outer-capt col-12">
			Компания
		</div>
		<div class="regform__outer col-12">
			<div class="regform__inp">
				<input type="text" name="company" value="" class="regform__input" autocomplete="false"/>
				<div class="regform__label">Компания</div>
			</div>
		</div>

		<div class="regform__btns col-12">
			<div class="row">
				<div class="regform__outer regform__outer-btn col-12 col-md-6">
					<button type="button" class="regform__btn regform__btn-save btn btn-red" id="button-save">
						<span>
							Сохранить изменения
						</span>
					</button>
				</div>

			</div>

		</div>


	</form>

</div>
<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_fail.tpl'); ?>