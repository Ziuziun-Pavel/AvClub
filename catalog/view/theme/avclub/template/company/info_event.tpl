<div class="icomp__page">
	<div class="row">
		<?php if($events) { ?>
			<?php foreach($events as $event) { ?>
				<div class="event__outer col-lg-6">
					<?php 
					require(DIR_TEMPLATE . 'avclub/template/_include/event.tpl');
					?>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<?php if(!empty($pagination)) { ?>
		<div class="page__row"><?php echo $pagination; ?></div>
	<?php } ?>
</div>