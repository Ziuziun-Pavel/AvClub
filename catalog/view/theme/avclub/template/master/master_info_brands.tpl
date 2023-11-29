<section class="lineorg">
    <div class="container">

        <div class="lineorg--title">Организаторы</div>
        <div class="lineorg--cont">

                <?php foreach($brand_list as $brand) { ?>
            <a href="<?= $brand['href'] ?>" class="lineorg__item">
                <span class="lineorg__item--img"><img src="<?php echo $brand['image']; ?>" alt=""></span>
                <span class="lineorg__item--type"><?= $brand['activity'] ?></span>
                <span class="lineorg__item--text">
						<?php echo $brand['description']; ?>
					</span>
            </a>
            <?php } ?>

        </div>

    </div>
</section>