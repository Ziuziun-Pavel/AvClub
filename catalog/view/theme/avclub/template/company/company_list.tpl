<div class="company__cont or__row">

	<?php if(!empty($companies)) { ?>
		<?php foreach($companies as $company) { ?>
			<div class="company__outer">
				<a href="<?php echo $company['href']; ?>" class="company__item link__outer">
					<span class="company__logo">
						<img src="<?php echo $company['thumb']; ?>" alt="">
					</span>
					<span class="company__info">
						<span class=" company__name"><span class="link"><?php echo $company['title']; ?></span></span>
						<span class="company__descr"><?php echo $company['preview']; ?></span>
					</span>
					<span class="company__branch">
						<?php if(!empty($company['tags'])) { ?>
							<ul class="clearfix">
								<?php foreach($company['tags'] as $branch) { ?>
									<li><?php echo $branch['title']; ?></li>
								<?php } ?>
							</ul>
						<?php } ?>
					</span>
				</a>
			</div>
		<?php } ?>
	<?php }else{ ?>
		<div class="master__outer master__empty text col-12">
			<h4>Ничего не найдено</h4>
			<p>Попробуйте изменить параметры фильтра</p>
			<div class="master__goto">
				<a href="#" class="link_under cofilter__clear_all">Очистить все</a>
			</div>
		</div>
	<?php } ?>

	<?php require(DIR_TEMPLATE . 'avclub/template/_include/company-add.tpl'); ?>


	<?php if(!empty($banner)) { ?>
		<div class="company__banner d-lg-none">
			<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-banner.tpl'); ?>
		</div>
	<?php } ?>

</div>

<?php if($pagination) { ?>
	<div class="page__row">
		<?php echo $pagination; ?>
	</div>
	<?php } ?>
