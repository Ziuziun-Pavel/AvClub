<style>
    h2 {
        color: #333;
    }
    .question {
        margin-bottom: 10px;
    }
    .answer {
        margin-left: 20px;
        font-style: italic;
    }
</style>
<p>(Раздел в разработке)</p>
<?php if(!empty($vote_list)) { ?>
<?php foreach($vote_list as $vote) { ?>
<div class="expreg expreg-votes d-none">
    <div class="expreg__info expreg__info-votes">

        <div class="expreg__name expreg__name-votes"><?php echo $vote['name']; ?></div>
        <div class="expreg__date expreg__date-votes">
            <span>
               <?php
                    switch ($vote['status']):
                        case 'wait':
                            echo 'Актуален до ' . $vote['date_end'];
                            break;
                        case 'fail':
                            echo 'Завершен ' . $vote['date_end'];
                            break;
                        case 'success':
                            echo 'Пройден ' . $vote['date_end'];
                            break;
                    endswitch;
                ?>

            </span>
        </div>

        <?php if($vote['status'] === 'wait') { ?>

            <div class="expreg__btns expreg__btns-votes" style="font-size: 13px;">
                <a href="<?php echo $vote['link']; ?>" class="btn btn-red"
                   >Пройти опрос</a>
            </div>

        <?php } ?>

        <?php if(!empty($vote["answers"])) { ?>

        <h2>Ответы на опрос:</h2>
        <?php foreach ($vote["answers"] as $question => $answer) { ?>
        <div class="question">
            <strong>Вопрос:</strong> <?php echo $question; ?>
            <div class="answer">
                <strong>Ответ:</strong> <?php echo $answer[0]; ?>
            </div>
        </div>
        <?php } ?>

        <?php } ?>


    </div>
    <div class="expreg__path">
        <div class="expreg__capt">Статус голосования</div>
        <div class="expreg__status">
            <?php foreach($vote['statuses'] as $key => $status) { ?>
            <div class="expreg__status--item <?php echo $status['preactive'] ? '--preactive' : ''; ?> <?php echo $status['active'] ? '' : '--passive'; ?>">
                <span></span> <?php echo $status['text']; ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php }?>
<?php } else { ?>
<div class="expreg d-none">
    <div class="expreg__info">
        <div class="imaster__text">
            На данный момент никаких опросов не планируется.
            Попробуйте зайти немного позже.
        </div>

    </div>
</div>

<?php } ?>