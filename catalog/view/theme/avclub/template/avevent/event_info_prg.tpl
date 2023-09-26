<section id="program" class="section_prg section_prg-double">
	<div class="container">
		<?php if($prg_title) { ?>
			<div class="title etitle">
				<h2><?php echo $prg_title; ?></h2>
			</div>
		<?php } ?>

		<?php if($prg_template) { ?>
			<?php /* DOUBLE */ ?>
			<div class="prg__row prg__row-img row">
				<div class="prg__data col-md-6">

					<?php foreach($prg_list as $prg) { ?>
						<div class="prg__item">
							<div class="date"><?php echo $prg['time_start']; ?> — <?php echo $prg['time_end']; ?></div>
							<div class="prg__name"><?php echo $prg['title']; ?></div>
							<div class="prg__text"><?php echo $prg['text']; ?></div>
							<?php if($prg['image']) { ?>
								<div class="prg__thumb" ><img src="<?php echo $prg['image']; ?>" alt="" ></div>
							<?php } ?>
						</div>
					<?php } ?>

					<?php if($prg_file) { ?>
						<div class="prg__down">
							<a href="<?php echo $prg_file['href']; ?>" class="link_under">Скачать программу мероприятия</a>
							<div class="clearfix"></div>
							<span><?php echo $prg_file['ext']; ?>, <?php echo $prg_file['size']; ?></span>
						</div>
					<?php } ?>

				</div>
				<div class="prg__img col-md-6">
					<div class="prg__image">
						<?php foreach($prg_list as $key=>$prg) { ?>
							<img src="<?php echo $prg['image']; ?>" alt="" class="prg__image-<?php echo $key; ?> <?php echo $key == 0 ? 'active' : ''; ?>">
						<?php } ?>
					</div>

				</div>
			</div>
			<?php /* # DOUBLE */ ?>
		<?php }else{ ?>
			<?php /* DEFAULT */ ?>
			<div class="prg__row prg__row-double row">
				<?php foreach(array_chunk($prg_list, ceil(count($prg_list) / 2)) as $col) { ?>
					<div class="prg__data col-md-6">
						<?php foreach($col as $prg) { ?>
							<div class="prg__item">
								<div class="date"><?php echo $prg['time_start']; ?> — <?php echo $prg['time_end']; ?></div>
								<div class="prg__name"><?php echo $prg['title']; ?></div>
								<div class="prg__text"><?php echo $prg['text']; ?></div>
								<?php if($prg['image']) { ?>
									<div class="prg__thumb" ><img src="<?php echo $prg['image']; ?>" alt="" ></div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if($prg_file) { ?>
					<div class="prg__down">
						<a href="<?php echo $prg_file['href']; ?>" class="link_under">Скачать программу мероприятия</a>
						<div class="clearfix"></div>
						<span><?php echo $prg_file['ext']; ?>, <?php echo $prg_file['size']; ?></span>
					</div>
				<?php } ?>
			</div>
			<?php /* # DEFAULT */ ?>
		<?php } ?>

		
	</div>
</section>