
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

	<div class="cofilter__top">
		<?php if(!empty($types)) { ?>
			<div class="cofilter__type cofilter__col">
				<input type="text" name="type" class="ocfilter__input" autocomplete="false" value="" placeholder="Вид продукции (например: проектор)" />
				<a href="#modal_company_type" class="cofilter__type_show modalshow">
					<span></span>
					<span></span>
					<span></span>
				</a>
				<button type="submit" class="cofilter__submit"><svg class="ico ico-center"><use xlink:href="#search" /></svg></button>
				<button type="button" class="cofilter__delete"><svg class="ico ico-center"><use xlink:href="#close2" /></svg></button>
			</div>
		<?php } ?>
		<div class="cofilter__name cofilter__col">
			<input type="text" name="company" class="ocfilter__input" autocomplete="false" value="" placeholder="Название компании" />
			<button type="submit" class="cofilter__submit"><svg class="ico ico-center"><use xlink:href="#search" /></svg></button>
			<button type="button" class="cofilter__delete"><svg class="ico ico-center"><use xlink:href="#close2" /></svg></button>
		</div>
		<?php /* ?>
		<div class="cofilter__brand cofilter__col">
			<input type="text" name="brand" class="ocfilter__input" autocomplete="false" value="" placeholder="Бренд" />
			<button type="submit" class="cofilter__submit"><svg class="ico ico-center"><use xlink:href="#search" /></svg></button>
			<button type="button" class="cofilter__delete"><svg class="ico ico-center"><use xlink:href="#close2" /></svg></button>
		</div>
		<?php */ ?>
		<div class="cofilter__clear">
			<button class="cofilter__clear_btn cofilter__clear_all" type="button">Очистить все</button>
		</div>

		<?php /* ?>
		<input type="hidden" name="tag_id" value="" />
		<input type="hidden" name="company_id" value="" />
		<input type="hidden" name="brand_id" value="" />
		<?php */ ?>
	</div>

	<?php /*if(!empty($branches)) { ?>
		<div class="cofilter__bottom clearfix">
			<?php foreach($branches as $branch) { ?>
				<label class="cofilter__branch"><input type="checkbox" name="branch[]" value="<?php echo $branch['branch_id']; ?>"><span><?php echo $branch['title']; ?></span></label>
			<?php } ?>
		</div>
	<?php } */?>

	<div class="d-none">
		<div id="modal_company_type" class="mcomptype__cont">
			<button type="button" class="modal__close" data-fancybox-close>
				<svg class="ico ico-center"><use xlink:href="#close" /></svg>
			</button>
			<div class="container">
				<div class="mcomptype__title title">#Все виды продукции</div>
				<div class="mcomptype__row row">
					<?php if(!empty($types)) { ?>
						<?php foreach(array_chunk($types, ceil(count($types) / 4)) as $key=>$column) { ?>
							<?php 
							switch($key) {
								case 0:	$class = 'order-1'; break;
								case 1:	$class = 'order-2'; break;
								case 2:	$class = 'order-3 order-sm-1 order-md-3'; break;
								case 3:	$class = 'order-4'; break;
								default: $class = ''; break;
							}
							?>
							<ul class="<?php echo $class; ?> list-vert col-sm-6 col-md-3">
								<?php foreach($column as $type) { ?>
									<li><label class="mcomptype__label"><input type="radio" name="alltype" value="<?php echo $type['tag_id']; ?>" class="alltype-<?php echo $type['tag_id']; ?>" data-title="<?php echo $type['title']; ?>"><span><?php echo $type['title']; ?></span></label></li>
								<?php } ?>
							</ul>
						<?php } ?>
					<?php } ?>

				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="form" value="filter">
	<input type="hidden" name="company_category_id" value="<?php echo $company_category_id; ?>">
</form>