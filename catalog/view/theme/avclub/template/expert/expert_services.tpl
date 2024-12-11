<p>(Раздел в разработке)</p>
<?php if(!empty($services_list)) {
?>
    <?php foreach($services_list as $service) { ?>
    <div class="expreg expreg-services d-none">
        <div class="expreg__info expreg__info-services">

            <div class="expreg__name expreg__name-services">
                <?php if (!empty($service['link'])) {
                    echo '<a href="' . $service['link'] . '">';
                    echo $service['title'];
                    echo '</a>';
                    } else {
                    echo $service['title'];
                } ?>
            </div>
            <div class="expreg__date expreg__date-services">
                <span>
                   <?php
                     echo 'Дата: ' . $service['date'];
                    ?>
                </span>
            </div>
        </div>
    </div>
    <?php }?>
<?php } else { ?>
    <div class="expreg d-none">
        <div class="expreg__info">
            <div class="imaster__text">
                На данный момент никаких услуг не найдено.
                Попробуйте зайти немного позже.
            </div>

        </div>
    </div>

<?php } ?>

<script>

</script>