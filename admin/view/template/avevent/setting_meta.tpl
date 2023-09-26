<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-meta" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<h3 class="panel-title"><i class="fa fa-pencil"></i> Meta <?php echo $title; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="form-meta">
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_bread; ?></label>
						<div class="col-sm-10">
							<input type="text" name="av_meta_event[bread]" value="<?php echo $meta['bread']; ?>" placeholder="<?php echo $entry_bread; ?>" class="form-control" />
							<?php if (!empty($error_bread)) { ?>
								<div class="text-danger"><?php echo $error_bread; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_meta_h1; ?></label>
						<div class="col-sm-10">
							<input type="text" name="av_meta_event[meta_h1]" value="<?php echo $meta['meta_h1']; ?>" placeholder="<?php echo $entry_meta_h1; ?>" class="form-control" />
							<?php if (!empty($error_meta_h1)) { ?>
								<div class="text-danger"><?php echo $error_meta_h1; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_description; ?></label>
						<div class="col-sm-10">
							<textarea name="av_meta_event[description]" placeholder="<?php echo $entry_meta_description; ?>" id="input-description" class="form-control summernote"><?php echo $meta['description']; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_meta_title; ?></label>
						<div class="col-sm-10">
							<input type="text" name="av_meta_event[meta_title]" value="<?php echo $meta['meta_title']; ?>" placeholder="<?php echo $entry_meta_title; ?>" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_meta_description; ?></label>
						<div class="col-sm-10">
							<textarea name="av_meta_event[meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" class="form-control"><?php echo $meta['meta_description']; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_meta_keyword; ?></label>
						<div class="col-sm-10">
							<textarea name="av_meta_event[meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" class="form-control"><?php echo $meta['meta_keyword']; ?></textarea>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	<?php if ($ckeditor) { ?>
		ckeditorInit('input-description', getURLVar('token'));
	<?php } ?>

</script> 
<?php echo $footer; ?>
