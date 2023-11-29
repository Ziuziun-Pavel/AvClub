<section class="linemember">
    <div class="container">

        <div class="linemember--title">Участники онлайн-встречи</div>
        <div class="linemember--cont">

            <div class="linemember--slider">

                <?php foreach($speaker_list as $speaker) { ?>
                <div class="slick-slide">
                    <a href="<?= $speaker['href'] ?>" class="linemember__item">
		                <span class="linemember__item--img">
                            <img src="<?php echo $speaker['image']; ?>" alt="">
                            <?php if ($speaker['moderator'] === true) {
                                    echo '<span>Модератор</span>';
                        }
                            ?>
                        </span>
                        <span class="linemember__item--name"><?php echo $speaker['name']; ?></span>
                        <span class="linemember__item--exp"><?php echo $speaker['exp']; ?></span>

                    </a>
                </div>
                <?php } ?>

            </div>

            <div class="linemember--nav nav__cont2">
                <button type="button" class="nav__item nav__prev nav__slide"><span><svg><use
                                    xlink:href="#arr-left"/></svg></span></button>
                <div class="nav__dots2"></div>
                <button type="button" class="nav__item nav__next nav__slide"><span><svg><use
                                    xlink:href="#arr-right"/></svg></span></button>
            </div>

        </div>

    </div>
</section>

<script>
    $(function () {
        var slider = $('.linemember--slider').slick({
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            dots: true,
            appendDots: $('.linemember--nav .nav__dots2'),
            prevArrow: '.linemember--nav .nav__prev',
            nextArrow: '.linemember--nav .nav__next',
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
                    },
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1
                    },
                }
            ]
        })
    })
</script>