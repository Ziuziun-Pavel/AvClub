<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-city" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-city" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
						<div class="col-sm-10">
							<input type="text" name="title" value="<?php echo $title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
							<?php if ($error_title) { ?>
								<div class="text-danger"><?php echo $error_title; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
						<div class="col-sm-10">
							<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
							<input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-href"><?php echo $entry_href; ?></label>
						<div class="col-sm-10">
							<input type="text" name="href" value="<?php echo $href; ?>" placeholder="<?php echo $entry_href; ?>" id="input-href" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php echo $entry_event; ?></label>
						<div class="col-sm-10">
							<div class="event__cont">
								<ul class="event__list">
									<?php if(!empty($events)) { ?>
										<?php foreach($events as $event) { ?>
											<li class="event__item event__item-<?php echo $event['event_id']; ?>" >
												<?php echo $event['title']; ?>
												<button type="button" class="event__remove" ><i class="fa fa-close"></i></button>
												<input type="hidden" name="event[]" value="<?php echo $event['event_id']; ?>">
											</li>
										<?php } ?>
									<?php } ?>
								</ul>
								<input type="text" name="event_search" class="event__input" placeholder="<?php echo $entry_event_placeholder; ?>" autocomplete="off">
							</div>
						</div>
						<?php require_once(DIR_TEMPLATE . 'avevent/event-autocomplete.tpl'); ?>
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
				</form>
			</div>
		</div>
	</div>


</div>

<?php echo $footer; ?>
