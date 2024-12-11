<?php if (!empty($app_list)) { ?>
<div class="cosort">
    <div class="cosort__outer">
        <div class="cosort__check">
            <div class="cosort__top">
                <?php
                // Получаем последний год из массива $years
                $latestYear = !empty($years) ? $years[0] : 'Нет данных'; // Если массив не пустой, берем первый элемент (последний год)
                ?>
                <span><strong><?php echo $latestYear; ?></strong></span>
                <svg class="ico"><use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill"></use></svg>
            </div>
            <div class="cosort__list">

                <?php
                // Получаем уникальные годы и сортируем их
                rsort($years); // Сортировка по убыванию
                foreach ($years as $year) {
                    echo "<label class='cosort__item'>";
                echo "<input type='radio' name='year' value='$year'>";
                echo "<span><strong>$year</strong></span>";
                echo "</label>";
                }
                ?>
            </div>
        </div>
    </div>
</div>




<?php foreach($app_list as $app) { ?>
<div class="expreg expreg-apps-finished " style="margin-top: 1rem" data-year="<?= $app['year'] ?>">
    <div class="expreg__info expreg__info-finished">

        <div class="expreg__name expreg__name-finished">
            <?php
            if (!empty($app['landing_url'])) {
                echo '<a target="_blank" href="' . $app['landing_url'] . '">';
            echo $app['title'];
            echo '</a>';
            } else {
            echo $app['title'];
            }
            ?>
        </div>
        <div class="expreg__date expreg__date-finished">
            <span>
               <?php
                    if ($app['status']):
                        echo 'Дата проведения: ' . $app['date'];
                    endif;
                ?>
            </span>
        </div>


        <?php if ($app['act_url']): ?>
        <div class="expreg__date">
            <a href="<?= $app['act_url']; ?>">Ссылка на акт</a>
        </div>
        <?php endif; ?>

        <?php if ($app['video']): ?>
        <div class="expreg__date">
            <a href="<?= $app['video']; ?>">Видео</a>
        </div>
        <?php endif; ?>

        <?php if (!empty($app['reports'])): ?>
        <?php foreach($app['reports'] as $key => $report): ?>
        <?php if (!empty($report)): // Проверка на наличие отчета ?>
        <div class="expreg__date">
            <?php
                    switch ($key):
                        case 'chat':
                            echo '<a href="' . htmlspecialchars($report) . '">Отчёт по чату</a>';
            break;
            case 'visit':
            echo '<a href="' . htmlspecialchars($report) . '">Отчёт по посещений</a>';
            break;
            case 'event':
            echo '<a href="' . htmlspecialchars($report) . '">Отчёт по мероприятию</a>';
            break;
            endswitch;
            ?>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if($app['status'] === 'wait') { ?>
        <div class="expreg__btns expreg__btns-finished" style="font-size: 13px;">
            <a href="#modal_vote" class="btn btn-red modalshow" data-link="<?= $app['link_crm'] ?>"
               data-title="<?= $app['title'] ?>">
                Заполнить бриф
            </a>
        </div>
        <!--<div class="expreg__btns expreg__btns-finished" style="font-size: 13px;">
            <a href="<?php echo $app['link_crm']; ?>" class="btn btn-red"
               >Заполнить бриф</a>
        </div>
-->
        <?php } ?>

        <?php if($app['status'] !== 'wait') { ?>
        <div class="answers__wrapper d-none ">
            <?php if (!empty($app["answers"])) { ?>
            <h2>Ответы:</h2>
            <?php foreach ($app["answers"] as $question => $answer) { ?>
            <div class="question">
                <strong>Вопрос:</strong> <?php echo $question; ?>

                <div class="answer">
                    <strong>Ответ:</strong> <?php echo $answer[0]; ?>
                </div>
            </div>
            <?php } ?>

            <?php } ?>
        </div>
        <?php if (!empty($app["answers"])) { ?>
        <div class="question-btn4">
            Показать ответы
            <div class="question-btn4--arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                    <path d="M5.70711 9.71069C5.31658 10.1012 5.31658 10.7344 5.70711 11.1249L10.5993 16.0123C11.3805 16.7927 12.6463 16.7924 13.4271 16.0117L18.3174 11.1213C18.708 10.7308 18.708 10.0976 18.3174 9.70708C17.9269 9.31655 17.2937 9.31655 16.9032 9.70708L12.7176 13.8927C12.3271 14.2833 11.6939 14.2832 11.3034 13.8927L7.12132 9.71069C6.7308 9.32016 6.09763 9.32016 5.70711 9.71069Z"
                          fill="#0F0F0F"/>
                </svg>
            </div>
        </div>
        <?php } ?>

        <?php } ?>

    </div>
    <div class="expreg__path">
        <div class="expreg__capt">Статус заявки</div>
        <div class="expreg__status">
            <?php foreach($app['statuses'] as $key => $status) { ?>
            <div class="expreg__status--item <?php echo $status['preactive'] ? '--preactive' : ''; ?> <?php echo $status['active'] ? '' : '--passive'; ?>">
                <span></span> <?php echo $status['text']; ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php }?>
<?php } else { ?>

<div class="cosort" style="margin-bottom: 1.5rem">
    <div class="cosort__outer">
        <div class="cosort__check">
            <div class="cosort__top">
                <?php
                // Получаем последний год из массива $years
                $latestYear = !empty($years) ? $years[0] : 'Нет данных'; // Если массив не пустой, берем первый элемент (последний год)
                ?>
                <span><strong><?php echo $latestYear; ?></strong></span>
                <svg class="ico"><use xlink:href="catalog/view/theme/avclub/img/sprite.svg#arr-down-fill"></use></svg>
            </div>
            <div class="cosort__list">

                <?php
                // Получаем уникальные годы и сортируем их
                rsort($years); // Сортировка по убыванию
                foreach ($years as $year) {
                    echo "<label class='cosort__item'>";
                echo "<input type='radio' name='year' value='$year'>";
                echo "<span><strong>$year</strong></span>";
                echo "</label>";
                }
                ?>
            </div>
        </div>
    </div>
</div>


<div class="expreg ">
    <div class="expreg__info">
        <div class="imaster__text">
            На данный момент никаких заявок не найдено.
            Попробуйте зайти немного позже.
        </div>

    </div>
</div>

<?php } ?>

<style>
    .preactive-before:before {
        background-color: black;
    }

    .question-btn4 {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .question-btn4--arrow {
        display: inline-block;
        width: 1rem;
        height: 1rem;
    }

    .question-btn4--arrow svg {
        width: 100%;
        height: 100%;
    }
</style>
<script>
    $(document).ready(function () {
        var savedYear = localStorage.getItem('selectedYear');
        if (savedYear) {
            $('input[name="year"][value="' + savedYear + '"]').prop('checked', true);
            $('.cosort__top span strong').text(savedYear);

        }

        $('input[name="year"]').on('change', function () {
            var selectedYear = $(this).val();
            localStorage.setItem('selectedYear', selectedYear);
            loadFinished(selectedYear)

            $('.expreg-apps-finished.expreg').each(function () {
                var itemYear = $(this).data('year');
                if (selectedYear === "" || itemYear == selectedYear) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('.expreg__btns-finished .modalshow').on('click', function (e) {
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

            loadFinished()
        });

        function loadFinished($year = 2024) {
            $.ajax({
                type: "GET",
                url: "index.php?route=expert/expert/getFinishedApps",
                dataType: "json",
                data: {
                    'expert_id': '<?php echo $expert_id; ?>',
                    'year': $year
                },
                beforeSend: function (json) {
                    $('#content-apps-finished').html(`
                        <div class="expreg__message ">
                            <div class="expreg__message--preloader">
                                <div class="cssload-clock"></div>
                            </div>
                            <div class="expreg__message--text">
                                Подождите, идет поиск заявок...
                            </div>
                        </div>
                    `);
                },
                complete: function (json) {
                },
                success: function (json) {
                    if (json['template']) {
                        $('#content-apps-finished').html(json['template']);
                        $('#content-apps-finished').removeClass('d-none');
                    } else if (json['error']) {
                        $('#content-apps-finished').html(error_text);
                    }
                    // if ($('.expertnav__tabs a.voting').hasClass('active')) {
                    //     $('#content-finished-votes').removeClass('d-none');
                    //     $('#content-finished-votes .expreg-votes').removeClass('d-none');
                    //     $('#navlist-bio').removeClass('active');
                    //     $('#navlist-events').removeClass('active');
                    // }

                },
                error: function (json) {
                    $('#content-apps-finished').html(error_text);
                    console.log('expert getAppAds error', json);
                }
            });
        }

        $(".expreg__status--item.--preactive").each(function () {
            $(this).prevAll(".expreg__status--item").addClass("preactive-before");
        });

        $(document).off('click', '.question-btn4').on('click', '.question-btn4', function () {
            $(this).closest('.expreg__info.expreg__info-finished').find('.answers__wrapper').toggleClass('d-none');
            console.log('click6');

            if ($(this).closest('.expreg__info.expreg__info-finished').find('.answers__wrapper').hasClass('d-none')) {
                $(this).html(`
            Показать ответы
            <div class="question-btn4--arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                    <path d="M5.70711 9.71069C5.31658 10.1012 5.31658 10.7344 5.70711 11.1249L10.5993 16.0123C11.3805 16.7927 12.6463 16.7924 13.4271 16.0117L18.3174 11.1213C18.708 10.7308 18.708 10.0976 18.3174 9.70708C17.9269 9.31655 17.2937 9.31655 16.9032 9.70708L12.7176 13.8927C12.3271 14.2833 11.6939 14.2832 11.3034 13.8927L7.12132 9.71069C6.7308 9.32016 6.09763 9.32016 5.70711 9.71069Z" fill="#0F0F0F"/>
                </svg>
            </div>
        `);
            } else {
                $(this).html(`
            Скрыть ответы
            <div class="question-btn4--arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                    <path d="M18.2929 15.2893C18.6834 14.8988 18.6834 14.2656 18.2929 13.8751L13.4007 8.98766C12.6195 8.20726 11.3537 8.20757 10.5729 8.98835L5.68257 13.8787C5.29205 14.2692 5.29205 14.9024 5.68257 15.2929C6.0731 15.6835 6.70626 15.6835 7.09679 15.2929L11.2824 11.1073C11.673 10.7168 12.3061 10.7168 12.6966 11.1073L16.8787 15.2893C17.2692 15.6798 17.9024 15.6798 18.2929 15.2893Z" fill="#0F0F0F"/>
                </svg>
            </div>
        `);
            }
        });
    });
</script>