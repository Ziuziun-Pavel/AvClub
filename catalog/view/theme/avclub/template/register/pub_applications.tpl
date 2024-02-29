<div class="publist--title">Поданные заявки на публикации</div>

<?php if($pub_applications) { ?>
<?php foreach($pub_applications as $app) { ?>
<div class="publist--item">
    <div class="publist--date"><?= $app['date'] ?></div>
    <div class="publist--data">
        <div class="publist--name"><?= $app['title'] ?></div>
        <? if($app['link'] || $app['message'] && $app['status'] !== 'consideration') { ?>
        <div class="publist--preview">

            <? if($app['link'] && !empty($app['link'] && $app['status'] === 'publisher')) { ?>
            <?php foreach($app['link'] as $link) { ?>
            <a href="<?= $link ?>"><?= $link ?></a><br>
            <? } ?>
            <? } ?>

            <? if($app['message'] && $app['status'] === 'canceled') { ?>
            <?= $app['message'] ?>
            <? } ?>
        </div>
        <? } ?>

        <?php if($app['reports']): ?>
            <div class="publist--tag">
                <span><?= $app['reports'] ?></span>
            </div>
        <?php endif; ?>

    </div>
    <div class="publist--status">
        <? switch ($app['status']) {
                    case "publisher":
                        echo 'Оформлено';
                        break;
                    case "consideration":
                        echo 'На рассмотрении';
                        break;
                    case "canceled":
                        echo 'Не опубликовано';
                        break;
                    case "process":
                        echo 'В процессе';
                        break;
                    default:
                        echo 'В процессе';
                        break;
                     } ?>
    </div>
</div>
<?php } ?>
<?php } else { ?>
<span>На данный момент нет заявок на публикации</span>
<?php } ?>


