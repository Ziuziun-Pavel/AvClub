<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить</a>
				<button type="submit" form="form-banner" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="Копировать" class="btn btn-default"><i class="fa fa-copy"></i></button>
				<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-banner').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-banner">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
							<td class="text-left"><?php if ($sort == 'b.name') { ?>
								<a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
							<?php } else { ?>
								<a href="<?php echo $sort_title; ?>"><?php echo $column_name; ?></a>
								<?php } ?></td>
								<td class="text-center" style="width: 110px;"><?php echo $column_status; ?></td>
								<td class="text-center" style="width: 180px;"><?php if ($sort == 'b.date_start') { ?>
									<a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
								<?php } else { ?>
									<a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
									<?php } ?></td>
								<td class="text-center" style="width: 180px;"><?php if ($sort == 'b.date_stop') { ?>
									<a href="<?php echo $sort_date_stop; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_stop; ?></a>
								<?php } else { ?>
									<a href="<?php echo $sort_date_stop; ?>"><?php echo $column_date_stop; ?></a>
									<?php } ?></td>
									<td class="text-center" style="width: 90px;"><?php echo $column_viewed; ?></td>
									<td class="text-center" style="width: 90px;"><?php echo $column_click; ?></td>
									<td class="text-right" style="width: 120px;"><?php echo $column_action; ?></td>
								</tr>
							</thead>
							<tbody>
								<?php if ($banners) { ?>
									<?php foreach ($banners as $banner) { ?>
										<tr class="<?php echo (!$banner['status']) ? 'danger' : $banner['class']; ?>">
											<td class="text-center"><?php if (in_array($banner['banner_id'], $selected)) { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $banner['banner_id']; ?>" checked="checked" />
											<?php } else { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $banner['banner_id']; ?>" />
												<?php } ?></td>
												<td class="text-left"><?php echo $banner['name']; ?></td>
												<td class="text-center"><div class=" <?php echo $banner['status'] ? 'text-success' : 'text-danger'; ?>"><?php echo $banner['status'] ? $text_enabled : $text_disabled; ?></div></td>
												<td class="text-center"><div class="btn btn-<?php echo $banner['class']; ?>"><?php echo $banner['date_start']; ?></div></td>
												<td class="text-center"><div class="btn btn-<?php echo $banner['class']; ?>"><?php echo $banner['date_stop']; ?></div></td>
												<td class="text-center"><?php echo $banner['count_viewed']; ?></td>
												<td class="text-center"><?php echo $banner['count_click']; ?></td>
												<td class="text-right">
													<a href="<?php echo $banner['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
	<?php echo $footer; ?>