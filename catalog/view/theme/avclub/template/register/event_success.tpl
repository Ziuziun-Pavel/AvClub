<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_attention.tpl'); ?>
<div class="regdata__title"><?php echo html_entity_decode($title); ?></div>

<?php if(!empty($type) && $type === 'forum') { ?>
	<div class="regdata__info">
		<?php if(!empty($price)) { ?>
			<div><strong>Цена билета:</strong> <?php echo $price; ?></div>
		<?php } ?>
		<?php if($promocode) { ?>
			<div><strong>Промокод:</strong> <?php echo $promocode; ?></div>
		<?php } ?>
	</div>
	<?php } ?>
<?php require(DIR_TEMPLATE . 'avclub/template/register/_inc_fail.tpl'); ?>
	<script>
		yaGoal('promokod');
	</script>