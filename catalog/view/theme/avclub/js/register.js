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
        console.log('1')
        if (userFieldsChanged) {
            console.log('Данные пользователя изменились!');
        } else {
            console.log('Данные пользователя НЕ изменились');
        }

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

            console.log(!form.find('.regform__select--text').hasClass('noedit'))
            console.log($('.regform__select--text span').html().trim() === '')
            if (!form.find('.regform__select--text').hasClass('noedit') && $('.regform__select--text span').html().trim() === '') {
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


});
