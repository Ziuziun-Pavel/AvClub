<?php echo $header; ?>
<?php echo $content_top; ?>

<div id="form-content">

</div>


<script>

    $(function () {
        var error_text = '<div class="expreg__message --loading">Ошибка отправки данных.<br>Попробуйте обновить страницу или повторить попытку немного позже</div>';

        const data = {
            'id': 12
        };

        $.ajax({
            type: "GET",
            url: "index.php?route=expert/expert/sendPublication",
            dataType: "json",
            data: data,
            beforeSend: function (json) {
            },
            complete: function (json) {
            },
            success: function (json) {

                if (json['template']) {
                    $('#form-content').html(json['template']);
                } else if (json['error']) {
                    $('#form-content').html(error_text);
                }
            },
            error: function (json) {
                $('#form-content').html(error_text);
                console.log('publication sendPublication error', json);
            }
        });

    })

</script>

<style>
    .events__tabs {
        width: 100%;
        margin-top: 14px;
        font-size: .8rem;
    }
</style>

<?php echo $footer; ?>