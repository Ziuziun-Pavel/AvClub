<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<div class="d-none">
	
	<?php /* LOGIN */ ?>
	<div id="modal_login" class="modal__cont">
		<div class="modal__inner">
			<button type="button" class="modal__close" data-fancybox-close>
				<svg class="ico ico-center"><use xlink:href="#close" /></svg>
			</button>

			<div class="modal__title">
				Личный кабинет в&nbsp;разработке
			</div>
			<div class="modal__text">
				Оставьте свой e-mail и мы сообщим когда все будет готово
			</div>
			<form action="#" class="letter__form modal__letter">
				<input type="text" name="email" class="letter__input" placeholder="Ваш e-mail">
				<button type="submit" class="letter__submit">
					<svg class="ico"><use xlink:href="#arrow-right"></use></svg>
				</button>
				<input type="hidden" name="type" value="account" />
			</form>
			<div class="modal__image">
				<img src="<?php echo $theme_dir; ?>/img/modal-login.svg" alt="">
			</div>
		</div>
	</div>

	<?php /* letter success */ ?>
	<div id="modal_letter_success" class="modal__cont">
		<div class="modal__inner">
			<button type="button" class="modal__close" data-fancybox-close>
				<svg class="ico ico-center"><use xlink:href="#close" /></svg>
			</button>

			<div class="modal__title">
				Вы успешно подписались на рассылку
			</div>
			<div class="modal__text">
				Вы будете получать самое важное за неделю в одном письме
			</div>
			<div class="modal__image modal__image-letter-success">
				<img src="<?php echo $theme_dir; ?>/img/modal-subscribe.svg" alt="">
			</div>
		</div>
	</div>
	<div id="modal_success" class="modal__cont">
		<div class="modal__inner">
			<button type="button" class="modal__close" data-fancybox-close>
				<svg class="ico ico-center"><use xlink:href="#close" /></svg>
			</button>

			<div class="modal__title">
				Заявка успешно отправлена
			</div>
			<div class="modal__text">
				В скором времени мы свяжемся с Вами
			</div>
			<div class="modal__image modal__image-letter-success">
				<img src="<?php echo $theme_dir; ?>/img/modal-subscribe.svg" alt="">
			</div>
		</div>
	</div>

	<?php /* CASE */ ?>
	<div id="modal-case" class="modal__cont modal__cont-form">
		<div class="modal__inner">
			<button type="button" class="modal__close" data-fancybox-close>
				<svg class="ico ico-center"><use xlink:href="#close" /></svg>
			</button>

			<div class="modal__title">
				Отправьте информацию о&nbsp;вашем кейсе для размещения на сайте
			</div>
			<div class="modal__text">
				Укажите контактные данные для обратной связи.
			</div>
			<form action="#" class="modal__form row form__order">
				<div class="modal__inp col-md-6">
					<input type="text" name="name" class="modal__input" placeholder="Имя">
				</div>
				<div class="modal__inp col-md-6">
					<input type="text" name="company" class="modal__input" placeholder="Компания">
				</div>
				<div class="modal__inp col-md-6">
					<input type="text" name="email" class="modal__input" placeholder="E-mail">
				</div>
				<div class="modal__inp col-md-6">
					<input type="text" name="phone" class="modal__input" placeholder="Телефон">
				</div>
				<?php /* ?>
				<div class="modal__inp col-12">
					<textarea name="message" class="modal__textarea" placeholder="Опишите вашу задачу или другие вопросы"></textarea>
				</div>
				<?php */ ?>
				<div class="modal__inp col-12">
					<label class="form__file link__outer">
						<svg><use xlink:href="#file" /></svg>
						<span class="file__upload" data-placeholder="Прикрепить текст кейса">
							<span class="link">Прикрепить текст кейса</span>
						</span>
						<input type="hidden" name="file_id">
					</label>
				</div>
				
				<div class="modal__inp col-12">
					<button type="submit" class="modal__submit btn btn-red">
						<span>Отправить</span>
					</button>
				</div>
				<input type="hidden" name="form" value="Заявка на размещение кейса">
			</form>
		</div>
	</div>

	<?php /* COMPANY */ ?>
	<div id="modal_company" class="modal__cont modal__cont-form">
		<div class="modal__inner">
			<button type="button" class="modal__close" data-fancybox-close>
				<svg class="ico ico-center"><use xlink:href="#close" /></svg>
			</button>

			<div class="modal__title">
				Добавить компанию в&nbsp;каталог
			</div>
			<div class="modal__text">
				Укажите контактные данные для обратной связи.
			</div>
			<form action="#" class="modal__form row form__order">
				<div class="modal__inp col-md-6">
					<input type="text" name="name" class="modal__input" placeholder="Имя">
				</div>
				<div class="modal__inp col-md-6">
					<input type="text" name="company" class="modal__input" placeholder="Компания">
				</div>
				<div class="modal__inp col-md-6">
					<input type="text" name="email" class="modal__input" placeholder="E-mail">
				</div>
				<div class="modal__inp col-md-6">
					<input type="text" name="phone" class="modal__input" placeholder="Телефон">
				</div>
				<div class="modal__inp col-12">
					<input type="text" name="web" class="modal__input req" placeholder="Сайт компании">
				</div>
				<?php  ?>
				<div class="modal__inp col-12">
					<textarea name="company_text" class="modal__textarea req" placeholder="Краткое описание компании"></textarea>
				</div>
				<?php  ?>
				<?php /* ?>
				<div class="modal__inp col-12">
					<label class="form__file link__outer">
						<svg><use xlink:href="#file" /></svg>
						<span class="file__upload" data-placeholder="Прикрепить подробную информацию">
							<span class="link">Прикрепить подробную информацию</span>
						</span>
						<input type="hidden" name="file_id">
					</label>
				</div>
				<?php */ ?>
				
				<div class="modal__inp col-12">
					<button type="submit" class="modal__submit btn btn-red">
						<span>Отправить</span>
					</button>
				</div>
				<input type="hidden" name="form" value="Заявка на добавление компании">
			</form>
		</div>
	</div>

	<div id="modal_vote" class="modal__cont modal__cont-form">
		<div class="modal__inner" style="padding: 40px 40px 40px 40px;">
			<button type="button" class="modal__close" data-fancybox-close>
				<svg class="ico ico-center"><use xlink:href="#close" /></svg>
			</button>

			<div class="modal-body">
				<h3 class="vote-title"></h3>
				<iframe id="crmForm" src="" width="100%" height="800px" frameborder="0" >

				</iframe>
			</div>
		</div>
	</div>

</div>