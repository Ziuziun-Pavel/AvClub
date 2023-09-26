<?php echo $header; ?>

<?php 
$theme_dir = 'catalog/view/theme/avclub';

require(DIR_TEMPLATE . 'avclub/template/avevent/event_info_top.tpl');

foreach($template as $key=>$item) {

  if(!$item['status']) {continue;}

  switch($key) {
    
    case 'video':
    if($video) { require(DIR_TEMPLATE . 'avclub/template/avevent/event_info_video.tpl');}
    break;

    case 'brand':
    if($brand_list) { require(DIR_TEMPLATE . 'avclub/template/avevent/event_info_brand.tpl');}
    break;

    case 'speaker':
    if($speaker_list) { require(DIR_TEMPLATE . 'avclub/template/avevent/event_info_speaker.tpl');}
    break;

    case 'present':
    if($present_list) { require(DIR_TEMPLATE . 'avclub/template/avevent/event_info_present.tpl');}
    break;

    case 'insta':
    if($insta_list) { require(DIR_TEMPLATE . 'avclub/template/avevent/event_info_insta.tpl');}
    break;

    case 'prg':
    if($prg_list) { require(DIR_TEMPLATE . 'avclub/template/avevent/event_info_prg.tpl');}
    break;

    case 'plus':
    if($plus_list) { require(DIR_TEMPLATE . 'avclub/template/avevent/event_info_plus.tpl');}
    break;

    case 'register':
    require(DIR_TEMPLATE . 'avclub/template/avevent/event_info_register.tpl');
    break;

    case 'ask':
    if($ask_list) { require(DIR_TEMPLATE . 'avclub/template/avevent/event_info_ask.tpl');}
    break;

    default:
    break;

}

}

?>

<!--Start of PopMechanic script-->
<script id="popmechanic-script" src="https://static.popmechanic.ru/service/loader.js?c=30968"></script>
<!--End of PopMechanic script-->

<?php echo $footer; ?>