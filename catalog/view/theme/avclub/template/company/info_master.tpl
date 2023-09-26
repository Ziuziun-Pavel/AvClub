<?php if(!empty($masters)) { ?>
	<div class="master__row row">

		<?php $m = 0; ?>
		<?php foreach($masters as $master) { $m++; ?>
			<div class="master__outer col-12">
				<?php require(DIR_TEMPLATE . 'avclub/template/_include/master.tpl'); ?>
			</div>
		<?php } ?>		

	</div>
<?php } ?>

<?php /* journals */ ?>
<?php if(!empty($journals)) { ?>
	<div class="icomp__page">
		<div class="news__row news__row-master  or__row row">

			<?php foreach($journals as $journal) { ?>
				<div class="news__outer col-sm-6 col-xl-4">
					<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-article.tpl'); ?>
				</div>
			<?php } ?>
			
		</div>
		<?php if($pagination) { ?>
			<div class="page__row"><?php echo $pagination; ?></div>
		<?php } ?>
	</div>
<?php } ?>
<?php /* # journals */ ?>