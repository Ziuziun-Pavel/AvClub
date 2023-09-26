<div id="modal_tag" class="mtag__cont">
	<button type="button" class="modal__close" data-fancybox-close>
		<svg class="ico ico-center"><use xlink:href="#close" /></svg>
	</button>
	<div class="container">
		<div class="mtag__title title">#Теги</div>
		<div class="tag__row row">

			<?php foreach(array_chunk($tags, ceil(count($tags) / 4)) as $key=>$column) { ?>
				<ul class="tag__list order-<?php echo $key+1; ?> list-vert col-sm-6 col-md-3">
					<?php foreach($column as $tag) { ?>
						<li><a href="<?php echo $tag['href']; ?>" class="link"><?php echo $tag['title']; ?></a></li>
					<?php } ?>
				</ul>
			<?php } ?>

		</div>
		<div class="mtag__more">
			<a href="<?php echo $link; ?>" class="link_under">СМОТРЕТЬ ВСЕ ТЕГИ</a>
		</div>
	</div>
</div>