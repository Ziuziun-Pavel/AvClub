
<div class="d-none">
	<div id="reg-fail" class="modal__cont modal__cont-form">
		<div class="modal__inner">
			<button type="button" class="modal__close" data-fancybox-close>
				<svg class="ico ico-center"><use xlink:href="#close" /></svg>
			</button>

			<div class="modal__title">
				Информация об ошибке
			</div>
			<form action="#" class="modal__form row form__reg_fail">
				<div class="modal__inp col-md-6">
					<input type="text" name="name" class="modal__input" placeholder="Имя">
				</div>
				<div class="modal__inp col-md-6">
					<input type="text" name="phone" class="modal__input" placeholder="Телефон">
				</div>
				<div class="modal__inp col-12">
					<textarea name="message" class="modal__textarea req" placeholder="Описание ошибки"></textarea>
				</div>
				<div class="modal__inp col-12">
					<label class="form__file link__outer">
						<svg><use xlink:href="#file" /></svg>
						<span class="file__upload" data-placeholder="Прикрепить скриншот">
							<span class="link">Прикрепить скриншот</span>
						</span>
						<input type="hidden" name="file_id">
					</label>
				</div>
				
				<div class="modal__inp col-12">
					<button type="submit" class="modal__submit btn btn-red">
						<span>Отправить</span>
					</button>
				</div>
				<input type="hidden" name="form" value="Заявка об ошибке регистрации / авторизации">
				<input type="hidden" name="sid" value="<?php echo $session; ?>">
			</form>
		</div>
	</div>
	<div id="reg-fail-success" class="modal__cont">
		<div class="modal__inner">
			<button type="button" class="modal__close" data-fancybox-close>
				<svg class="ico ico-center"><use xlink:href="#close" /></svg>
			</button>

			<div class="modal__title">
				Сообщение успешно отправлено
			</div>
			<div class="modal__text">
				Спасибо, что помогаете нам стать лучше
			</div>
			<div class="modal__image modal__image-letter-success">
				<img src="<?php echo $theme_dir; ?>/img/modal-subscribe.svg" alt="">
			</div>
		</div>
	</div>

</div>