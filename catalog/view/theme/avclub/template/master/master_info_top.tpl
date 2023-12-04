	<section class="linetop">
		<div class="container">

			<div class="linetop--cont">
				<div class="linetop--data">
					<div class="linetop--tag">
						<span>онлайн</span>
						<span><?php
						if($type == 'meetup') {
							echo 'Митап';
						} else {
							echo 'Мастер-класс';
						}

						?>
						</span>
					</div>
					<div class="linetop--time">
						<span><?php echo $date; ?></span>
						<span><?php echo $time_start; ?> — <?php echo $time_end; ?> МСК</span>
					</div>
					<div class="linetop--title">
						<?php echo $heading_title; ?>
					</div>
					<div class="linetop--text">
						<?php echo html_entity_decode($description); ?>
					</div>
					<a href="<?php echo $link; ?>" class="linetop--btn btn btn-invert" target="_blank">
						<span>Зарегистрироваться</span>
					</a>
				</div>
				<div class="linetop--img">
					<img src="<?php echo $image; ?>" alt="" style="width: 100%;">
				</div>
			</div>

		</div>
	</section>
