<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
			<div class="alert alert-danger">
				<i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		<?php if ($success) { ?>
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-attribute" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="journal_telegram_status" class="form-control">
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" <?php echo $journal_telegram_status == 0 ? 'selected' : ''; ?>><?php echo $text_disabled; ?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_link; ?></label>
						<div class="col-sm-10">
							<input type="text" name="journal_telegram_link" value="<?php echo $journal_telegram_link; ?>" placeholder="<?php echo $entry_link; ?>"  class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_text; ?></label>
						<div class="col-sm-10">
							<input type="text" name="journal_telegram_text" value="<?php echo $journal_telegram_text; ?>" placeholder="<?php echo $entry_text; ?>"  class="form-control" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php echo $footer; ?>
