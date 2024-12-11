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
<?php if(!empty($quiz_list)) { ?>
<?php foreach($quiz_list as $quiz) { ?>
<div class="expreg expreg-votes d-none">
    <div class="expreg__info expreg__info-votes">

        <div class="expreg__name expreg__name-votes" style="margin-top: 0;">
            <?php echo $quiz['name']; ?>
        </div>
        <div class="expreg__date expreg__date-votes">
            <span>
               <?php echo 'Актуален до ' . $quiz['date_end']; ?>
            </span>
        </div>

    </div>
    <div class="expreg__btns expreg__btns-votes" style="height: 1rem;font-size: 13px;margin: 2rem -2rem;">
        <a href="<?php echo $quiz['link']; ?>" class="btn btn-red" target="_blank">Проголосовать</a>
    </div>
</div>
<?php }?>
<?php } ?>

<?php if(!empty($vote_list) || !empty($quiz_list)) {
?>
<?php foreach($vote_list as $vote) { ?>
<div class="expreg expreg-votes d-none">
    <div class="expreg__info expreg__info-votes">

        <div class="expreg__name expreg__name-votes">
            <?php
            if (!empty($vote['landing_url'])) {
                echo '<a target="_blank" href="' . $vote[landing_url] . '">';
            echo $vote['name'];
            echo '</a>';
            } else {
            echo $vote['name'];
            }
            ?>
        </div>
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
            <a href="#modal_vote" class="btn btn-red modalshow" data-link="<?= $vote['link'] ?>" data-title="<?= $vote['name'] ?>">
                Ответить
            </a>
        </div>
        <!--<div class="expreg__btns expreg__btns-votes" style="font-size: 13px;">
            <a href="<?php echo $vote['link']; ?>" class="btn btn-red"
               >Ответить</a>
        </div>
-->
        <?php } ?>

        <div class="answers__wrapper d-none ">
            <?php if (!empty($vote["answers"])) { ?>
            <h2>Ответы:</h2>
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
        <?php if (!empty($vote["answers"])) { ?>
        <div class="question-btn">
            Показать ответы
            <div class="question-btn--arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                    <path d="M5.70711 9.71069C5.31658 10.1012 5.31658 10.7344 5.70711 11.1249L10.5993 16.0123C11.3805 16.7927 12.6463 16.7924 13.4271 16.0117L18.3174 11.1213C18.708 10.7308 18.708 10.0976 18.3174 9.70708C17.9269 9.31655 17.2937 9.31655 16.9032 9.70708L12.7176 13.8927C12.3271 14.2833 11.6939 14.2832 11.3034 13.8927L7.12132 9.71069C6.7308 9.32016 6.09763 9.32016 5.70711 9.71069Z" fill="#0F0F0F"/>
                </svg>
            </div>
        </div>
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

<style>
    .preactive-before:before {
        background-color: black;
    }

    .question-btn {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .question-btn--arrow {
        display: inline-block;
        width: 1rem;
        height: 1rem;
    }

    .question-btn--arrow svg {
        width: 100%;
        height: 100%;
    }
</style>
<script>
    $(document).ready(function () {
        $('.expreg__btns-votes .modalshow').on('click', function (e) {
            e.preventDefault();
            var link = $(this).data('link');
            var title = $(this).data('title');
            $('#crmForm').attr('src', link);
            $('.vote-title').text(title)

        });

        $('#modal_vote .modal__close').off('click').on('click', function (event) {
            $('#crmForm').attr('src', '');
            $('.vote-title').text('')

            console.log('окно закрыто')
            is_modal_open = false;

            loadVotes()
        });

        function loadVotes() {
            $.ajax({
                type: "GET",
                url: "index.php?route=expert/expert/getActiveVotes",
                dataType: "json",
                data: {
                    'expert_id': '<?php echo $expert_id; ?>'
                },
                beforeSend: function (json) {
                    $('#content-active-votes').html(`
                    <div class="expreg__message ">
                        <div class="expreg__message--preloader">
                            <div class="cssload-clock"></div>
                        </div>
                        <div class="expreg__message--text">
                            Подождите, идет поиск опросов...
                        </div>
                    </div>
                `);
                },
                complete: function (json) {
                },
                success: function (json) {
                    if (json['template']) {
                        $('#content-active-votes').html(json['template']);
                    } else if (json['error']) {
                        $('#content-active-votes').html(error_text);
                    }
                    if ($('.expertnav__tabs a.voting').hasClass('active')) {
                        $('#content-active-votes').removeClass('d-none');
                        $('#content-active-votes .expreg-votes').removeClass('d-none');
                        $('#navlist-bio').removeClass('active');
                        $('#navlist-events').removeClass('active');
                    }

                },
                error: function (json) {
                    $('#catalog_list').html(error_text);
                    console.log('expert getCatalogList error', json);
                }
            });

            $.ajax({
                type: "GET",
                url: "index.php?route=expert/expert/getFinishedVotes",
                dataType: "json",
                data: {
                    'expert_id': '<?php echo $expert_id; ?>'
                },
                beforeSend: function (json) {
                    $('#content-finished-votes').html(`
                    <div class="expreg__message ">
                        <div class="expreg__message--preloader">
                            <div class="cssload-clock"></div>
                        </div>
                        <div class="expreg__message--text">
                            Подождите, идет поиск опросов...
                        </div>
                    </div>
                `);
                },
                complete: function (json) {
                },
                success: function (json) {
                    if (json['template']) {
                        $('#content-finished-votes').html(json['template']);
                    } else if (json['error']) {
                        $('#content-finished-votes').html(error_text);
                    }
                    if ($('.expertnav__tabs a.voting').hasClass('active')) {
                        $('#content-finished-votes').removeClass('d-none');
                        $('#content-finished-votes .expreg-votes').removeClass('d-none');
                        $('#navlist-bio').removeClass('active');
                        $('#navlist-events').removeClass('active');
                    }

                },
                error: function (json) {
                    $('#catalog_list').html(error_text);
                    console.log('expert getCatalogList error', json);
                }
            });
        }


        $(".expreg__status--item.--preactive").each(function () {
            $(this).prevAll(".expreg__status--item").addClass("preactive-before");
        });

        $(document).off('click', '.question-btn').on('click', '.question-btn', function () {
            $(this).closest('.expreg__info.expreg__info-votes').find('.answers__wrapper').toggleClass('d-none');
            console.log('click1');

            if ($(this).closest('.expreg__info.expreg__info-votes').find('.answers__wrapper').hasClass('d-none')) {
                $(this).html(`
            Показать ответы
            <div class="question-btn--arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                    <path d="M5.70711 9.71069C5.31658 10.1012 5.31658 10.7344 5.70711 11.1249L10.5993 16.0123C11.3805 16.7927 12.6463 16.7924 13.4271 16.0117L18.3174 11.1213C18.708 10.7308 18.708 10.0976 18.3174 9.70708C17.9269 9.31655 17.2937 9.31655 16.9032 9.70708L12.7176 13.8927C12.3271 14.2833 11.6939 14.2832 11.3034 13.8927L7.12132 9.71069C6.7308 9.32016 6.09763 9.32016 5.70711 9.71069Z" fill="#0F0F0F"/>
                </svg>
            </div>
        `);
            } else {
                $(this).html(`
            Скрыть ответы
            <div class="question-btn--arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                    <path d="M18.2929 15.2893C18.6834 14.8988 18.6834 14.2656 18.2929 13.8751L13.4007 8.98766C12.6195 8.20726 11.3537 8.20757 10.5729 8.98835L5.68257 13.8787C5.29205 14.2692 5.29205 14.9024 5.68257 15.2929C6.0731 15.6835 6.70626 15.6835 7.09679 15.2929L11.2824 11.1073C11.673 10.7168 12.3061 10.7168 12.6966 11.1073L16.8787 15.2893C17.2692 15.6798 17.9024 15.6798 18.2929 15.2893Z" fill="#0F0F0F"/>
                </svg>
            </div>
        `);
            }
        });


    });
</script>