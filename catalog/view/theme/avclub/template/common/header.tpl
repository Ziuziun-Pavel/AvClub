<?php $theme_dir = 'catalog/view/theme/avclub'; ?>

<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $title;  ?></title>
	<base href="<?php echo $base; ?>" />
	<?php if ($description) { ?>
		<meta name="description" content="<?php echo $description; ?>" />
	<?php } ?>
	<?php if ($keywords) { ?>
		<meta name="keywords" content= "<?php echo $keywords; ?>" />
	<?php } ?>
	<meta property="og:title" content="<?php echo $title; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo $og_url; ?>" />
	<?php if ($og_image) { ?>
		<meta property="og:image" content="<?php echo $og_image; ?>" />
	<?php } else { ?>
		<meta property="og:image" content="<?php echo $logo; ?>" />
	<?php } ?>
	<meta property="og:site_name" content="<?php echo $name; ?>" />


	<?php foreach ($styles as $style) { ?>
		<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
	<?php } ?>
	<link rel="stylesheet" href="<?php echo $theme_dir; ?>/css/libs.min.css">
	<link rel="stylesheet" href="<?php echo $theme_dir; ?>/css/style.min.css?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/css/style.min.css') ?>">
	<link rel="stylesheet" href="<?php echo $theme_dir; ?>/css/online.min.css?v=<?php echo filectime(DIR_TEMPLATE . 'avclub/css/style.min.css') ?>">

	<?php foreach ($links as $link) { ?>
		<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
	<?php } ?>

	<script src="<?php echo $theme_dir; ?>/js/jquery.min.js"></script>
	<script src="<?php echo $theme_dir; ?>/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo $theme_dir; ?>/libs/bootstrap/js/bootstrap.min.js"></script>
	<?php foreach ($scripts as $script) { ?>
		<script src="<?php echo $script; ?>" type="text/javascript"></script>
	<?php } ?>
	<?php foreach ($analytics as $analytic) { ?>
		<?php echo $analytic; ?>
	<?php } ?>

		<!--[if lt IE 9]>
		<script src="<?php echo $theme_dir; ?>/js/ie.min.js"></script>
	<![endif]-->

	<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
	(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
		m[i].l=1*new Date();
		for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
			k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

	ym(26949891, "init", {
		clickmap:true,
		trackLinks:true,
		accurateTrackBounce:true,
		webvisor:true
	});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/26949891" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script>
	yaGoal = function(goal = '') {
		if(typeof ym !== "undefined") {
			ym(26949891, 'reachGoal', goal);
		}
	}
</script>


	<!-- Facebook Pixel Code -->
	<noscript><img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=1049051051775467&ev=PageView&noscript=1"
		/></noscript>
		<!-- DO NOT MODIFY -->
		<!-- End Facebook Pixel Code -->
	</head>
	<body class="<?php echo $class; ?>">
		<!-- Google Tag Manager (noscript) -->
		<noscript><ifr ame src="https://www.googletagmanager.com/ns.html?id=GTM-569NTJX"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->

			<?php if(!empty($branding['pc']) || !empty($branding['mob'])) { ?>
				<div class="branding">
					<?php echo $branding['link'] ? '<a href="'.$branding['link'].'" ' . ($branding['target'] ? 'target="_blank"' : ''). ' class="' . (!empty($branding['banner_id']) ? 'banner_click' : '') . '" data-id="' . (!empty($branding['banner_id']) ? $branding['banner_id'] : '0') . '">' : '<span>'; ?>

					<?php if(!empty($branding['pc'])) { ?>
						<img src="<?php echo $branding['pc']; ?>" alt="" class="branding__img-pc">
					<?php } ?>
					<?php if(!empty($branding['mob'])) { ?>
						<img src="<?php echo $branding['mob']; ?>" alt="" class="branding__img-mob" >
					<?php } ?>

					<?php echo $branding['link'] ? '</a>' : '</span>'; ?>
				</div>
				<script>
					$.ajax({
						type: "POST",
						url: "index.php?route=themeset/themeset/updateBannerView",
						dataType: "json",
						data: {banner_id: <?php echo $branding['banner_id']; ?>}
					});
				</script>
			<?php } ?>

			<div class="d-none">

				<div id="modal_search" class="modsearch__cont">
					<button type="button" class="modal__close" data-fancybox-close>
						<svg class="ico ico-center"><use xlink:href="#close" /></svg>
					</button>

					<div class="modsearch__inner container">

						<form action="#" class="search__form search__form-page">

							<div class="search__inp">
								<input type="text" name="search" class="search__input" placeholder="Что вы ищете?">
								<button type="submit" class="search__submit">
									<svg class="ico ico-center"><use xlink:href="#long-arrow-right" /></svg>
								</button>
							</div>
							<div class="clearfix"></div>
							<div class="search__bottom row">
								<div class="search__filter col-12">
									<label class="search__radio">
										<input type="radio" name="search_type" value="" checked>
										<span>Весь сайт</span>
									</label>
									<label class="search__radio">
										<input type="radio" name="search_type" value="journal">
										<span>Журнал</span>
									</label>
									<label class="search__radio">
										<input type="radio" name="search_type" value="master">
										<span>Онлайн-события</span>
									</label>
									<label class="search__radio">
										<input type="radio" name="search_type" value="event">
										<span>Мероприятия</span>
									</label>
									<label class="search__radio">
										<input type="radio" name="search_type" value="company">
										<span>Компании</span>
									</label>
								</div>
							</div>

						</form>

					</div>

				</div>

				<div id="modal_menu" class="mmenu__cont">
					<div class="container">

						<div class="mmenu__login">
							<?php if(!empty($logged)) { ?>
							<a href="<?php echo $account; ?>" class="link__outer">
								<svg class="ico ico-login"><use xlink:href="#login" /></svg>
								<span class="link">Кабинет</span>
							</a>
						<?php }else{ ?>
							<a href="<?php echo $login; ?>" class="link__outer">
								<svg class="ico ico-login"><use xlink:href="#login" /></svg>
								<span class="link">Войти</span>
							</a>
						<?php } ?>
						</div>
						<ul class="mmenu__menu list-vert">
							<?php foreach($menu as $item) { ?>
								<li class="mmenu__parent">
									<a href="<?php echo $item['href']; ?>" class="link"><?php echo $item['title']; ?></a>
									<?php if($item['active'] && $item['children']) { ?>
										<ul class="mmenu__child list-vert clearfix">
											<?php foreach($item['children'] as $key=>$child) { ?>
												<?php if($key === 'tag') { ?>
													<li><a href="#modal_tag" class="link mmenu__tag modalshow"><svg class="ico"><use xlink:href="#hash" /></svg> <?php echo $child['title']; ?></a></li>
												<?php }else{ ?>
													<li><a href="<?php echo $child['href']; ?>" class="link"><?php echo $child['title']; ?></a></li>
												<?php } ?>
											<?php } ?>
										</ul>
									<?php } ?>
								</li>
							<?php } ?>
							<?php if(!empty($adv['href'])) { ?>
								<li class="mmenu__parent"><a href="<?php echo $adv['href']; ?>" class="link" target="_blank"><?php echo $adv['title'] ? $adv['title'] : 'Рекламодателям';  ?></a></li>
							<?php } ?>
						</ul>
						<ul class="mmenu__soc soc__list list-hor clearfix">
							<?php foreach($themeset_soc as $key=>$link) { ?>
								<?php if(!$link){continue;} ?>
								<li><a href="<?php echo $link; ?>" target="_blank" class="soc__list-<?php echo $key; ?>"><svg><use xlink:href="#soc-<?php echo $key; ?>" /></svg></a>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>

				<?php echo $tag; ?>

			</div>

			<?php echo $fixed; ?>

			<div class="page__outer">
				<?php

				if(isset($type_page) && $type_page === 'event') {
					require(DIR_TEMPLATE . 'avclub/template/common/header_event.tpl');
				} elseif(strpos($_SERVER['REQUEST_URI'], '/master/') !== false) {

					require(DIR_TEMPLATE . 'avclub/template/common/header_master.tpl');
				}
				else{
					require(DIR_TEMPLATE . 'avclub/template/common/header_default.tpl');
				}


				?>

