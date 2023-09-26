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
					<div class="form-group required">
						<label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
						<div class="col-sm-10">
							<input type="text" name="avmain_banner_title" value="<?php echo $avmain_banner_title; ?>" placeholder="<?php echo $entry_title; ?>"  class="form-control" />
							<?php if($error_title) { ?>
								<div class="text-danger"><?php echo $error_title; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label"><?php echo $entry_link; ?></label>
						<div class="col-sm-10">
							<input type="text" name="avmain_banner_link" value="<?php echo $avmain_banner_link; ?>" placeholder="<?php echo $entry_link; ?>"  class="form-control" />
							<?php if($error_link) { ?>
								<div class="text-danger"><?php echo $error_link; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group ">
						<label class="col-sm-2 control-label" for="input-target"><?php echo $entry_target; ?></label>
						<div class="col-sm-4">
							<div class="btn-group btn-toggle" data-toggle="buttons">
								<label class="btn btn-primary <?php echo $avmain_banner_target == 1 ? 'active' : ''; ?>"><input type="radio" name="avmain_banner_target" value="1"  <?php echo $avmain_banner_target == 1 ? 'checked' : ''; ?>> Да </label>
								<label class="btn btn-default   <?php echo $avmain_banner_target == 0 ? 'active' : ''; ?>"><input type="radio" name="avmain_banner_target" value="0"   <?php echo $avmain_banner_target == 0 ? 'checked' : ''; ?>>  Нет </label>
							</div>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
						<div class="col-sm-10">
							<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
							<input type="hidden" name="avmain_banner_image" value="<?php echo $avmain_banner_image; ?>" id="input-image" />

							<?php if($error_image) { ?>
								<div class="text-danger"><?php echo $error_image; ?></div>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<style>
	.btn-group .btn-default.active{
		color: #fff;
		background-color: #f56b6b;
		border-color: #f24545;
	}
	.btn-group .btn-primary{
		color: #333;
		background-color: #e6e6e6;
		border-color: #adadad;
	}
	.btn-group .btn-primary.active{
		color: #fff;
		background-color: #75a74d;
		border-color: #5c843d;
	}
</style>
<?php echo $footer; ?>
