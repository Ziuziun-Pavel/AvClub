<?php $theme_dir = 'catalog/view/theme/avclub'; ?>
<section class="foot__before"></section>
</div>
<footer class="footer">
	<div class="container">

		<div class="row foot__top">

			<div class="foot__info col-md-6">
				<div class="row">
					<div class="col-sm-6 col-md-12">
						<div class="foot__logo">
							<?php $logo_img = $theme_dir . '/images/logo.svg'; ?>
							<?php if ($home == $og_url) { ?>
								<span>
									<img src="<?php echo $logo_img; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" />
								</span>
							<?php } else { ?>
								<a href="<?php echo $home; ?>"><img src="<?php echo $logo_img; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a>
							<?php } ?>
						</div>
						<?php if(!empty($themeset_logo_text)) { ?>
							<div class="foot__text">
								<?php echo $themeset_logo_text; ?>
							</div>
						<?php } ?>
					</div>
					<div class="col-sm-6 col-md-12">
						<ul class="soc__list list-hor">
							<?php foreach($themeset_soc as $key=>$link) { ?>
								<?php if(!$link){continue;} ?>
								<li><a href="<?php echo $link; ?>" target="_blank" class="soc__list-<?php echo $key; ?>"><svg><use xlink:href="#soc-<?php echo $key; ?>" /></svg></a>
								</li>
							<?php } ?>
						</ul>
						<div class="clearfix"></div>
						<ul class="foot__links list-vert">

							<?php if($themeset_phone) { ?>
								<li><a href="tel:+<?php echo preg_replace('/[^0-9]/', '', $themeset_phone); ?>" class="link"><?php echo $themeset_phone; ?></a></li>
							<?php } ?>
							<?php if($themeset_email) { ?>
								<li><a href="mailto:<?php echo $themeset_email; ?>" class="link"><?php echo $themeset_email; ?></a></li>
							<?php } ?>
						</ul>
					</div>
				</div>

			</div>
			<div class="foot__menu col-6 col-md-3">
				<?php if(!empty($menu['journal'])) { ?>
					<ul class="foot__menu_parent list-vert">
						<li><a href="<?php echo $menu['journal']['href']; ?>" class="link"><?php echo $menu['journal']['title']; ?></a></li>
					</ul>
					<?php if(!empty($menu['journal']['children'])) { ?>
						<ul class="foot__menu_child list-vert">
							<?php foreach($menu['journal']['children'] as $key=>$child) { ?>
								<?php if($key === 'tag') {continue;} ?>
								<li><a href="<?php echo $child['href']; ?>" class="link"><?php echo $child['title']; ?></a></li>
							<?php } ?>
						</ul>
					<?php } ?>
				<?php } ?>
			</div>
			<div class="foot__menu col-6 col-md-3">
				<ul class="foot__menu_parent list-vert">
					<?php foreach($menu as $key=>$item) { ?>
						<?php if($key === 'journal') {continue;} ?>
						<li><a href="<?php echo $item['href']; ?>" class="link"><?php echo $item['title']; ?></a></li>
					<?php } ?>
					<?php if(!empty($adv['href'])) { ?>
						<li><a href="<?php echo $adv['href']; ?>" class="link" target="_blank"><?php echo $adv['title'] ? $adv['title'] : 'Рекламодателям';  ?></a></li>
					<?php } ?>
					<li>
						<a href="<?php echo $logged ? $account : $login; ?>" class="link foot__menu-login"><svg class="ico"><use xlink:href="#<?php echo $logged ? 'cabinet' : 'login'; ?>" /></svg> <?php echo $logged ? 'Кабинет' : 'Войти'; ?></a>
					</li>
				</ul>
			</div>

		</div>

		<div class="row foot__bottom">

			<div class="foot__copy col-12 col-md-6">
				<div class="foot__copy_in">
					<?php echo html_entity_decode($themeset_copy); ?>
				</div>
			</div>

			<div class="foot__smenu col-6 col-md-3">
				<ul class="list-vert">
					<?php if(!empty($footer_menu)) { ?>
						<?php foreach($footer_menu as $menu) { ?>
							<li><a href="<?php echo $menu['href']; ?>" class="link"><?php echo $menu['title']; ?></a></li>
						<?php } ?>
					<?php } ?>
				</ul>
			</div>

			<div class="dev__cont col-6 col-md-3">
				<a href="https://slavnydesign.com/" class="dev__item" target="_blank">
					<span>Дизайн и разработка:</span>
					<img src="<?php echo $theme_dir; ?>/img/dev.svg" alt="">
				</a>
			</div>

		</div>

	</div>
</footer>
<?php /* # page outer */ ?>

<script src="<?php echo $theme_dir; ?>/libs/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo $theme_dir; ?>/libs/slick/slick.min.js"></script>
<script src="<?php echo $theme_dir; ?>/libs/inputmask/inputmask.js"></script>
<script src="<?php echo $theme_dir; ?>/libs/inputmask/jquery.inputmask.js"></script>

<script src="<?php echo $theme_dir; ?>/js/common.js?v=<?php echo date('mdyH'); ?>"></script>

<?php require(DIR_TEMPLATE . 'avclub/template/_include/modal.tpl'); ?>
<?php require(DIR_TEMPLATE . 'avclub/template/_include/svg.tpl'); ?>



<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-72812773-1', 'auto');
	ga('send', 'pageview');
</script>

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-72812773-1"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-72812773-1');
</script>

<script>(function() {
	var _fbq = window._fbq || (window._fbq = []);
	if (!_fbq.loaded) {
		var fbds = document.createElement('script');
		fbds.async = true;
		fbds.src = '//connect.facebook.net/en_US/fbds.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(fbds, s);
		_fbq.loaded = true;
	}
	_fbq.push(['addPixelId', '1049051051775467']);
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', 'PixelInitialized', {}]);
</script>
<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1049051051775467'); // Insert your pixel ID here.
fbq('track', 'PageView');
</script>

<script type="text/javascript">
	(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol 
+ '//vk.com/rtrg?r=AyBbvECcEqyQ8zee2WS5uTEqovUtWzmlXTlKyeHriyAklypHjNoKCa9k0uIvUP7xp9FrUFbFL/CRT52rQjf3sgKKDIBni0jPwTBTLjPa9*YbUo6b7n53B1FDhaZidimvkwgimaX5jfcQyjgzZO*YgEZ5DXcBuYlx7I6fLlSko3I-&pixel_id=1000053825';</script>
<script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?160",t.onload=function(){VK.Retargeting.Init("VK-RTRG-310140-h7cUu"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script>

<script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src='https://vk.com/js/api/openapi.js?169',t.onload=function(){VK.Retargeting.Init("VK-RTRG-1770525-fKw25"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-1770525-fKw25" style="position:fixed; left:-999px;" alt=""/></noscript>


</body></html>