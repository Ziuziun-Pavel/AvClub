hideLastBranch = function (list) {
    $(list).find('li').not('.d-none').not('.showmore').last().addClass('d-none');
    var
        $h_item = $(list).find('li').eq(0).outerHeight(true),
        $h_cont = $(list).outerHeight(),
        $row = 5;
    if ($(window).width() < 768) {
        $row = 4;
    }
    if ($h_item * $row < $h_cont) {
        hideLastBranch(list);
    }


}
changeViewBranch = function (list) {
    $(list).removeClass('active');

    var
        $h_item = $(list).find('li').eq(0).outerHeight(true),
        $h_cont = 0,
        $row = 5;


    $(list).find('li').removeClass('d-none');
    $(list).find('.experttag_btn').remove();

    if ($(window).width() < 768) {
        $row = 4;

    }
    $height = $h_item * $row;

    $h_cont = $(list).outerHeight();

    if ($height <= $h_cont) {
        $(list).find('ul').append('<li class="experttag_btn showmore"><svg><use xlink:href="#points" /></svg><svg><use xlink:href="#arr-down" /></svg></li>');
        hideLastBranch(list);
    }

}
$(function () {

    $(document).on('click', '.expert__bio--btn a', function (e) {
        e.preventDefault();

        var
            button = $(this),
            cont = button.closest('.expert__bio--btn'),
            hidden = cont.siblings('.expert__bio--more');

        if (hidden.is(':visible')) {
            hidden.stop(true, true).slideUp();
            button.text(button.attr('data-passive'));
        } else {
            hidden.stop(true, true).slideDown();
            button.text(button.attr('data-active'));
        }
    })

    if ($('.experttag__list li').length) {

        $(window).on('load resize', function () {
            $('.experttag__list').each(function (key, item) {
                changeViewBranch(item);
            })
        });
        $(document).on('click', '.experttag_btn', function (e) {
            e.preventDefault();
            $(this).closest('.experttag__list').toggleClass('active');
        })
    }


    $(document).on('click', '.expert__pane .pagination a', function (e) {
        e.preventDefault();
        var
            value = [],
            page = 1,
            method = '',
            expert_id = $('.expertnav').attr('data-id'),
            container = $(this).closest('.expert__pane'),
            type = container.attr('data-key'),
            query = $(this).attr('href').split('?');

        if (query[1]) {
            var part = query[1].split('&');
            for (i = 0; i < part.length; i++) {
                var data = part[i].split('=');
                if (data[0] && data[1]) {
                    value[data[0]] = data[1];
                }
            }

            if (value['page']) {
                page = value['page'];
            }
        }

        switch (type) {
            case 'event':
            case 'avevent':
                method = 'avevent';
                break;

            case 'master':
            case 'online':
                method = 'master';
                break;

            default:
                method = type;
                break;
        }


        var $data = 'json=1';
        if (expert_id) {
            $data += '&expert_id=' + expert_id;
        }
        if (page) {
            $data += '&page=' + page;
        }
        if (type) {
            $data += '&type=' + type;
        }

        $.ajax({
            url: 'index.php?route=expert/content/json_' + method,
            type: 'get',
            dataType: 'json',
            data: $data,
            beforeSend: function () {
                container.addClass('loading');
            },
            complete: function () {
                setTimeout(function () {
                    container.removeClass('loading');
                }, 300);
            },
            success: function (data) {
                if (data['template']) {
                    container.html(data['template']);
                }
            },
            error: function (data) {
                console.log('error', data);
            }
        });


    })


    $('.expertnav__change').on('click', function (e) {
        e.preventDefault();
        var $type = $(this).attr('data-type');
        $(this).addClass('active').parent().siblings('li').find('a.active').removeClass('active');
        $('.expert__pane-' + $type).addClass('active').siblings('.expert__pane').removeClass('active');
        $('.expertnav__sub-' + $type).addClass('active').siblings('.active').removeClass('active');

        if (!$(this).hasClass('load')) {
            $(this).addClass('load');

            var
                expert_id = $('.expertnav').attr('data-id'),
                $child_type = $('.expertnav__sub .expertnav__sub-' + $type).find('.expertnav__change.active'),
                $url = '&expert_id=' + expert_id;

            if ($child_type.length) {
                $type = $child_type.attr('data-type');
                $child_type.addClass('load');
            }

            $url += '&type=' + $type;

            $.ajax({
                url: 'index.php?route=expert/content' + $url,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $('.expertrow__content').addClass('loading');
                },
                complete: function () {
                    setTimeout(function () {
                        $('.expertrow__content').removeClass('loading');
                    }, 200);
                },
                success: function (data) {
                    if (data['template']) {
                        $('.expert__pane-' + $type).html(data['template']);
                    }
                },
                error: function (data) {
                    console.log('error', data);
                }
            });


        }

    })

    $(document).on('click', '.expertnav__tab-tab', function (e) {
        e.preventDefault();
        if (!$(this).hasClass('active')) {
            var tab = $(this),
                type = tab.attr('data-type');

            tab.addClass('active').siblings('a').removeClass('active');
            $('#navlist-' + type).addClass('active').siblings('.expertnav__list').removeClass('active');
            $('#content-' + type).addClass('active').siblings('.expert__content').removeClass('active');
        }

        if ($(this).hasClass('active')) {
            var tab = $(this),
                type = tab.attr('data-type');

            if (type === 'events') {
                $('.events__tabs').removeClass('d-none');
                $('#navlist-bio').addClass('d-none');
                $('.expertnav__tab-tab.reg').addClass('active');
                $('.expertnav__tab-tab.fut_ev').removeClass('active');
                $('.expertnav__tab-tab.publications').removeClass('active');

                $('#content-webinars').removeClass('d-none');
                $('#content-webinars .expreg').removeClass('d-none');
                $('#content-webinars.expert__content').css("display", "block");
            } else {
                $('#content-webinars .expreg').addClass('d-none');
                $('#content-webinars.expert__content').css("display", "none");
                $('#content-webinars').addClass('d-none');
            }

            if(type === 'webinars' || type === 'events') {
                $('#content-webinars .expreg').removeClass('d-none');
                $('#content-webinars.expert__content').css("display", "block");
                $('#content-webinars').removeClass('d-none')
            } else {
                $('#content-webinars .expreg').addClass('d-none');
                $('#content-webinars.expert__content').css("display", "none");
                $('#content-webinars').addClass('d-none');
                $('#navlist-bio').addClass('d-none')
            }

            if(type === 'future_events') {
                $('#content-events .expreg').removeClass('d-none');
                $('#content-events.expert__content').css("display", "block");
                $('#content-events').removeClass('d-none')
                $('#navlist-bio').addClass('d-none')
            } else {
                $('#content-events .expreg').addClass('d-none');
                $('#content-events.expert__content').css("display", "none");
                $('#content-events').addClass('d-none');
            }

            if(type === 'catalog_list') {
                $('#catalog_list .expreg').removeClass('d-none');
                $('#catalog_list.expert__content').css("display", "block");
                $('#catalog_list').removeClass('d-none')
                $('#navlist-bio').addClass('d-none')
            } else {
                $('#catalog_list .expreg').addClass('d-none');
                $('#catalog_list.expert__content').css("display", "none");
                $('#catalog_list').addClass('d-none');
            }

            if(type === 'publications') {
                $('#publist .expreg').removeClass('d-none');
                $('#publist.expert__content').css("display", "block");
                $('#publist').removeClass('d-none')
                $('#navlist-bio').addClass('d-none')
            } else {
                $('#publist .expreg').addClass('d-none');
                $('#publist.expert__content').css("display", "none");
                $('#publist').addClass('d-none');
            }

            if (type === 'bio') {
                $('#content-' + type).addClass('active');
                $('#navlist-' + type).removeClass('d-none');
                $('.events__tabs').addClass('d-none');
            }

        }

    })

    $('.form__expert').on('submit', function () {
        var
            $form = $(this),
            $data = $form.serialize(),
            $name = $form.find('input[name="name"]'),
            $company = $form.find('input[name="company"]').not('.not_req'),
            $phone = $form.find('input[name="phone"]').not('.not_req'),
            $email = $form.find('input[name="email"]'),
            $web = $form.find('input.req[name="web"]'),
            $comment = $form.find('textarea.req[name="message"]'),
            $agree = $form.find('input[name="agree"]:checked'),
            $rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
            $error = false;


        if ($form.find('input[name="agree"]').length && !$agree.length) {
            $form.find('.agree__cont').addClass('invalid');
            $error = true;
        } else {
            $form.find('.agree__cont').removeClass('invalid');
        }

        if ($name.length) {
            if ($name.val().length < 3) {
                $name.addClass('invalid');
                $error = true;
            } else {
                $name.removeClass('invalid');
            }
        }

        if ($web.length) {
            if ($web.val().length < 4) {
                $web.addClass('invalid');
                $error = true;
            } else {
                $web.removeClass('invalid');
            }
        }

        if ($comment.length) {
            if ($comment.val().length < 5) {
                $comment.addClass('invalid');
                $error = true;
            } else {
                $comment.removeClass('invalid');
            }
        }

        if ($company.length) {
            if ($company.val().length < 2) {
                $company.addClass('invalid');
                $error = true;
            } else {
                $company.removeClass('invalid');
            }
        }
        if ($phone.length) {
            if (!$phone.inputmask('isComplete')) {
                $phone.addClass('invalid');
                $error = true;
            } else {
                $phone.removeClass('invalid');
            }
        }
        if ($email.length) {
            if ($email.val().length < 1 || !$rv_email.test($email.val())) {
                $email.addClass('invalid');
                $error = true;
            } else {
                $email.removeClass('invalid');
            }
        }

        if ($error) {
            return false;
        } else {
            $.ajax({
                type: "POST",
                url: "index.php?route=expert/expert/sendMail",
                dataType: "json",
                data: $data,
                beforeSend: function ($json) {

                },
                success: function ($json) {
                    if ($json['success']) {
                        $('.form__expert')[0].reset();
                        $instance = $.fancybox.getInstance();
                        if ($instance) {
                            $instance.close();
                        }
                        showModal('#expert_success', "fancy-vert");
                    }

                },
                error: function () {

                }
            });
        }
        return false;
    })
})