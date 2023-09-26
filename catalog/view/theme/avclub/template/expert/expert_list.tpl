<?php $theme_dir = 'catalog/view/theme/avclub'; ?>

<?php echo $header; ?>
<?php echo $content_top; ?>

<section class="section_explist">
	<div class="container">

		<?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

		<div class="content__title">
			<h1><?php echo $heading_title; ?></h1>
		</div>

		<div class="content__cont">

			<div class="cofilter__links">
				<?php foreach($links as $link) { ?>
					<?php if($link['active']) { ?>
						<span class="btn btn-invert --disabled"><?php echo $link['title']; ?></span>
					<?php }else{ ?>
						<a href="<?php echo $link['link']; ?>" class="btn btn-red"><?php echo $link['title']; ?></a>
					<?php } ?>
				<?php } ?>
			</div>

			<form action="#" class="cofilter__cont">

				<div class="cofilter__top cofilter__top-lr">
					<div class="cofilter__left">

						<div class="cofilter__col">
							<input type="text" name="filter_name" class="cofilter__input cofilter__input--auto" autocomplete="false" value="" placeholder="ФИО (например: Дмитрий Богданов)" />
							<button type="submit" class="cofilter__submit"><svg class="ico ico-center"><use xlink:href="#search" /></svg></button>
							<button type="button" class="cofilter__delete"><svg class="ico ico-center"><use xlink:href="#close2" /></svg></button>
						</div>

						<?php /* ?>
						<div class="cofilter__col">
							<input type="text" name="filter_company" class="cofilter__input cofilter__input--auto" autocomplete="false" value="" placeholder="Бренд (например: Promethean)" />
							<button type="submit" class="cofilter__submit"><svg class="ico ico-center"><use xlink:href="#search" /></svg></button>
							<button type="button" class="cofilter__delete"><svg class="ico ico-center"><use xlink:href="#close2" /></svg></button>
						</div>
						<?php */ ?>

						<div class="cofilter__col">
							<input type="text" name="filter_tag" class="cofilter__input cofilter__input--auto" autocomplete="false" value="" placeholder="Экспертиза (например: акустика)" />
							<button type="submit" class="cofilter__submit"><svg class="ico ico-center"><use xlink:href="#search" /></svg></button>
							<button type="button" class="cofilter__delete"><svg class="ico ico-center"><use xlink:href="#close2" /></svg></button>
						</div>

						<?php /* ?>
						<div class="cofilter__col">
							<input type="text" name="filter_branch" class="cofilter__input cofilter__input--auto" autocomplete="false" value="" placeholder="Отрасль (например: производство)" />
							<button type="submit" class="cofilter__submit"><svg class="ico ico-center"><use xlink:href="#search" /></svg></button>
							<button type="button" class="cofilter__delete"><svg class="ico ico-center"><use xlink:href="#close2" /></svg></button>
						</div>
						<?php */ ?>

					</div>
					<div class="cofilter__right">
						<div class="cofilter__clear">
							<button class="cofilter__clear_btn cofilter__clear_all" type="button">Очистить все</button>
						</div>
					</div>

				</div>

			</form>

			<div class="cosort">
				<div class="cosort__outer">
					<div class="cosort__check">
						<div class="cosort__top">
							<span><strong>Порядок:</strong> по умолчанию</span>
							<svg class="ico"><use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill"></svg>
						</div>
						<div class="cosort__list">
							<label class="cosort__item">
								<input type="radio" name="sort" value="" checked>
								<span><strong>Порядок:</strong> по умолчанию</span>
							</label>
							<label class="cosort__item">
								<input type="radio" name="sort" value="lastname.asc">
								<span><strong>По фамилии:</strong> от А до Я</span>
							</label>
							<label class="cosort__item">
								<input type="radio" name="sort" value="lastname.desc">
								<span><strong>По фамилии:</strong> от Я до А</span>
							</label>
							<label class="cosort__item">
								<input type="radio" name="sort" value="modified.desc">
								<span><strong>Порядок:</strong> сперва новые</span>
							</label>
							<label class="cosort__item">
								<input type="radio" name="sort" value="modified.asc">
								<span><strong>Порядок:</strong> сперва старые</span>
							</label>
							<label class="cosort__item">
								<input type="radio" name="sort" value="articles.desc">
								<span><strong>По количеству публикаций</strong></span>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="explist__list">
				<?php echo $expert_list; ?>
			</div>

		</div>


	</div>
</section>

<script src="<?php echo $theme_dir; ?>/js/expert-filter.js?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/js/expert-filter.js') ?>"></script>
<?php echo $footer; ?>