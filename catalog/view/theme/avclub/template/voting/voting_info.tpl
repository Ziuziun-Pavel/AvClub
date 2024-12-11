<?php echo $header; ?>
<style>

    .vote-section {
        margin: 3rem;
    }

    .voting__btn-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }

    #modal-vote .form-group {
        margin-bottom: 1rem;
    }

    #modal-vote label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }

    #modal-vote .form-control {
        width: 100%;
        padding: 0.5rem;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    #modal-vote textarea.form-control {
        resize: vertical;
    }

    .rating-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .rating-options label {
        display: flex;
        align-items: center;
        font-size: 1rem;
        cursor: pointer;
    }

    .rating-options input[type="radio"] {
        margin-right: 5px;
    }

    #vote-message {
        border: 1px solid black;
        padding: 3rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
    }

    #vote-message .word-container {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    #vote-message .word-container h3 {
        width: 100%;
        display: flex;
        justify-content: center;
        font-size: 1.4em;
    }

    #vote-message .word-container p {
        width: 100%;
        display: flex;
        justify-content: center;
        color: #b21a1a;
        font-size: 1.4em;
        font-weight: bold;
    }

</style>
<?php echo $content_top; ?>
<section class="section_content section_article">
    <div class="container">

        <?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

        <div class="content__title">
            <h1><?php echo $title; ?></h1>
        </div>
        <div class="content__wish">
            <div class="date">
                Номинант:
                <a style="display: inline; text-decoration: none" href="<?= $link ?>">
                    <h3 style="display: inline;"><?php echo $heading_title; ?></h3>
                </a>
            </div>
        </div>

        <div class="content__cont text__cont">

            <div class="content__row row">
                <div class="content__text col-md-8 col-lg-8 col-xl-9">
                    <?php if($thumb) { ?>

                    <div class="text__thumb">
                        <a href="<?= $link ?>">
                            <img src="<?php echo $thumb; ?>" alt="">
                        </a>
                    </div>
                    <?php } ?>

                    <!-- Блок голосования -->
                    <div class="vote-section">
                        <?php if (!empty($nominee['grade'])) { ?>
                        <!-- Сообщение, если пользователь уже голосовал -->
                        <div id="vote-message" class="form-group">
                            <div class="word-container">
                                <h3>Вы уже проголосовали за этого участника</h3>
                            </div>
                            <div class="word-container">
                                <p>Ваша оценка — <?php echo $nominee['grade']; ?></p>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="voting__btn-wrapper">
                            <a href="#modal-vote"
                               class="btn btn-red vote-btn"
                               data-max-grade="<?= $nominee['max_grade']; ?>"
                               data-nominee-id="<?= $nominee['id']; ?>"
                               data-voted="<?= isset($nominee['grade']) ? 'true' : 'false'; ?>"
                               data-grade="<?= $nominee['grade'] ?? ''; ?>"
                               data-href="<?= $nominee['href']; ?>"
                               data-fancybox>Проголосовать</a>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- # Блок голосования -->

                    <?php /* CASE */ ?>
                </div>
                <?php echo $column_right; ?>

            </div>

        </div>

        <?php echo $column_left; ?>

    </div>
</section>

<?php echo $content_bottom; ?>
<a href="#" class="toTop goTo">
    <svg class="ico ico-center">
        <use xlink:href="#arrow-top"/>
    </svg>
</a>


<div class="d-none">
    <div id="modal-vote" class="imgedit">
        <!-- Кнопка закрытия -->
        <button type="button" class="modal__close" data-fancybox-close>
            <svg class="ico ico-center">
                <use xlink:href="#close"></use>
            </svg>
        </button>
        <div class="modal--container">
            <!-- Заголовок -->
            <div class="imgedit__title">Оцените номинанта</div>

            <!-- Сообщение, если пользователь уже голосовал -->
            <div id="vote-message" class="form-group" style="display: none;">
                <h3>Вы уже проголосовали за этого участника</h3>
                <p>
                    Для просмотра поставленной оценки перейдите на страницу номинанта
                </p>

                <div class="imgedit__btns imgedit__btns-upload">
                    <a href="#" id="nominee-link" class="btn btn-red imgedit__btn">Перейти на страницу номинанта</a>
                </div>
            </div>

            <div id="vote-loader" class="form-loader" style="display: none;">
                <div class="expreg__message ">
                    <div class="expreg__message--preloader">
                        <div class="cssload-clock"></div>
                    </div>
                    <div class="expreg__message--text">
                        Подождите, идет отправка оценки...
                    </div>
                </div>
            </div>


            <!-- Форма голосования -->
            <form id="vote-form">
                <input id="nominee_id" name="nominee_id" value="" hidden>

                <!-- Оценка от 1 до 10 -->
                <div class="form-group">
                    <div class="rating-options" id="rating-options">
                        <!-- Радиокнопки будут динамически генерироваться -->
                    </div>
                </div>

                <!-- Текстовое поле для комментария -->
                <div class="form-group">
                    <label for="comment">Добавьте свое мнение</label>
                    <textarea id="comment" name="comment" class="form-control" rows="4"
                              placeholder="Напишите ваше мнение"></textarea>
                </div>

                <!-- Чекбокс -->
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="agree" name="agree">
                        Нажимая кнопку "Отправить" я даю своё согласие на обработку моих персональных данных в
                        соответствие
                        с федеральным законом от 27.07.2023 года № 152-ФЗ "О персональных данных" на условиях и для
                        целей
                        определенных в Согласии на обработку персональных данных*
                    </label>
                </div>

                <!-- Кнопка отправки -->
                <div class="imgedit__btns imgedit__btns-upload">
                    <button type="submit" class="btn btn-red imgedit__btn">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Обработчик клика на кнопке "Проголосовать"
        $(".vote-btn").on("click", function () {
            const maxGrade = parseInt($(this).data("max-grade"), 10) || 10; // Получаем максимальную оценку
            const userGrade = $(this).data("grade"); // Получаем оценку пользователя, если она есть
            const nomineeId = $(this).data("nominee-id");
            const voted = $(this).data("voted") == true; // Проверяем, голосовал ли пользователь
            const nomineeHref = $(this).data("href"); // Ссылка на страницу номинанта
            const $ratingOptionsContainer = $("#rating-options"); // Контейнер для радиокнопок
            const $modalTitle = $(".imgedit__title"); // Заголовок модального окна
            const $voteMessage = $("#vote-message"); // Сообщение для пользователя
            const $voteForm = $("#vote-form"); // Форма голосования
            const $nomineeLink = $("#nominee-link"); // Кнопка перехода на страницу номинанта

            $("#nominee_id").val(nomineeId);

            if (voted) {
                // Пользователь уже голосовал
                $modalTitle.text("");
                $voteMessage.show();
                $("#user-grade").text(userGrade); // Отображаем оценку пользователя
                $voteForm.hide();
                $nomineeLink.attr("href", nomineeHref); // Устанавливаем ссылку на страницу номинанта
            } else {
                // Пользователь еще не голосовал
                $modalTitle.text(`Оцените номинанта`);
                $voteMessage.hide();
                $voteForm.show();

                // Генерация радиокнопок
                $ratingOptionsContainer.empty();
                for (let i = 1; i <= maxGrade; i++) {
                    $ratingOptionsContainer.append(`
                    <label>
                        <input type="radio" name="rating" value="${i}"> ${i}
                    </label>
                `);
                }
            }
        });

        // Обработчик отправки формы
        $("#vote-form").on("submit", function (event) {
            event.preventDefault();

            const rating = $("input[name='rating']:checked").val(); // Получаем выбранную оценку
            const comment = $("#comment").val(); // Получаем текст комментария
            const nomineeId = $("#nominee_id").val(); // Получаем ID номинанта
            const agree = $("#agree").is(":checked"); // Проверяем, выбран ли чекбокс согласия

            if (!rating) {
                alert("Пожалуйста, выберите оценку!");
                return;
            }

            if (!agree) {
                alert("Пожалуйста, согласитесь с правилами обработки персональных данных!");
                return;
            }

            // Показать лоадер
            $("#vote-loader").show();
            $("#vote-form").hide();

            // Отправка данных через AJAX
            $.ajax({
                type: "GET",
                url: "index.php?route=voting/voting/addGrade",
                dataType: "json",
                data: {
                    'contact_id': '<?php echo $expert_id; ?>',
                    'nominee_id': nomineeId,
                    'quiz_id': '<?php echo $quiz_id; ?>',
                    'grade': rating,
                    'comment': comment
                },
                beforeSend: function () {
                    console.log("Начало отправки данных");
                },
                complete: function () {
                    // Скрыть лоадер
                    $("#vote-loader").hide();
                    $("#vote-form").show();

                    // // Закрытие модального окна
                    // $.fancybox.close();
                },
                success: function (json) {
                    console.log("Успех:", json);

                    if (json['success']) {
                        // Добавление текста в блок modal--container
                        $('.modal--container').html('<p>Ваш голос успешно отправлен. Спасибо за участие!</p>');

                        setTimeout(function () {
                            location.reload()
                        }, 1000);
                    } else if (json['error']) {
                        $('.modal--container').html('<p>Ошибка при отправке голоса</p>');
                    }


                },
                error: function (json) {
                    console.error("Ошибка:", json);
                    alert("Произошла ошибка при отправке данных. Попробуйте снова.");
                }
            });
        });

    });
</script>

<?php echo $footer; ?>

