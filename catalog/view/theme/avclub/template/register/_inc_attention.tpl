<?php if(!empty($attention) && !empty($attention_text)) { ?>
	<div class="regdata__attention">
		<svg class="icow"><use xlink:href="catalog/view/theme/avclub/img/sprite.svg#attention">
		</svg>
		<?php echo html_entity_decode($attention_text); ?>
	</div>
	<?php } ?>