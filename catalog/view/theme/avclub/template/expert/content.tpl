<?php if($type === 'all' && !empty($bio) && $page == 1 ) { ?>

<?php } ?>
<?php foreach($results as $item) { ?>
	<a href="<?php echo $item['href']; ?>" class="xprt__item xprt__item-<?php echo $item['type']; ?> link__outer">
		<span class="xprt__top">
			<span class="xprt__date"><?php echo $item['date']; ?></span>
			<span class="xprt__type"><?php echo $item['type_text']; ?></span>
		</span>
		<span class="xprt__content">
			<?php if(!in_array($item['type'], array('opinion'))) { ?>
				<span class="xprt__img">
					<span class="xprt__image"><img src="<?php echo $item['thumb']; ?>" alt=""></span>
				</span>
			<?php } ?>
			<span class="xprt__info">

				<?php if($item['type'] === 'opinion') { ?>
					<span class="xprt__opinion">
						<span class="xprt__opinion_inner">
							<span class="xprt__opinion_img"><img src="<?php echo $item['thumb']; ?>" alt=""></span>
							<span class="xprt__opinion_name"><?php echo $item['author']; ?></span>
							<span class="xprt__opinion_exp"><?php echo $item['exp']; ?></span>
						</span>
					</span>
				<?php } ?>
				<?php if($item['type'] === 'online') { ?>
					<span class="xprt__author"><?php echo $item['author']; ?></span>
					<span class="xprt__speaker">спикер</span>
				<?php } ?>

				<span class="xprt__data">
					<span class="xprt__name"><?php echo $item['title']; ?></span>
					<?php if(!empty($item['preview']) && !in_array($item['type'], array('opinion', 'case'))) { ?>
						<span class="xprt__sub"><?php echo $item['preview']; ?></span>
					<?php } ?>
					<?php if($item['type'] === 'case' && !empty($item['case']['attr'])) { ?>
						<span class="xprt__case">
							<?php foreach($journal['case']['attr'] as $attr) { ?>
								<?php if(!$attr['catalog']){continue;} ?>
								<span class="xprt__case_item"><?php echo $attr['title']; ?>: <?php echo $attr['text']; ?></span>
							<?php } ?>
						</span>
					<?php } ?>
				</span>

				<span class="xprt__more">
					<span class="link link_under">Подробнее</span>
				</span>
			</span>
		</span>

	</a>
<?php } ?>
<?php if($pagination) { ?>
	<div class="page__row col-12">
		<?php echo $pagination; ?>
	</div>
	<?php } ?>