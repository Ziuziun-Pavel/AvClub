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
					<div class="form-group ">
						<label class="col-sm-2 control-label" for="input-title">Заголовок</label>
						<div class="col-sm-10">
							<input type="text" name="title" value="<?php echo $title; ?>" placeholder="Заголовок" id="input-title" class="form-control" />
							<?php if ($error_title) { ?>
								<div class="text-danger"><?php echo $error_title; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group ">
						<label class="col-sm-2 control-label" for="input-link">Ссылка</label>
						<div class="col-sm-10">
							<input type="text" name="link" value="<?php echo $link; ?>" placeholder="Ссылка" id="input-link" class="form-control" />
							<?php if ($error_link) { ?>
								<div class="text-danger"><?php echo $error_link; ?></div>
							<?php } ?>
						</div>
					</div>

					<div class="form-group ">
						<label class="col-sm-2 control-label"><?php echo $entry_author; ?></label>
						<div class="col-sm-10">
							<div class="author__cont">
								<ul class="author__list">
									<?php if($author) { ?>
										<li class="author__item author__item-<?php echo $author['author_id']; ?>" data-toggle="tooltip" title="<?php echo $author['exp']; ?>">
											<?php echo $author['name']; ?>
											<button type="button" class="author__remove" ><i class="fa fa-close"></i></button>
											<input type="hidden" name="author_id" value="<?php echo $author['author_id']; ?>">
										</li>
									<?php } ?>
								</ul>
								<input type="text" name="author_search" class="author__input" placeholder="<?php echo $entry_author_placeholder; ?>">
							</div>
							<?php if ($error_author) { ?>
								<div class="text-danger"><?php echo $error_author; ?></div>
							<?php } ?>
						</div>
						<?php require_once(DIR_TEMPLATE . 'visitor/visitor-autocomplete.tpl'); ?>
					</div>
					<div class="form-group ">
						<label class="col-sm-2 control-label"><?php echo $entry_tag; ?></label>
						<div class="col-sm-10">
							<div class="tag__cont">
								<ul class="tag__list">
									<?php if($tags) { ?>
										<?php foreach($tags as $tag) { ?>
											<li class="tag__item tag__item-<?php echo $tag['tag_id']; ?>" value="<?php echo utf8_strtolower($tag['tag']); ?>">
												<?php echo $tag['tag']; ?>
												<button type="button" class="tag__remove" ><i class="fa fa-close"></i></button>
												<input type="hidden" name="tag[]" value="<?php echo $tag['tag_id']; ?>">
											</li>
										<?php } ?>
									<?php } ?>
								</ul>
								<input type="text" name="tag_search" class="tag__input" placeholder="<?php echo $entry_tag_placeholder; ?>">
							</div>
						</div>
						<?php require_once(DIR_TEMPLATE . 'tag/tag-autocomplete.tpl'); ?>
					</div>
				</form>
			</div>
		</div>
	</div>


</div>

<style>
	.btn-toggle .btn:not(.active){
		color: #333;
		background-color: #e6e6e6;
		border-color: #adadad;
	}

</style>
<?php echo $footer; ?>
