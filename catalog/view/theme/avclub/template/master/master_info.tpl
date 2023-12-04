<?php
echo $header; ?>

<?php 
$theme_dir = 'catalog/view/theme/avclub';

require(DIR_TEMPLATE . 'avclub/template/master/master_info_top.tpl');

require(DIR_TEMPLATE . 'avclub/template/master/master_info_preview.tpl');


if(!empty($speaker_list)) { require(DIR_TEMPLATE . 'avclub/template/master/master_info_speakers.tpl'); }

if($brand_list) { require(DIR_TEMPLATE . 'avclub/template/master/master_info_brands.tpl'); }

?>

<?php echo $column_left; ?>

<?php require(DIR_TEMPLATE . 'avclub/template/master/master_info_contacts.tpl'); ?>

<!--Start of PopMechanic script-->
<script id="popmechanic-script" src="https://static.popmechanic.ru/service/loader.js?c=30968"></script>
<!--End of PopMechanic script-->

<?php echo $footer; ?>