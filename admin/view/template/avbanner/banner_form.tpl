<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-journal" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-journal" class="form-horizontal">
					<div class="form-group required">
						<label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
						<div class="col-sm-10">
							<input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
							<?php if ($error_name) { ?>
								<div class="text-danger"><?php echo $error_name; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label"><?php echo $entry_link; ?></label>
						<div class="col-sm-10">
							<input type="text" name="link" value="<?php echo $link; ?>" placeholder="<?php echo $entry_link; ?>" class="form-control" />
							<?php if ($error_link) { ?>
								<div class="text-danger"><?php echo $error_link; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group ">
						<label class="col-sm-2 control-label" for="input-target"><?php echo $entry_target; ?></label>
						<div class="col-sm-4">
							<div class="btn-group btn-toggle" data-toggle="buttons">
								<label class="btn btn-primary <?php echo $target == 1 ? 'active' : ''; ?>"><input type="radio" name="target" value="1"  <?php echo $target == 1 ? 'checked' : ''; ?>> Да </label>
								<label class="btn btn-default   <?php echo $target == 0 ? 'active' : ''; ?>"><input type="radio" name="target" value="0"   <?php echo $target == 0 ? 'checked' : ''; ?>>  Нет </label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
						<div class="col-sm-4">
							<div class="input-group datetime">
								<input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" id="input-date-start" class="form-control" />
								<span class="input-group-btn">
									<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
							<?php if ($error_date_start) { ?>
								<div class="text-danger"><?php echo $error_date_start; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-date-stop"><?php echo $entry_date_stop; ?></label>
						<div class="col-sm-4">
							<div class="input-group datetime">
								<input type="text" name="date_stop" value="<?php echo $date_stop; ?>" placeholder="<?php echo $entry_date_stop; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" id="input-date-start" class="form-control" />
								<span class="input-group-btn">
									<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
							<?php if ($error_date_stop) { ?>
								<div class="text-danger"><?php echo $error_date_stop; ?></div>
							<?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-image_pc"><?php echo $entry_image_pc; ?></label>
						<div class="col-sm-10">
							<a href="" id="thumb-image_pc" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_pc; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
							<input type="hidden" name="image_pc" value="<?php echo $image_pc; ?>" id="input-image_pc" />
						</div>
					</div>
					<div class="form-group <?php echo $type === 'content' ? 'hidden' : ''; ?>">
						<label class="col-sm-2 control-label" for="input-image_mob"><?php echo $entry_image_mob; ?></label>
						<div class="col-sm-10">
							<a href="" id="thumb-image_mob" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_mob; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
							<input type="hidden" name="image_mob" value="<?php echo $image_mob; ?>" id="input-image_mob" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="status" id="input-status" class="form-control">
								<?php if ($status) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>


				</div>
				<input type="hidden" name="type" value="<?php echo $type; ?>">
			</form>
		</div>
	</div>
</div>



<script>
	$('.datetime').datetimepicker({
		pickDate: true,
		pickTime: true,
		icons: {
			time: 'fa fa-clock-o',
			date: 'fa fa-calendar',
			up: 'fa fa-sort-up',
			down: 'fa fa-sort-down'
		}
	});
</script>
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
