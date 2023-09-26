<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-menu" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-menu" class="form-horizontal">

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <input type="text" name="title" value="<?php echo $title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
            </div>
          </div>
          <fieldset>
            <legend>Ссылки</legend>
            <?php include(DIR_TEMPLATE . 'themeset/menu_item.tpl'); ?>
          </fieldset>


        </form>
      </div>
    </div>
  </div>
</div>


<script>
  var $max_sort = <?php echo $max_sort + 1; ?>;
  addMenuItem = function($item){
    var
    $button = $($item),
    $panel = $button.closest('.tab-pane'),
    $nav = $panel.find('ul.nav'),
    $index = $nav.find('li').length;

    $html_nav = '<li id="href_' + $index + '"><a href="#tab' + $index + '" data-toggle="tab" aria-expanded="true">Ссылка '+$index+'</a></li>';
    $button.closest('li').before($html_nav);

    $html = '';

    $html += '<div class="tab-pane" id="tab'+$index+'" data-index="'+$index+'">';
    $html += '  <div class="form-group"><label class="col-sm-2 control-label"><span data-toggle="tooltip" title="" data-original-title="<?php echo $help_title;?>"><?php echo $entry_title; ?></span></label><div class="col-sm-10"><input type="text" name="links['+$index+'][title]" value="" placeholder="<?php echo $entry_title; ?>" class="form-control" /></div></div>';
    $html += '  <div class="change__type">';
    $html += '    <div class="form-group"><label class="col-sm-2 control-label"><?php echo $entry_type; ?></label><div class="col-sm-10"><select name="links['+$index+'][type]" class="form-control" onchange="changeType(this);"><option value="category"><?php echo $text_category;?></option><option value="category_type">Категории по типам</option><option value="category_with_children">Категория с подкатегориями</option><option value="manufacturer"><?php echo $text_manufacturer;?></option><option value="standart"><?php echo $text_standart;?></option><option value="information"><?php echo $text_information;?></option><option value="news_category">Блог - категория</option><option value="news_article">Блог - статья</option><option value="href"><?php echo $text_href;?></option></select></div></div>';
    $html += '    <div class="form-group type__item type__category type__category_type type__category_with_children">';
    $html += '      <label class="col-sm-2 control-label"><?php echo $text_category; ?></label>';
    $html += '      <div class="col-sm-10">';
    $html += '        <select name="links['+$index+'][category_id]" class="form-control">';
    <?php foreach($categories as $value){ ?>
      $html += '          <option value="<?php echo $value['category_id']; ?>"><?php echo $value['name'];?></option>';
      <?php 
    } ?>
    $html += '        </select>';
    $html += '      </div>';
    $html += '    </div>';
    $html += '    <div class="form-group type__item type__manufacturer" style="display: none;">';
    $html += '      <label class="col-sm-2 control-label"><?php echo $text_manufacturer; ?></label>';
    $html += '      <div class="col-sm-10">';
    $html += '        <select name="links['+$index+'][manufacturer_id]" class="form-control">';
    $html += '        <option value="0" >-- Не выбрано --</option>';
    <?php foreach($manufacturers as $value){ ?>
      $html += '          <option value="<?php echo $value['manufacturer_id']; ?>" ><?php echo $value['name'];?></option>';
      <?php 
    } ?>
    $html += '        </select>';
    $html += '      </div>';
    $html += '    </div>';
    $html += '    <div class="form-group type__item type__information" style="display: none;">';
    $html += '      <label class="col-sm-2 control-label"><?php echo $text_information; ?></label>';
    $html += '      <div class="col-sm-10">';
    $html += '        <select name="links['+$index+'][information_id]" class="form-control">';
    <?php foreach($informations as $value){ ?>
      $html += '          <option value="<?php echo $value['information_id']; ?>"><?php echo $value['title'];?></option>';
      <?php 
    } ?>
    $html += '        </select>';
    $html += '      </div>';
    $html += '    </div>';

    $html += '    <div class="form-group type__item type__news_article" style="display: none;">';
    $html += '      <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="" data-original-title="Автодополнение">Заголовок статьи</span></label>';
    $html += '      <div class="col-sm-10">';
    $html += '        <input type="text" name="newsblog" value="" placeholder="Заголовок статьи" class="form-control" /><input type="hidden" name="links['+$index+'][news_article]" value="">';

    $html += '        <div class="well well-sm"></div>';
    $html += '      </div>';
    $html += '    </div>';


    $html += '    <div class="form-group type__item type__news_category" style="display: none;">';
    $html += '      <label class="col-sm-2 control-label">Блог - категория</label>';
    $html += '      <div class="col-sm-10">';
    $html += '        <select name="links['+$index+'][news_category_id]" class="form-control">';
    $html += '        <option value="0">-- Не выбрано --</option>';

    <?php foreach($blog_categories as $value){ ?>
      $html += '          <option value="<?php echo $value['category_id']; ?>"><?php echo $value['name'];?></option>';
      <?php 
    } ?>
    $html += '        </select>';
    $html += '      </div>';
    $html += '    </div>';


    $html += '    <div class="form-group type__item type__standart" style="display: none;">';
    $html += '      <label class="col-sm-2 control-label"><?php echo $text_standart; ?></label>';
    $html += '      <div class="col-sm-10">';
    $html += '        <select name="links['+$index+'][standart]" class="form-control">';
    <?php foreach($standart as $value){ ?>
      $html += '          <option value="<?php echo $value['value']; ?>"><?php echo $value['name'];?></option>';
      <?php 
    } ?>
    $html += '        </select>';
    $html += '      </div>';
    $html += '    </div>';
    $html += '    <div class="form-group type__item type__href" style="display: none;"><label class="col-sm-2 control-label"><?php echo $entry_href; ?></label><div class="col-sm-10"><input type="text" name="links['+$index+'][href]" value="" placeholder="<?php echo $entry_href; ?>" class="form-control" /></div></div>';
    $html += '    </div>';

    $html += '    <div class="form-group ">';
    $html += '      <label class="col-sm-2 control-label">Меню</label>';
    $html += '      <div class="col-sm-10">';
    $html += '        <select name="links['+$index+'][submenu]" class="form-control">';
    $html += '        <option value="">-- Не выбрано --</option>';
    <?php foreach($menus as $value){ ?>
      $html += '          <option value="<?php echo $value['menu_id']; ?>"><?php echo $value['name'];?></option>';
      <?php 
    } ?>
    $html += '        </select>';
    $html += '      </div>';
    $html += '    </div>';

    $html += '    <div class="form-group"><label class="col-sm-2 control-label">Изображение</label><div class="col-sm-10"><a href="" id="thumb-image'+$index+'" data-toggle="image" class="img-thumbnail"><img src="<?php echo $item['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="links['+$index+'][image]" value="<?php echo $item['image']; ?>" id="input-image'+$index+'" /></div></div>';

    $html += '    <div class="form-group"><label class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label><div class="col-sm-10"><input type="text" name="links['+$index+'][sort_order]" value="'+$max_sort+'" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></div></div>';
    $html += '    <div class="form-group"><label class="col-sm-2 control-label">Выделение</label><div class="col-sm-10"><select name="links['+$index+'][label]" class="form-control"><option value="1"><?php echo $text_enabled; ?></option><option value="0" selected="selected"><?php echo $text_disabled; ?></option></select></div></div>';
    $html += '    <div class="form-group"><label class="col-sm-2 control-label">На мобильных</label><div class="col-sm-10"><select name="links['+$index+'][mobile]" class="form-control"><option value="1" selected="selected"><?php echo $text_enabled; ?></option><option value="0"><?php echo $text_disabled; ?></option></select></div></div>';
    $html += '    <div class="form-group"><label class="col-sm-2 control-label"><?php echo $entry_status; ?></label><div class="col-sm-10"><select name="links['+$index+'][status]" class="form-control"><option value="1" selected="selected"><?php echo $text_enabled; ?></option><option value="0"><?php echo $text_disabled; ?></option></select></div></div>';


    $html += '    <div class="form-group"><div class="col-sm-10 col-sm-push-2"><a href="#" class="btn btn-danger tab_delete" data-id="'+$index+'">Удалить</a></div></div>'; 
    $html += '</div>';

    $max_sort++;

    $('#tabs').append($html);
    $('#href_' + $index + ' a').click();
    $('[data-toggle="tooltip"]').tooltip({container: 'body', html: true});

    $('input[name=\'newsblog\']').autocomplete({
      'source': function(request, response) {
        $.ajax({
          url: 'index.php?route=newsblog/article/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item['name'],
                value: item['article_id']
              }
            }));
          }
        });
      },
      'select': function(item) {
        $('input[name=\'newsblog\']').val('');

        $(this).siblings('.well').html('<div><i class="fa fa-minus-circle article_remove"></i> ' + item['label'] + '</div>');
        $(this).siblings('input[type="hidden"]').val(item['value']);
      }
    });
  }

  changeType = function($item) {
    var
    $self = $($item),
    $value = $self.val(),
    $parent = $($item).closest('.change__type');
    $parent.find('.type__item').hide();
    $parent.find('.type__'+$value).show();
  }




