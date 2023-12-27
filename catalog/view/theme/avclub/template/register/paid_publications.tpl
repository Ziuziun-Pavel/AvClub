<?php if($paid_publications) { ?>
<div class="publ__slot--title">Выберите слот <br class="d-lg-none">для размещения публикации</div>

<?php foreach($paid_publications as $publication) { ?>
    <div class="publ__slot--item">
        <span><?= $publication['title'] ?></span>
        <a href="<?= $publication['link'] ?>" class="btn btn-invert">
            + Добавить
        </a>
    </div>
<?php } ?>
<?php } else { ?>
<span>На данный момент нет оплаченных публикаций</span>
<?php } ?>
