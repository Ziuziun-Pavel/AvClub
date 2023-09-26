<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $meta; ?>" data-toggle="tooltip" title="Мета-данные" class="btn btn-warning" style="margin-right: 35px;"><i class="fa fa-info"></i> Мета-данные</a>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить</a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-journal').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
  <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
<?php } ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
  </div>
  <div class="panel-body">
    <div class="well">
      <div class="row">
        <div class="<?php echo $type === 'video' ? 'col-md-5' : 'col-sm-9 col-md-10'; ?>">
          <div class="form-group">
            <label class="control-label" for="input-name"><?php echo $column_title; ?></label>
            <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $column_title; ?>" id="input-name" class="form-control" />
          </div>
        </div>
        <?php if($type === 'video') { ?>
          <div class="col-md-5">
            <div class="form-group">
              <label class="control-label" for="input-status"><?php echo $entry_master_old; ?></label>
              <select name="filter_master_old" id="input-status" class="form-control">
                <option value="*"></option>
                <?php if ($filter_master_old) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                <?php } ?>
                <?php if (!$filter_master_old && !is_null($filter_master_old)) { ?>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } else { ?>
                  <option value="0"><?php echo $text_no; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        <?php } ?>
        <div class="col-sm-3 col-md-2 pull-right">
          <div class="form-group">
            <label class="control-label hidden-xs">&nbsp;</label>
            <div class="clearfix"></div>
            <button type="button" id="button-filter" class="btn btn-block btn-primary"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
          </div>
        </div>
      </div>
    </div>
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-journal">
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td class="text-left"><?php if ($sort == 'jd.title') { ?>
                <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
              <?php } else { ?>
                <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                <?php } ?></td>
                <?php if($type === 'video') { ?>
                  <td class="text-center" style="width: 110px;"><?php echo $column_master_old; ?></td>
                <?php } ?>
                <td class="text-center" style="width: 110px;"><?php echo $column_status; ?></td>
                <td class="text-center" style="width: 180px;"><?php if ($sort == 'j.date_available') { ?>
                  <a href="<?php echo $sort_date_available; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_available; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_date_available; ?>"><?php echo $column_date_available; ?></a>
                  <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($journals) { ?>
                  <?php foreach ($journals as $journal) { ?>
                    <tr class="<?php echo (!$journal['status'] || !$journal['started']) ? 'danger' : ''; ?>">
                      <td class="text-center"><?php if (in_array($journal['journal_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $journal['journal_id']; ?>" checked="checked" />
                      <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $journal['journal_id']; ?>" />
                        <?php } ?></td>
                        <td class="text-left"><?php echo $journal['title']; ?></td>
                        <?php if($type === 'video') { ?>
                          <td class="text-center">
                            <?php if($journal['master_old']) { ?>
                              <div class="btn btn-warning"><?php echo $text_yes; ?></div>
                            <?php }else{ ?>
                              -
                            <?php } ?>
                          </td>
                        <?php } ?>
                        <td class="text-center"><div class=" <?php echo $journal['status'] ? 'text-success' : 'text-danger'; ?>"><?php echo $journal['status'] ? $text_enabled : $text_disabled; ?></div></td>
                        <td class="text-center"><div class="btn <?php echo $journal['started'] ? 'btn-success' : 'btn-danger'; ?>"><?php echo $journal['date_available']; ?></div></td>
                        <td class="text-right">
                          <?php if(!empty($public_status)) { ?>
                            <a href="<?php echo $journal['public']; ?>" data-toggle="tooltip" title="<?php echo $button_status; ?>" class="btn btn-<?php echo $journal['status'] ? 'success' : 'danger'; ?>" style="margin-right: 20px;"><i class="fa fa-check-square"></i></a>
                          <?php } ?>
                          <?php if($journal['status'] && $journal['started']) { ?>
                            <a href="<?php echo $journal['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info" target="_blank"><i class="fa fa-eye"></i></a>
                          <?php }else{ ?>
                            <a href="<?php echo $journal['preview']; ?>" data-toggle="tooltip" title="<?php echo $button_preview; ?>" class="btn btn-warning" target="_blank"><i class="fa fa-eye"></i></a>
                          <?php } ?>
                          <a href="<?php echo $journal['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        </td>
                      </tr>
                    <?php } ?>
                  <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </form>
          <div class="row">
            <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
            <div class="col-sm-6 text-right"><?php echo $results; ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
    $('#button-filter').on('click', function() {
      url = 'index.php?route=journal/journal/<?php echo $type; ?>&token=<?php echo $token; ?>';

      var filter_name = $('input[name=\'filter_name\']').val();

      if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
      }

      if($('select[name=\'filter_master_old\']').length) {
        var filter_master_old = $('select[name=\'filter_master_old\']').val();

        if (filter_master_old != '*') {
          url += '&filter_master_old=' + encodeURIComponent(filter_master_old); 
        } 
      }
      

      location = url;
    });
  </script> 
  <script type="text/javascript">
    $('input[name=\'filter_name\']').autocomplete({
      'source': function(request, response) {
        $.ajax({
          url: 'index.php?route=journal/journal/autocomplete&filter_type=<?php echo $type; ?>&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',     
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item['name'],
                value: item['journal_id']
              }
            }));
          }
        });
      },
      'select': function(item) {
        $('input[name=\'filter_name\']').val(item['label']);
      } 
    });
  </script> 
  <?php echo $footer; ?>