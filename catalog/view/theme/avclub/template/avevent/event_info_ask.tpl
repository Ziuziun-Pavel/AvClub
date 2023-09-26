<section id="question" class="section_quest">
	<div class="container">
		<?php if($ask_title) { ?>
			<div class="title etitle">
				<h2><?php echo $ask_title; ?></h2>
			</div>
		<?php } ?>
		<div class="quest__list">

			<?php foreach($ask_list as $key=>$ask) { ?>
				<div class="quest__item <?php echo $key == 0 ? 'active' : ''; ?>">
					<div class="quest__head">
						<?php echo nl2br($ask['title']); ?>
						<div class="quest__btn"></div>
					</div>
					<div class="quest__text" <?php echo $key == 0 ? 'style="display: block;"' : ''; ?>>
						<?php echo nl2br($ask['text']); ?>
					</div>
				</div>
			<?php } ?>

		</div>
	</div>
</section>