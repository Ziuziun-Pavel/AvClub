<?php echo $header; ?>

<section class="section_content section_event-list">
	<div class="container">

		<?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

		<div class="content__title">
			<h1><?php echo $heading_title; ?></h1>
		</div>
		<div class="content__cont">

			<?php if(!empty($events)) { ?>
				<div class="event__sort row">
					<div class="event__filter col-md-9 col-lg-9">
						<ul class="event__filter-list">
							<li><a href="#" class="event__filter-tab filter__type active">все мероприятия</a></li>
							<?php if($types) { ?>
								<?php foreach($types as $type) { ?>
									<li><a href="#" class="event__filter-tab filter__type" data-type="<?php echo $type['type_id']; ?>"><?php echo $type['title']; ?></a></li>
								<?php } ?>
							<?php } ?>
						</ul>
					</div>
					<div class="event__city col-md-3 col-lg-3">
						<a href="#modal_city" class="event__city-show modalshow">
							<svg class="ico"><use xlink:href="#location" /></svg>
							<span>Все города</span>
						</a>
					</div>
				</div>

				<div class="event__row row">

					<?php foreach($events as $event) { ?>
						<div class="event__outer col-md-6 event-type-<?php echo $event['type_id']; ?> event-city-<?php echo $event['city_id']; ?>">
							<?php if($event['show_event']) { ?>
							<a href="<?php echo $event['href']; ?>" class="event__item link__outer">
								<img src="<?php echo $event['thumb']; ?>" alt="" class="event__img">
								<span class="event__type">
									<span><?php echo $event['type']; ?></span>
									<span><?php echo $event['city']; ?></span>
								</span>
								<span class="event__name"><span class="link"><?php echo $event['title']; ?></span></span>
								<?php if($event['date'] == $event['date_stop']) { ?>
									<span class="event__address"><?php echo $event['date'] . ' ' . $event['date_month'] . ', ' . $event['time_start'] . ' - ' . $event['time_end'] . ', ' . $event['address']; ?></span>
								<?php }
								elseif($event['date'] != $event['date_stop'] && $event['date'] == $event['date_stop']) { ?>
									<span><?php echo $date . ' ' . $date_month; ?> — <?php echo $date_stop . ' ' . $date_stop_month; ?><?php echo  ' ' . $date_year; ?></span>
								<?php }
								else { ?>
									<span class="event__address"><?php echo $event['date'] . ' - ' . $event['date_stop'] . ' ' . $event['date_month'] . ', ' . $event['time_start'] . ' - ' . $event['time_end'] . ', ' . $event['address']; ?></span>
								<?php } ?>
							</a>
						<?php }else{ ?>
							<span class="event__item ">
								<img src="<?php echo $event['thumb']; ?>" alt="" class="event__img">
								<span class="event__type">
									<span><?php echo $event['type']; ?></span>
									<span><?php echo $event['city']; ?></span>
								</span>
								<span class="event__name"><span class=""><?php echo $event['title']; ?></span></span>

								<?php if($event['date'] == $event['date_end']) { ?>
									<span class="event__address"><?php echo $event['date'] . ', ' . $event['time_start'] . ' - ' . $event['time_end'] . ', ' . $event['address']; ?></span>
								<?php }else{ ?>
									<span class="event__address"><?php echo $event['date'] . ' - ' . $event['date_stop'] . ', ' . $event['time_start'] . ' - ' . $event['time_end'] . ', ' . $event['address']; ?></span>
								<?php } ?>
							</span>
						<?php } ?>
						</div>
					<?php } ?>

					<div class="event__empty master__outer master__empty text col-12 d-none">
						<h4>Нет мероприятий, <br>соответствующих выбранным параметрам</h4>
						<div class="master__goto">
							<a href="#" class="link_under filter__reset">Сбросить параметры</a>
						</div>
					</div>

				</div>
			<?php }else{ ?>
				<div class="master__outer master__empty text col-12">
					<h4>Раздел мероприятий обновляется</h4>
					<p>Подождите еще немного ;)</p>
					<div class="master__goto">
						<a href="<?php echo $continue; ?>" class="link_under ">Вернуться на главную</a>
					</div>
				</div>
			<?php } ?>


		</div>

	</div>
</section>

<div class="d-none">
	<div id="modal_city" class="mcity__modal">
		<button type="button" class="modal__close" data-fancybox-close="">
			<svg class="ico ico-center"><use xlink:href="#close"></use></svg>
		</button>
		<div class="mcity__title">
			Выберите город
		</div>
		<div class="mcity__list">
			<?php foreach(array_chunk($cities, ceil(count($cities) / 2)) as $key=>$column) { ?>
				<ul class="list-vert">
					<?php foreach($column as $key2=>$city) { ?>
						<li><a href="#" data-city="<?php echo $city['city_id']; ?>" class="filter__city <?php echo !$city['city_id'] ? 'active' : ''; ?>"><?php echo $city['title']; ?></a></li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
	</div>
</div>
<script>
	changeFilter = function(){
		var 
		$type = $('.filter__type.active').attr('data-type'),
		$city = $('.filter__city.active').attr('data-city'),
		$city_text = $('.filter__city.active').text(),
		$ex = '';

		$('.event__outer').removeClass('d-none');

		if($type) {
			$ex += '.event-type-'+$type;
		}
		if($city) {
			$ex += '.event-city-'+$city;
		}
		if($ex) {
			$('.event__outer:not('+$ex+')').addClass('d-none');
		}

		$('.event__city-show span').text($city_text);

		if(!$('.event__outer:not(.d-none)').length) {
			$('.event__empty').removeClass('d-none');
		}else{
			$('.event__empty').addClass('d-none');
		}
	}

	$(function(){
		$('.filter__reset').on('click', function(e){
			e.preventDefault();
			$('.filter__type.active').removeClass('active');
			$('.filter__city.active').removeClass('active');
			$('.filter__type').eq(0).addClass('active');
			$('.filter__city').eq(0).addClass('active');
			$('.event__empty').addClass('d-none');
			changeFilter();
		})
		$('.filter__city').on('click', function(e){
			e.preventDefault();
			var 
			$self = $(this);
			if(!$self.hasClass('active')) {
				$('.filter__city.active').removeClass('active');
				$self.addClass('active');
				changeFilter();
				$instance = $.fancybox.getInstance();
				if($instance){$instance.close();}
			}
		})
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