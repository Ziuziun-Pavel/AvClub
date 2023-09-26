<?= $header; ?><?= $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>                
                <button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?= $text_confirm; ?>') ? $('#form-shortcodes').submit() : false;"><i class="fa fa-trash-o"></i></button>
            </div>
            <h1><?= $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?= $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?= $heading_title; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?= $delete; ?>" method="post" enctype="multipart/form-data" id="form-shortcodes">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                    <td class="text-left"><?php if ($sort == 'admin_name') { ?>
                                            <a href="<?= $sort_admin_name; ?>" class="<?= strtolower($order); ?>"><?= $column_admin_name; ?></a>
                                        <?php } else { ?>
                                            <a href="<?= $sort_admin_name; ?>"><?= $column_admin_name; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-left"><?php if ($sort == 'name') { ?>
                                            <a href="<?= $sort_name; ?>" class="<?= strtolower($order); ?>"><?= $column_name; ?></a>
                                        <?php } else { ?>
                                            <a href="<?= $sort_name; ?>"><?= $column_name; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-left"><?php if ($sort == 'type') { ?>
                                            <a href="<?= $sort_type; ?>" class="<?= strtolower($order); ?>"><?= $column_type; ?></a>
                                        <?php } else { ?>
                                            <a href="<?= $sort_type; ?>"><?= $column_type; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-right"><?= $column_action; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($shortcodes) { ?>
                                    <?php foreach ($shortcodes as $shortcode) { ?>
                                        <tr>
                                            <td class="text-center"><?php if (in_array($shortcode['id'], $selected)) { ?>
                                                    <input type="checkbox" name="selected[]" value="<?= $shortcode['id']; ?>" checked="checked" />
                                                <?php } else { ?>
                                                    <input type="checkbox" name="selected[]" value="<?= $shortcode['id']; ?>" />
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?= $shortcode['admin_name'] ?>
                                            </td>
                                            <td>
                                                [<?= $shortcode['name'] ?>]
                                            </td>
                                            <td>
                                                <?= $shortcode['type'] ?>
                                            </td>
                                            <td class="text-right"><a href="<?php echo $shortcode['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
<?= $footer; ?>