</script>

<script>
  $(function(){
    $(document).on('click', '.tab_delete', function(e){
      e.preventDefault();
      if(confirm('Вы уверены?')){
        var 
        $index = $(this).closest('.tab-pane').attr('data-index');
        $('#tab-'+$index).remove();
        $('#href-'+$index).remove();
        if($('#tab-menu .nav').find('li').length > 1){
          $('#tab-menu .nav').find('li').eq(0).find('a').trigger('click');
        }
      }
    })

    $(document).on('click', '.row__delete', function(e){
      e.preventDefault();
      if(confirm('Вы уверены?')){
        var 
        $row = $(this).closest('tr');
        $row.remove();
      }
    })
    

  })
</script>
<script>
  $('input[name=\'newsblog\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=newsblog/article/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item['name'],
              value: item['article_id']
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'newsblog\']').val('');

      $(this).siblings('.well').html('<div><i class="fa fa-minus-circle article_remove"></i> ' + item['label'] + '</div>');
      $(this).siblings('input[type="hidden"]').val(item['value']);
    }
  });
  $(document).on('click', '.article_remove', function(){
    $(this).closest('.well').siblings('input[type="hidden"]').val('');
    $(this).parent().remove();
    
  })
</script>

<style>
  #nav_button .btn{
    width: 100%;
  }
  .tabs-left > .nav-tabs {
    border-bottom: 0;
  }

  .tab-content > .tab-pane,
  .pill-content > .pill-pane {
    display: none;
  }

  .tab-content > .active,
  .pill-content > .active {
    display: block;
  }

  .tabs-left > .nav-tabs > li,
  .tabs-right > .nav-tabs > li {
    float: none;
  }

  .tabs-left > .nav-tabs > li > a,
  .tabs-right > .nav-tabs > li > a {
    min-width: 74px;
    margin-right: 0;
    margin-bottom: 3px;
  }



  .tabs-left > .nav-tabs > li > a {
    margin-right: -1px;
    -webkit-border-radius: 4px 0 0 4px;
    -moz-border-radius: 4px 0 0 4px;
    border-radius: 4px 0 0 4px;
  }

  .tabs-left > .nav-tabs > li > a:hover,
  .tabs-left > .nav-tabs > li > a:focus {
    border-color: #eeeeee #dddddd #eeeeee #eeeeee;
  }

  .tabs-left > .nav-tabs .active > a,
  .tabs-left > .nav-tabs .active > a:hover,
  .tabs-left > .nav-tabs .active > a:focus {
    border-color: #ddd transparent #ddd #ddd;
    *border-right-color: #ffffff;
  }

  input[type="color"]{
    height: 50px;
    padding: 5px;
  }
</style>


<?php echo $footer; ?>