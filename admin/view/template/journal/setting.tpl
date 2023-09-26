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
				<h3 class="panel-title"><i class="fa fa-pencil"></i> Настройки журнала</h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-attribute" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-page" data-toggle="tab" aria-expanded="true">Страницы</a></li> 
						<li ><a href="#tab-limit" data-toggle="tab" aria-expanded="true">Лимиты</a></li> 
						<li ><a href="#tab-size" data-toggle="tab" aria-expanded="true">Размеры</a></li> 
					</ul>
					<div id="tabs" class="tab-content">

						<!-- PAGE -->
						<div class="tab-pane active" id="tab-page">
							<ul id="tab-page-nav" class="nav nav-tabs">			
								<?php $count = 0; ?>
								<?php foreach($meta_list as $key=>$meta) {$count++; ?>		
									<li class="<?php echo $count == 1 ? 'active' : ''; ?>"><a href="#tab-page-<?php echo $key; ?>" data-toggle="tab" aria-expanded="false"><?php echo $meta['title']; ?></a></li>
								<?php } ?>
							</ul>
							<div class="tab-content">
								<?php $count = 0; ?>
								<?php foreach($meta_list as $key=>$meta) {$count++; ?>		
									<div class="tab-pane <?php echo $count == 1 ? 'active' : ''; ?>" id="tab-page-<?php echo $key; ?>">
										<div class="form-group">
											<label class="col-sm-2 control-label"><?php echo $entry_description; ?></label>
											<div class="col-sm-10">
												<textarea name="<?php echo $key ?>[description]" placeholder="<?php echo $entry_meta_description; ?>" id="input-page-<?php echo $key; ?>" class="form-control summernote"><?php echo $meta['meta']['description']; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"><?php echo $entry_meta_h1; ?></label>
											<div class="col-sm-10">
												<input type="text" name="<?php echo $key ?>[meta_h1]" value="<?php echo $meta['meta']['meta_h1']; ?>" placeholder="<?php echo $entry_meta_h1; ?>" class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"><?php echo $entry_meta_title; ?></label>
											<div class="col-sm-10">
												<input type="text" name="<?php echo $key ?>[meta_title]" value="<?php echo $meta['meta']['meta_title']; ?>" placeholder="<?php echo $entry_meta_title; ?>" class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"><?php echo $entry_meta_description; ?></label>
											<div class="col-sm-10">
												<textarea name="<?php echo $key ?>[meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" class="form-control"><?php echo $meta['meta']['meta_description']; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label"><?php echo $entry_meta_keyword; ?></label>
											<div class="col-sm-10">
												<textarea name="<?php echo $key ?>[meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" class="form-control"><?php echo $meta['meta']['meta_keyword']; ?></textarea>
											</div>
										</div>
									</div>
								<?php } ?>


							</div>
						</div>
						<!-- # PAGE -->

						<!-- LIMIT -->
						<div class="tab-pane" id="tab-limit">
							<?php foreach($limit_list as $key=>$item) {  ?>
								<div class="form-group required">
									<label class="col-sm-2 control-label"><?php echo $item['title']; ?></label>
									<div class="col-sm-10">
										<input type="text" name="<?php echo $key; ?>" value="<?php echo $item['value']; ?>" placeholder="<?php echo $item['title']; ?>"  class="form-control" />
										<?php if ($item['error']) { ?>
											<div class="text-danger"><?php echo $item['error']; ?></div>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
	
						</div>
						<!-- # LIMIT -->

						<!-- SIZE -->
						<div class="tab-pane" id="tab-size">

							<?php foreach($size_list as $key=>$item) {  ?>
								<div class="form-group required">
									<label class="col-sm-2 control-label"><?php echo $item['title']; ?></label>
									<div class="col-sm-10">
										<div class="row">
											<div class="col-sm-5">
												<input type="text" name="<?php echo $key; ?>_width" value="<?php echo $item['width']; ?>" placeholder="Ширина" class="form-control" />
											</div>
											<div class="col-sm-5">
												<input type="text" name="<?php echo $key; ?>_height" value="<?php echo $item['height']; ?>" placeholder="Высота" class="form-control" />
											</div>
										</div>
										<?php if ($item['error']) { ?>
											<div class="text-danger"><?php echo $item['error']; ?></div>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							
						
						</div>
						<!-- # SIZE -->



					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	<?php if ($ckeditor) { ?>
		<?php foreach($meta_list as $key=>$meta) {$count++; ?>		
			ckeditorInit('input-page-<?php echo $key; ?>', getURLVar('token'));
		<?php } ?>
	<?php } ?>

</script> 
<?php echo $footer; ?>
