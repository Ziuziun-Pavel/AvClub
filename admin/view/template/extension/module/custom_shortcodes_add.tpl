<?= $header; ?><?= $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-shortcode" data-toggle="tooltip" title="<?= $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?= $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-shortcode" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-admin_name"><?= $entry_admin_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="shortcode[admin_name]" value="<?= $shortcode['admin_name']; ?>" placeholder="<?= $entry_admin_name; ?>" id="input-admin_name" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-name"><?= $entry_name; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="shortcode[name]" value="<?= $shortcode['name']; ?>" placeholder="<?= $entry_name; ?>" id="input-name" class="form-control" />
                            <?php if ($error_name) { ?>
                                <div class="text-danger"><?= $error_name; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-type"><?= $entry_type; ?></label>
                        <div class="col-sm-10">
                            <select name="shortcode[type]" id="input-type" class="form-control">
                                <option <?=$shortcode['type'] == 'html' ? 'selected' : ''?>>html</option>
                                <option <?=$shortcode['type'] == 'php' ? 'selected' : ''?>>php</option>
                                <option <?=$shortcode['type'] == 'js' ? 'selected' : ''?>>js</option>
                            </select>                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $footer; ?>