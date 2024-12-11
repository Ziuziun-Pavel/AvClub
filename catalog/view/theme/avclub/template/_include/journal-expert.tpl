<?php if(!empty($expert['href'])) {
?>
	<a href="<?php echo $expert['href']; ?>" class="opinion__item link__outer">
		<span class="opinion__inner">
			<span class="opinion__img">
				<span class="opinion__image">
					<img src="<?php echo $expert['thumb']; ?>" alt="">
					<span class="opinion__expert"><svg class="ico ico-center"><use xlink:href="#star" /></svg></span>
				</span>
			</span>
			<span class="opinion__name"><?php echo $expert['name']; ?></span>
			<span class="opinion__exp"><?php echo htmlspecialchars_decode($expert['exp']); ?></span>
			<span class="opinion__exp"><?php echo $expert['award']; ?></span>
		</span>
	</a>
<?php }else{ ?>
	<span class="opinion__item link__outer">
		<span class="opinion__inner">
			<span class="opinion__img">
				<span class="opinion__image">
					<img src="<?php echo $expert['thumb']; ?>" alt="">
				</span>
			</span>
			<span class="opinion__name"><?php echo $expert['name']; ?></span>
			<span class="opinion__exp"><?php echo $expert['exp']; ?></span>
			<span class="opinion__exp" style="font-size: .9rem;"><?php echo $expert['award']; ?></span>
		</span>
	</span>
	<?php } ?>