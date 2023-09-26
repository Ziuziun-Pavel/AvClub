<div class="atag__cont">
  <div class="atag__title">ТЕГИ</div>
  <ul class="atag__list list-hor">
    <?php foreach($tags as $tag) { ?>
      <li><a href="<?php echo $tag['href']; ?>"><?php echo $tag['tag']; ?></a></li>
    <?php } ?>
  </ul>

  <?php if(!empty($companies)) { ?>
  	<div class="atag__companies">
  		<?php foreach($companies as $company) { ?>
  			<a href="<?php echo $company['href']; ?>" class="comp__item comp__item-article link__outer">
  				<span class="comp__img">
  					<img src="<?php echo $company['thumb']; ?>" alt="">
  				</span>
  				<span class="comp__name"><span class="link"><?php echo $company['title']; ?></span></span>
  				<span class="comp__preview"><?php echo $company['preview']; ?></span>
  				<span class="btn btn-red comp__btn">
  					<span>Подробнее</span>
  				</span>
  			</a>
  		<?php } ?>
  	</div>
  <?php } ?>
</div>