$(function () {
    yaGoal('goal-reg-entry')

    // $('.regphone__inp input[name="telephone"]').inputmask("+9{0,30}");

    $(document).on('click', '.reginfo.short .reginfo__name', function (e) {
        e.preventDefault();
        if ($(window).width() < 992) {
            var
                $cont = $('.reginfo'),
                $data = $('.reginfo__data');
            if ($cont.hasClass('active')) {
                $cont.removeClass('active');
                $data.stop(true, true).slideUp();
            } else {
                $cont.addClass('active');
                $data.stop(true, true).slideDown();
            }
        }
    })

    $(document).on('submit', '#registration-number', function (e) {
        e.preventDefault();
        var
            form = $(this),
            data = $(this).serialize(),
            telephone = form.find('input[name="telephone"]'),
            email = form.find('input[name="email"]'),
            $rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
            mess = form.find('.reg__error'),
            error = false;

        var code = $('.iti__selected-dial-code').text();

        form.find('.invalid').removeClass('invalid');
        var isPhoneValid = $('#telephone').intlTelInput('isValidNumber');
        console.log(isPhoneValid)

        if (isPhoneValid === false) {
            addInvalid(telephone.closest('.regphone__inp'));
            error = true;
            mess.show()
            mess.text("Введите правильный номер телефона")
            $.ajax({
                type: "POST",
                url: "index.php?route=register/login/logPhone",
                dataType: "json",
                data: {
                    'telephone': code + $('input[name="telephone"]').val()
                },
                beforeSend: function (json) {
                },
                complete: function (json) {
                },
                success: function (json) {
                },
                error: function (json) {

                }
            });
        }

        if (email.length && email.is(':visible')) {
            if (email.val().length < 1 || !$rv_email.test(email.val())) {
                addInvalid(email.closest('.regform__inp'));
                error = true;
            }
        }

        if (!error) {
            $.ajax({
                type: "POST",
                url: "index.php?route=register/event/authorize",
                dataType: "json",
                data: {
                    'telephone': code + $('input[name="telephone"]').val(),
                    'email': email.val(),
                    'r': $('input[name="r"]').val(),
                    'sid': $('input[name="sid"]').val()
                },
                beforeSend: function (json) {
                    $('.reg__load').fadeIn();
                },
                complete: function (json) {
                    $('.reg__load').fadeOut();
                },
                success: function (json) {
                    if (json['error']) {
                        mess.text(json['error']);
                        if (mess.is(':hidden')) {
                            mess.stop(true, true).slideDown();
                        }

                        if (json['show_email']) {
                            form.find('.regphone__email').stop(true, true).slideDown();
                        }

                    } else if (mess.is(':visible')) {
                        mess.stop(true, true).slideUp();
                    }

                    if (json['template']) {
                        $('.regdata').html(json['template']);
                    }
                },
                error: function (json) {
                    console.log('authorize error', json);
                }
            });
        }

        return false;
    });

    $(document).on('submit', '#registration-phone-number', function (e) {
        e.preventDefault();
        var
            form = $(this),
            data = $(this).serialize(),
            telephone = form.find('input[name="telephone"]'),
            mess = form.find('.reg__error'),
            error = false;

        var code = $('.iti__selected-dial-code').text();

        form.find('.invalid').removeClass('invalid');
        var isPhoneValid = $('#telephone').intlTelInput('isValidNumber');

        if (isPhoneValid === false) {
            addInvalid(telephone.closest('.regphone__inp'));
            error = true;
            mess.show()
            mess.text("Введите правильный номер телефона")
            $.ajax({
                type: "POST",
                url: "index.php?route=register/login/logPhone",
                dataType: "json",
                data: {
                    'telephone': code + $('input[name="telephone"]').val()
                },
                beforeSend: function (json) {
                },
                complete: function (json) {
                },
                success: function (json) {
                },
                error: function (json) {

                }
            });
        }

        if (!error) {
            $.ajax({
                type: "POST",
                url: "index.php?route=register/form/authorize",
                dataType: "json",
                data: {
                    'telephone': code + $('input[name="telephone"]').val(),
                    'r': $('input[name="r"]').val(),
                    'sid': $('input[name="sid"]').val()
                },
                beforeSend: function (json) {
                    $('.reg__load').fadeIn();
                },
                complete: function (json) {
                    $('.reg__load').fadeOut();
                },
                success: function (json) {
                    console.log(json)
                    if (json['error']) {
                        mess.text(json['error']);
                        if (mess.is(':hidden')) {
                            mess.stop(true, true).slideDown();
                        }
                        return;

                    } else if (mess.is(':visible')) {
                        mess.stop(true, true).slideUp();
                    }

                    if (json['template']) {
                        $('.regdata').html(json['template']);
                    }
                },
                error: function (json) {
                    console.log('authorize error', json);
                }
            });
        }

        return false;
    });

    $(document).on('submit', '#registration-code', function (e) {
        e.preventDefault();
        var
            form = $(this),
            data = $(this).serialize(),
            code = form.find('input[name="code"]'),
            mess = form.find('.reg__error'),
            error = false;

        $.ajax({
            type: "POST",
            url: "index.php?route=register/event/inputCode",
            dataType: "json",
            data: form.serialize(),
            beforeSend: function (json) {
                $('.reg__load').fadeIn();
            },
            complete: function (json) {
                $('.reg__load').fadeOut();
            },
            success: function (json) {
                if (json['reload']) {
                    location.reload();
                }
                if (json['error']) {
                    code.val('');
                    mess.text(json['error']);
                    if (mess.is(':hidden')) {
                        mess.stop(true, true).slideDown();
                    }
                } else if (mess.is(':visible')) {
                    mess.stop(true, true).slideUp();
                }

                if (json['template']) {
                    $('.regdata').html(json['template']);
                    $('.reginfo').addClass('short');
                    yaGoal('proverochniy_kod');
                }
            },
            error: function (json) {
                console.log('inputCode', json);
            }
        });
    })

    $(document).on('click', '#button-change', function (e) {
        e.preventDefault();

        var form = $(this).closest('form');
        var companyInput = form.find('input[name="company"]');

        $.ajax({
            type: "POST",
            url: "index.php?route=register/event/changeData",
            dataType: "json",
            data: form.serialize(),
            beforeSend: function (json) {
                $('.reg__load').fadeIn();
            },
            complete: function (json) {
                $('.reg__load').fadeOut();
            },
            success: function (json) {
                if (json['reload']) {
                    // location.reload();
                }
                if (json['template']) {
                    $('.regdata').html(json['template']);
                }

                if (companyInput.val() === '') {
                    $('#button-save').prop("disabled", true)
                } else {
                    $('#button-save').prop("disabled", false)
                }
            },
            error: function (json) {
                console.log('change data', json);
            }
        });
    })

    $(document).on('click', '#button-notme', function (e) {
        e.preventDefault();

        var form = $('form.regform');

        $.ajax({
            type: "POST",
            url: "index.php?route=register/event/notmeData",
            dataType: "json",
            data: form.serialize(),
            beforeSend: function (json) {
                $('.reg__load').fadeIn();
            },
            complete: function (json) {
                $('.reg__load').fadeOut();
            },
            success: function (json) {
                if (json['reload']) {
                    location.reload();
                }
                if (json['template']) {
                    $('.regdata').html(json['template']);
                }
            },
            error: function (json) {
                console.log('notme data', json);
            }
        });
    })

    $(document).on('click', '#button-save', function (e) {
        e.preventDefault();

        initialFormState = $('#register-newuser').serialize();
        var
            form = $(this).closest('form'),
            data = form.serialize(),
            $name = form.find('input[name="name"]'),
            $lastname = form.find('input[name="lastname"]'),
            $company = form.find('input[name="company"]'),
            $email = form.find('input[name="email"]'),
            $post = form.find('input[name="post"]'),
            $rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/,
            error = false,
            error_company = false;

        $('.invalid').removeClass('invalid');

        if ($email.length) {
            if ($email.val().length < 1 || !$rv_email.test($email.val())) {
                addInvalid($email.closest('.regform__inp'));
                error = true;
                console.log('error1');
            }
        }

        if ($post.length) {
            if ($post.val().length < 1) {
                addInvalid($post.closest('.regform__inp'));
                error = true;
                console.log('error2');
            }
        }

        if ($name.length) {
            if ($name.val().length < 1) {
                addInvalid($name.closest('.regform__inp'));
                error = true;
                console.log('error3');
            }
        }

        if ($lastname.length) {
            if ($lastname.val().length < 1) {
                addInvalid($lastname.closest('.regform__inp'));
                error = true;
                console.log('error4');
            }
        }

        var input_arr = [];
        var company_arr = [];

        if ($name.length) {
            input_arr.push($name);
        }
        if ($lastname.length) {
            input_arr.push($lastname);
        }

        $.each(input_arr, function (key, item) {
            if (item.val().length < 2) {
                addInvalid(item.closest('.regform__inp'));
                error = true;
                console.log('error3');
            }
        })

        if (!form.find('input[name="b24_company_old_id"]').length || !form.find('input[name="b24_company_id"]').length) {
            addInvalid(this.closest('#regbrand-search'));
            form.find('.error-message').show();
            console.log('error4');
            error = true;
            error_company = true;
        } else {
            if (!form.find('input[name="city"]').hasClass('noedit')) {
                company_arr.push(form.find('input[name="city"]'));
            }
            if (form.find('input[name="company_phone"]') && !form.find('input[name="company_phone"]').hasClass('noedit')) {
                company_arr.push(form.find('input[name="company_phone"]'));
            }
            if (!form.find('input[name="company_site"]').hasClass('noedit')) {
                company_arr.push(form.find('input[name="company_site"]'));
            }
            var company_activity = form.find('input[name="company_activity"]').closest('.regform__inp');

            if (!form.find('.regbrand__fields').hasClass('noedit') && $('.regform__select--text span').text().trim() === '') {
                addInvalid(company_activity);
                error = true;
                console.log('error5');
                error_company = true;
            }

            $.each(company_arr, function (key, item) {
                if (item.val().length < 2) {
                    addInvalid(item.closest('.regform__inp'));
                    error = true;
                    console.log('error6');
                    error_company = true;
                }
            })
        }

        if (error_company) {
            addInvalid($('.regbrand'));
        }

        /*
        if(form.find('input[name="company_status"]').val() === 'new') {
            company_arr.push(form.find('input[name="city"]'));
            company_arr.push(form.find('input[name="company_phone"]'));
            company_arr.push(form.find('input[name="company_site"]'));

            if(!form.find('input[name="company_activity[]"]:checked').length) {
                addInvalid($('.regcompact__title'));
                error = true;
                error_company = true;
            }
        }
        $.each(company_arr, function(key, item){
            if(item.val().length < 2 ){
                addInvalid(item.closest('.regform__inp'));
                error = true;
                error_company = true;
            }
        })

        if($company.val().length < 2 || error_company ){
            addInvalid($company.closest('.regform__inp'));
            error = true;
            error_company = true;
        }*/

        if (!error) {
            $.ajax({
                type: "POST",
                url: "index.php?route=register/event/saveData",
                dataType: "json",
                data: form.serialize() + '&userFieldsChanged=' + userFieldsChanged,
                beforeSend: function (json) {
                    $('.reg__load').fadeIn();
                },
                complete: function (json) {
                    $('.reg__load').fadeOut();
                },
                success: function (json) {
                    if (json['reload']) {
                        console.log('1')
                        location.reload();
                    }
                    if (json['template']) {
                        console.log('2')
                        $('.regdata').html(json['template']);
                        yaGoal('personalnie');
                    }


                },
                error: function (json) {
                    console.log('save data', json);
                }
            });
        }
    })

    $(document).on('click', '#button-register', function (e) {
        e.preventDefault();
        yaGoal('goal-reg-start')
        var
            form = $(this).closest('form'),
            $company = form.find('input[name="company"]'),
            error = false;

        form.find('.invalid').removeClass('invalid');

        if ($company.length) {
            if ($company.val().length < 2) {
                addInvalid($company.closest('.regform__inp'));
                $company.closest('.regform__outer').find('.error-message').show();
                error = true;
            }
        }

        if (!error) {
            $.ajax({
                type: "POST",
                url: "index.php?route=register/event/checkPromo",
                dataType: "json",
                data: form.serialize(),
                beforeSend: function (json) {
                    $('.reg__load').fadeIn();
                },
                complete: function (json) {
                    $('.reg__load').fadeOut();
                },
                success: function (json) {
                    if (json['reload']) {
                        location.reload();
                    }
                    if (json['template']) {
                        $('.regdata').html(json['template']);
                        yaGoal('proverka');
                    }
                },
                error: function (json) {
                    console.log('showPromo', json);
                }
            });
        }
    })

    $(document).on('click', '.regpromo__button', function (e) {
        e.preventDefault();
        var
            btn = $(this),
            mess = btn.closest('.regpromo__inp').siblings('.reg__error'),
            data = '',
            sid = $('.regpromo input[name="sid"]').val();

        if (btn.attr('data-promo') == 1) {
            data = 'sid=' + sid + '&hasPromo=1&promo=' + btn.siblings('input[name="promo"]').val();
        } else {
            data = 'sid=' + sid + '&hasPromo=0';
        }

        $.ajax({
            type: "POST",
            url: "index.php?route=register/event/register",
            dataType: "json",
            data: data,
            beforeSend: function (json) {
                $('.reg__load').fadeIn();
            },
            complete: function (json) {
                $('.reg__load').fadeOut();
            },
            success: function (json) {
                yaGoal('register-success')

                if (json['reload']) {
                    location.reload();
                }

                if (json['error']) {
                    mess.text(json['error']);
                    if (mess.is(':hidden')) {
                        mess.stop(true, true).slideDown();
                    }
                } else if (mess.is(':visible')) {
                    mess.stop(true, true).slideUp();
                }

                if (json['template']) {
                    $('.regdata').html(json['template']);

                }
            },
            error: function (json) {
                console.log('register', json);
            }
        });
    })

    $(document).on('click', '#register-user', function (e) {
        e.preventDefault();
        var
            btn = $(this),
            promo_mess = btn.closest('.regpromo__inp').siblings('.regform__inp-error'),
            code_mess = btn.closest('.regphone__input').siblings('.regform__inp-error'),
            data = '',
            sid = $('#register-newuser input[name="sid"]').val();

        var name = $('input[name="name"]').val();
        var lastname = $('input[name="lastname"]').val();
        var email = $('input[name="email"]').val();
        var post = $('input[name="post"]').val();
        var degree = $("input[name='degree']:checked").val();
        var group = $("input[name='group']:checked").val();

        var companyName = $('input[name="company"]').val();
        var city = $('input[name="city"]').val();
        var companyPhone = $('input[name="company_phone"]').val();
        var companySite = $('input[name="company_site"]').val();
        var companyActivity = $('input[name="company_activity"]:checked').val();
        var companyB24Id = $('input[name="b24_company_id"]').val();
        var type = $("input[name='type']:checked").val();
        var sphere = $("input[name='sphere']:checked").val();

        var inputCode = $('input[name="code"]').val();
        var promo = $('input[name="promo"]').val();

        if (!name) {
            $("input[name='name']").closest('.regform__inp').addClass('invalid');
            $("input[name='name']").closest('.regform__outer').find('.regform__inp-error').text('Введите имя').css('color', 'red');
        } else {
            $("input[name='name']").closest('.regform__inp').removeClass('invalid');
            $("input[name='name']").closest('.regform__outer').find('.regform__inp-error').text('');
        }

        if (!lastname) {
            $("input[name='lastname']").closest('.regform__inp').addClass('invalid');
            $("input[name='lastname']").closest('.regform__outer').find('.regform__inp-error').text('Введите фамилию').css('color', 'red');
        } else {
            $("input[name='lastname']").closest('.regform__inp').removeClass('invalid');
            $("input[name='lastname']").closest('.regform__outer').find('.regform__inp-error').text('');
        }

        if (!email) {
            $("input[name='email']").closest('.regform__inp').addClass('invalid');
            $("input[name='email']").closest('.regform__outer').find('.regform__inp-error').text('Введите email').css('color', 'red');
        } else {
            $("input[name='email']").closest('.regform__inp').removeClass('invalid');
            $("input[name='email']").closest('.regform__outer').find('.regform__inp-error').text('');
        }

        if (!post) {
            $("input[name='post']").closest('.regform__inp').addClass('invalid');
            $("input[name='post']").closest('.regform__outer').find('.regform__inp-error').text('Введите должность').css('color', 'red');
        } else {
            $("input[name='post']").closest('.regform__inp').removeClass('invalid');
            $("input[name='post']").closest('.regform__outer').find('.regform__inp-error').text('');
        }

        if (!companyName || !companySite || !city) {
            $(".company__block").css('border-color', 'red');
            $(".company__block").find('.regform__inp-error').text('Заполните данные о  компании').css('color', 'red');
        } else {
            $(".company__block").css('border-color', '#b5a0a0');
            $(".company__block").find('.regform__inp-error').text('');
        }

        if (!inputCode) {
            $("input[name='code']").closest('.regform__inp').addClass('invalid');
            $("input[name='code']").closest('.regform__outer').find('.regform__inp-error').text('Введите проверочный код').css('color', 'red');
        } else {
            $("input[name='code']").closest('.regform__inp').removeClass('invalid');
            $("input[name='code']").closest('.regform__outer').find('.regform__inp-error').text('');
        }

        if (!name ||
            !lastname ||
            !email ||
            !post ||
            !companyName ||
            !city ||
            !companySite ||
            !inputCode
        ) {
            if (inputCode) {
                $("html, body").animate({
                    scrollTop: $(".reguser").offset().top
                });

            } else {
                $("html, body").animate({
                    scrollTop: $("input[name='code']").closest('.regform__outer').offset().top
                });
            }
            return;

        }

        console.log(degree)
        console.log(group)
        console.log(type)
        console.log(sphere)

        if (promo) {
            data = 'sid=' + sid
                + '&hasPromo=1&promo=' + promo
                + '&code=' + inputCode
                + '&name=' + name
                + '&lastname=' + lastname
                + '&email=' + email
                + '&post=' + post
                + '&company=' + companyName
                + '&company_city=' + city
                + '&company_phone=' + companyPhone
                + '&company_site=' + companySite
                + '&company_activity=' + companyActivity
                + '&company_b24_id=' + companyB24Id
                + '&user_field_changed=' + userFieldsChanged
                + '&form_register=' + true
                + '&company_changed=' + companyChanged
                + '&degree=' + degree
                + '&group=' + group
                + '&type=' + type
                + '&sphere=' + sphere
            ;
        } else {
            data = 'sid=' + sid
                + '&hasPromo=0'
                + '&code=' + inputCode
                + '&name=' + name
                + '&lastname=' + lastname
                + '&email=' + email
                + '&post=' + post
                + '&company=' + companyName
                + '&company_city=' + city
                + '&company_phone=' + companyPhone
                + '&company_site=' + companySite
                + '&company_activity=' + companyActivity
                + '&company_b24_id=' + companyB24Id
                + '&user_field_changed=' + userFieldsChanged
                + '&form_register=' + true
                + '&company_changed=' + companyChanged
                + '&degree=' + degree
                + '&group=' + group
                + '&type=' + type
                + '&sphere=' + sphere
            ;
        }

        $.ajax({
            type: "POST",
            url: "index.php?route=register/form/register",
            dataType: "json",
            data: data,
            beforeSend: function (json) {
                $('.reg__load').fadeIn();
            },
            complete: function (json) {
                $('.reg__load').fadeOut();
            },
            success: function (json) {
                yaGoal('register-success')

                if (json['reload']) {
                    location.reload();
                }

                console.log(json)

                if (json['code_error'] ) {
                    $("input[name='code']").closest('.regform__outer').find('.regform__inp-error').text('Неверный проверочный код').css('color', 'red')
                } else if (code_mess.is(':visible')) {
                    $("input[name='code']").closest('.regform__outer').find('.regform__inp-error').text('');
                }

                if (json['promo_error'] ) {
                    $("input[name='promo']").closest('.regform__outer').find('.regform__inp-error').text('Введите промокод').css('color', 'red')
                } else if (promo_mess.is(':visible')) {
                    $("input[name='promo']").closest('.regform__outer').find('.regform__inp-error').text('');

                }

                if (json['template']) {
                    $('.regdata').html(json['template']);

                }
            },
            error: function (json) {
                console.log('register', json);
            }
        });
    })

});
