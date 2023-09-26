<section class="section_ehistory">
  <div class="container">
    <?php if($title) { ?>
      <div class="title etitle">
        <h2><?php echo $title; ?></h2>
      </div>
    <?php } ?>
    <div class="ehistory__cont">
      <div class="ehistory__slider">

        <?php foreach($events as $year) { ?>
          <div class="ehistory__slide">
            <div class="ehistory__year"><?php echo $year['year']; ?></div>
            <div class="ehistory__info">
              <?php foreach($year['events'] as $event) { ?>
                <p>
                  <?php
                  $link = '';
                  $target = false;
                  if(!empty($event['old_type'])) {
                    if($event['old_type'] === 'link' && $event['old_link']) {
                      $link = $event['old_link'];
                      $target = true;
                    }else if($event['old_type'] === 'page'){
                      $link = $event['href'];
                    }
                  } 
                  if($link) {
                    echo '<a href="' . $link . '" ' . ($target ? 'target="_blank"' : '') . ' class="link">';
                  }
                  echo $event['type'] . ' ' . $event['city'] . ' ' . ($event['stop_show'] ? $event['date_start'] . '-' . $event['date_stop'] : $event['date_start']); 
                  echo $link ? '</a>' : '';
                  ?>
                </p>
              <?php } ?>
            </div>
          </div>
        <?php } ?>

      </div>
      <div class="ehistory__nav nav__cont2">
        <button type="button" class="nav__item nav__prev nav__slide"><span><svg><use xlink:href="#arr-left" /></svg></span></button>
        <div class="nav__drag"><svg class="ico"><use xlink:href="#drag" /></svg></div>
        <button type="button" class="nav__item nav__next nav__slide"><span><svg><use xlink:href="#arr-right" /></svg></span></button>
      </div>
    </div>
  </div>
</section>
<script>
  $(function(){
    var ehstorySlider = $('.ehistory__slider').slick({
      infinite: false,
      slidesToShow: 4,
      slidesToScroll: 1,
      dots: false,
      prevArrow: '.section_ehistory .nav__prev',
      nextArrow: '.section_ehistory .nav__next',
      responsive: [
      {
        breakpoint: 1300,
        settings: {
          slidesToShow: 3,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 510,
        settings: {
          slidesToShow: 1,
        },
      }
      ]
    })
  })
</script>