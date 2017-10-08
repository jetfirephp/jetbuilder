jQuery(document).ready(function () {
    jQuery("#register_form").validate({
        meta: "validate",
        submitHandler: function (e) {
            $("#register_form .btn-submit").hide();
            $("#register_form .btn-loading").show();
            var api = $('#register_form').attr('action'),
                s = $("#society").val(),
                f = $("#first_name").val(),
                l = $("#last_name").val(),
                a = $("#address").val(),
                p = $("#postal_code").val(),
                c = $("#city").val(),
                t = $("#phone").val(),
                em = $("#email").val(),
                ps = $("#password").val(),
                cps = $("#confirm_pass").val(),
                token = $("#token").val();
            captcha = $("#g-recaptcha-response").val();
            return $.post(api, {
                society: {
                    name: s
                },
                account: {
                    first_name: f,
                    last_name: l,
                    phone: t,
                    email: em,
                    password: ps,
                    confirm_pass: cps
                },
                address: {
                    address: a,
                    postal_code: p,
                    city: c
                },
                _token: token,
                captcha: captcha
            }, function (response) {
                $("#register_form .btn-submit").show();
                $("#register_form .btn-loading").hide();
                $("#response-message").show();
                (response.status == 'error')
                    ? $("#response-message").addClass('alert-danger')
                    : $("#response-message").addClass('alert-success');
                var message = '<div>';
                if (response.message instanceof Array || response.message instanceof Object) {
                    $.each(response.message, function (k, el) {
                        $.each(el, function (l, item) {
                            message += '<p>' + item + '</p>';
                        })
                    })
                } else
                    message += response.message;
                $("#response-message span").html(message + '</div>');
            }), !1
        },
        rules: {
            society: {
                name: "required"
            },
            account: {
                first_name: "required",
                last_name: "required",
                phone: {
                    required: true,
                    minlength: 9
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_pass: {
                    required: true,
                    minlength: 5,
                    equalTo: '#password'
                }
            },
            address: {
                address: "required",
                postal_code: {
                    required: true,
                    number: true
                },
                city: "required"
            },
            captcha: "required",
            _token: "required"
        },
        messages: {
            "society[name]": "Le nom de la société est requis",
            "account[first_name]": "Votre prénom est requis",
            "account[last_name]": "Votre nom est requis",
            "account[phone]": {
                required: "Le numéro de téléphone est requis",
                minlength: "Le format du numéro de téléphone n'est pas correct"
            },
            "account[email]": {
                required: "L'email est requis",
                email: "Le format du mail est incorrect"
            },
            "account[password]": {
                required: "Le mot de passe est requis",
                minlength: "Le mot de passe doit contenir minimum 5 caractères"
            },
            "account[confirm_pass]": {
                required: "La confirmation du mot de passe est requis",
                minlength: "Le mot de passe doit contenir minimum 5 caractères",
                equalTo: "Les mots de passe ne sont pas identiques"
            },
            "address[address]": "L'adresse est requis",
            "address[postal_code]": {
                required: "Le code postal est requis",
                number: "Le format du code postal est incorrect"
            },
            "address[city]": "Veuillez renseigner le ville",
            captcha: "Le captcha est requis",
            _token: "Le token est requis"
        }
    })
});
