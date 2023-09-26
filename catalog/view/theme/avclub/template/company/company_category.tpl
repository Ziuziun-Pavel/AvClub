<?php echo $header; ?>
<?php echo $content_top; ?>
<section class="section_content section_company-list">
	<div class="container">

		<?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

		<div class="content__title">
			<h1><?php echo $heading_title; ?></h1>
		</div>
		<div class="content__cont">

			<?php echo $filter; ?>

			<div class="company__row row">

				<div class="company__content col-lg-8 col-xl-9">

					<?php echo $company_list; ?>

				</div>

				<div class="aside col-lg-4 col-xl-3">
					<?php if(!empty($banner)) { ?>
						<div class="company__banner d-none d-lg-block">
							<?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-banner.tpl'); ?>
						</div>
					<?php } ?>
				</div>
			</div>

		</div>

	</div>
</section>

<script src="catalog/view/theme/avclub/js/company-filter.js?v=14022022"></script>

<?php echo $content_bottom; ?>
<?php echo $footer; ?>