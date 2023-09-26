<?php $theme_dir = 'catalog/view/theme/avclub'; ?>

<?php echo $header; ?>
<?php echo $content_top; ?>
<section class="section_content section_master">
	<div class="container">

		<?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

		<div class="content__title">
			<h1><?php echo $heading_title; ?></h1>
		</div>
		<div class=" content__cont">

			<div class="master__row row">

				<div class="master__sub title col-12">расписание</div>

				<?php $m = 0; ?>
				<?php if(!empty($masters)) { ?>
					<div class="event__sort col-12">
						<div class="event__filter">
							<ul class="event__filter-list">
								<?php if($types) { ?>
									<?php foreach($types as $type=>$name) { ?>
										<li><a href="#" class="event__filter-tab filter__type <?php echo $type === '' ? 'active' : ''; ?>" data-type="<?php echo $type; ?>"><?php echo $name; ?></a></li>
									<?php } ?>
								<?php } ?>
							</ul>
						</div>
					</div>
					<?php foreach($masters as $master) { $m++; ?>
						<div class="master__outer master__outer_item master__outer_item-<?php echo $master['type']; ?> col-lg-6">
							<?php require(DIR_TEMPLATE . 'avclub/template/_include/master.tpl'); ?>
						</div>
					<?php } ?>
				<?php }else{ ?>
					<div class="master__outer master__empty text col-12">
						<h4>Расписание будущих онлайн-событий обновляется</h4>
						<p>Подождите еще немного ;)</p>
						<div class="master__goto">
							<a href="#master-past" class="link_under goTo">Смотреть прошедшие онлайн-события</a>
						</div>
					</div>
				<?php } ?>

				<?php 
				$master_info_class = 'col-lg-6'; 
				if(count($masters) == 0 || count($masters) % 2 == 0) {
					$master_info_class = 'imaster__center col-12'; 
				}
				?>
				<div class="master__outer master__outer-imaster <?php echo $master_info_class; ?>">
					<?php require(DIR_TEMPLATE . 'avclub/template/_include/master-add.tpl'); ?>
				</div>					

			</div>

			<?php /* journals */ ?>
			<?php if($journals) { ?>
				<div id="master-past" class="news__sub title">прошедшие онлайн-события</div>
				<div class="news__row news__row-master  or__row row">

					<?php foreach($journals as $journal) {$m++; ?>
						<div class="news__outer col-sm-6 col-lg-4 col-xl-3">
							<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-article.tpl'); ?>
						</div>
					<?php } ?>

					<?php if($banner) { ?>
						<div class="news__outer news__outer-banner col-sm-6 col-lg-4 col-xl-3">
							<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-banner.tpl'); ?>
						</div>
					<?php } ?>

					<?php if($telegram['status']) { ?>
						<div class="news__outer news__outer-tg d-sm-none col-sm-6 col-lg-4 col-xl-3">
							<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-telegram.tpl'); ?>
						</div>
					<?php } ?>
				</div>
				<?php if($pagination) { ?>
					<div class="page__row"><?php echo $pagination; ?></div>
				<?php } ?>
			<?php } ?>
			<?php /* # journals */ ?>



		</div>

	</div>
</section>
<script>
	changeFilter = function(){
		var 
		$type = $('.filter__type.active').attr('data-type'),
		$ex = '';

		$('.master__outer_item').removeClass('d-none');

		if($type) {
			$ex += '.master__outer_item-'+$type;
		}
		if($ex) {
			$('.master__outer_item:not('+$ex+')').addClass('d-none');
		}


		if($('.master__outer_item:not(.d-none)').length % 2 === 0) {
			$('.master__outer-imaster').removeClass('col-lg-6').addClass('imaster__center col-12');
		}else{
			$('.master__outer-imaster').addClass('col-lg-6').removeClass('imaster__center col-12');
		}
	}

	$(function(){
		$('.filter__type').on('click', function(e){
			e.preventDefault();
			var 
			$self = $(this);
			if(!$self.hasClass('active')) {
				$('.filter__type.active').removeClass('active');
				$self.addClass('active');
				changeFilter();
			}
			
		})
	})
</script>
<?php echo $content_bottom; ?>
<?php echo $footer; ?>