<?php $theme_dir = 'catalog/view/theme/avclub'; ?>

<?php echo $header; ?>
<?php echo $content_top; ?>
<style>
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

    @media (min-width: 576px) {
        .news__image img {
            height: 189px;
        }
    }

    @media (max-width: 576px) {
        .news__image img {
            height: 264px;
        }
    }
</style>
<section class="section_content section_master">
    <div class="container">

        <?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

        <div class="content__title">

            <h1>Премия <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
        </div>
        <div class=" content__cont">
            <div class="event__sort row">
                <div class="event__filter col-md-9 col-lg-9">
                    <ul class="event__filter-list">
                        <li><a href="#" class="event__filter-tab filter__type active" data-type="all">все номинанты</a></li>
                        <?php foreach ($group_filter as $group) { ?>
                        <? if (!empty($group)): ?>
                            <li><a href="#" class="event__filter-tab filter__type" data-type='<?= $group ?>'><?= htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?></a></li>
                        <? endif; ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="search__row row">
                <?php if (!empty($nominees)) { ?>
                <?php foreach ($nominees as $nominee) { ?>
                <div class="news__outer col-sm-6 col-lg-4 col-xl-3" data-group="<?= $nominee['group'] ?>">
                    <a href="<?= $nominee['href']; ?>" class="news__item link__outer" target="_blank">
                        <div class="news__type">НОМИНАНТ</div>
                        <span class="news__img">
                        <span class="news__image">
                            <img src="<?= $nominee['image']; ?>"
                                 alt="<?= htmlspecialchars($nominee['name'], ENT_QUOTES, 'UTF-8'); ?>">
                        </span>
			        </span>
                        <span class="news__name">
			            <span class="link"><?= htmlspecialchars($nominee['name'], ENT_QUOTES, 'UTF-8'); ?></span>
	                </span>
                    </a>
                    <div class="voting__btn-wrapper">
                        <?php if (!empty($nominee['grade'])) { ?>
                        <div class="opinion__exp">
                            Ваша оценка - <strong><?= htmlspecialchars($nominee['grade'], ENT_QUOTES, 'UTF-8'); ?></strong>
                        </div>
                        <?php } else { ?>
                        <a href="#modal-vote"
                           class="btn btn-red vote-btn"
                           data-max-grade="<?= $nominee['max_grade']; ?>"
                           data-nominee-id="<?= $nominee['nominee_id']; ?>"
                           data-voted="false"
                           data-grade=""
                           data-href="<?= $nominee['href']; ?>"
                           data-fancybox>Проголосовать</a>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                <?php } else { ?>
                <p>Номинанты не найдены.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

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
                <input id="nominee_id" name="nominee_id" value="" hidden >

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
                        Нажимая кнопку "Отправить" я даю своё согласие на обработку моих персональных данных в соответствие
                        с федеральным законом от 27.07.2023 года № 152-ФЗ "О персональных данных" на условиях и для целей
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
        $('.event__filter-tab').click(function(e) {
            e.preventDefault();

            // Get the group type (either 'all' or the group name)
            var filterType = $(this).data('type');

            // Highlight the active filter
            $('.event__filter-tab').removeClass('active');
            $(this).addClass('active');

            // Filter the nominees
            $('.news__outer').each(function() {
                var nomineeGroup = $(this).data('group');  // Assuming each nominee has a data-group attribute
                console.log(nomineeGroup)
                if (filterType === 'all' || nomineeGroup === filterType) {
                    $(this).show();  // Show this nominee
                } else {
                    $(this).hide();  // Hide this nominee
                }
            });
        });

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

<?php echo $content_bottom; ?>
<?php echo $footer; ?>