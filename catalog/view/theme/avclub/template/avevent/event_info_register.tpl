<section id="registration" class="section_reg">

	<div class="reg__cont">
		<div class="reg__data">
			<div class="reg__qty">ОСТАЛОСЬ <?php echo $count; ?> <?php echo $count_text; ?></div>
			<div class="reg__title">Зарегистрируйтесь на мероприятие</div>
			<div class="reg__capt">
				<div class="reg__name"><?php echo $type; ?>.<?php echo $city; ?></div>
				<?php if($price) { ?>
					<div class="reg__price"><?php echo $price; ?> ₽</div>
				<?php } ?>
			</div>
			<div class="reg__attr clearfix">
				<?php if($date == $date_stop) { ?>
				<span><?php echo $date . ' ' . $date_month . ' ' . $date_year; ?></span>
				<?php }
					 elseif($date !== $date_stop && $date_month !== $date_stop_month) { ?>
				<span><?php echo $date . ' ' . $date_month; ?> — <?php echo $date_stop . ' ' . $date_stop_month; ?><?php echo  ' ' . $date_year; ?></span>
				<?php }
					else { ?>
				<span><?php echo $date; ?> — <?php echo $date_stop; ?><?php echo ' ' . $date_month . ' ' . $date_year; ?></span>
				<?php } ?>
				<span><?php echo $time_start; ?> — <?php echo $time_end; ?></span>
				<span><?php echo $address_full; ?></span>
			</div>
			<div class="reg__btn">
				<a href="<?php echo $link; ?>" class="btn btn-red" target="_blank" onclick="yaGoal('clik_zaregestrirovatsy');">
					<span>Зарегистрироваться</span>
				</a>
			</div>
		</div>
	</div>
	<div class="reg__map">
		<div id="map"></div>
	</div>

</section>
<?php if($coord) { ?>
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
	<script>
		ymaps.ready(function () {
			var myMap = new ymaps.Map('map', {
				center: [<?php echo $coord; ?>],
				zoom: 13
			}, {
				searchControlProvider: 'yandex#search'
			});

			myMap.behaviors.disable('scrollZoom');

			myPlacemark = new ymaps.Placemark([<?php echo $coord; ?>], {
				balloonContentHeader: "<?php echo $type; ?>.<?php echo $city; ?>",
				balloonContentBody: "<?php echo $address_full; ?>",
				hintContent: "<?php echo $type; ?>.<?php echo $city; ?>"
			}, {
				iconLayout: 'default#image',
				iconImageHref: '<?php echo $theme_dir; ?>/img/map-label.svg',
				iconImageSize: [40, 46],
				iconImageOffset: [-20, -23],
				hideIconOnBalloonOpen: false,
				openHintOnHover: true
			});

			myMap.geoObjects.add(myPlacemark);

		});
	</script>
	<?php } ?>