$(document).ready(() => {



    $(" .flexsignup  form  ").submit(e => {
        e.preventDefault();
        let $emil = $("#email").val()
        $.post(
            'server/signup/index.php', // Un script PHP que l'on va créer juste après
            {

                nom: $("#nom").val(),
                prenom: $("#prenom").val(),
                tel: $("#tel").val(),
                email: $emil,
                pass: $("#pass").val(),
                conditions: $("#conditions").val(),
                pass2: $("#pass2").val(),

            },

            data => {
                $('p.alert').remove();
                if (data == 'Success') {
                    let date = new Date();
                    $('<div>').html(' <p class="text-center">Inscription Réussit ! Un E-mail de Validation Vous a été envoyer . </p> <br> <a href="#resend" class="text-danger"> Toujour pas Reçu ? - clicker pour renvoyer</a> ').addClass('my-4 alert alert-success').appendTo($(e.target));


                    let ConfirmMail = () => {
                        $.post("server/SendEmailPost/index.php", {
                                message: "<html><body  align='center'> <h1> Vérifiez Votre E - mail  </h1><br>  <a href='127.0.0.1/server/cmd/index.php?req=confirmsub&email=" + $emil + "&t=" + date.getTime() + "'>Cliquez ici </a> <br> <br><p> En cas de non confirmation , Cet email de Confirmation sera obsolet dans 15  minutes, En cas de non confirmation Votre compte sera à tout jamais supprimez dans 24h </p> </body></html>",
                                obj: 'Validez Votre Insctiption',
                                adress: $emil
                            },
                            (d, textStatus, jqXHR) => {},
                            "text"
                        );
                    }
                    ConfirmMail()
                    $('a[href="#resend"]').click(e => {
                        e.preventDefault()
                        ConfirmMail()
                    })


                } else {

                    data = JSON.parse(data)
                    let errors = Object.keys(data)
                    let value = Object.values(data)
                    for (let i = 0; i < errors.length; i++) {


                        $('p.alert-danger').remove()
                        let $errorInpt = $('#' + errors[i])
                        $errorInpt.addClass('alert alert-danger')

                        $errorInpt.after('<p class="alert alert-danger">' + value[i] + ' </p>')

                    }


                }

            },
            'text'
        );
    });

});