$(document).ready(() => {
    //--hash

    let hash = window.location.hash
    if (hash == '') {
        hash = '#DoCommande'
    }
    if (hash != "" && !$(' .navigation a[href="' + hash + '" ]').hasClass('nav-element-actif')) {

        let $elemnt = $('[href="' + hash + '"]')
        let $href = $($elemnt).attr('href')
        $('.actif').removeClass('.actif')
        $($elemnt).addClass('actif')

        $($href).siblings('section').hide()
        $($href).show()
    }
    //-- navigation

    $('.navigation a ').click(e => {
        let $ths = $(e.target).attr('href')
        $(e.target).siblings('.actif').removeClass('actif')
        $(e.target).addClass('actif')

        $($ths).slideDown(300)
        $($ths).siblings().slideUp(300)

    })

    //---- edit info form


    $('section#clientInfo p a').click(ev => {
        ev.preventDefault()
        let $ths2 = $(ev.target).attr('href')

        $(' #clientInfo  form').addClass('d-none')
        $($ths2).removeClass('d-none')

    })



    ///-----ajax request 

    let ajax = (optioin, result) => {
        $.post(
            "server/cmd/index.php", // Un script PHP que l'on va créer juste après
            optioin,
            result,
            "text"
        );
    };

    //----- for FIles Beaff  

    $('#beAffiliat form').submit(e => {
        e.preventDefault()

        var ajaxData = new FormData();
        ajaxData.append('action', 'uploadImages');
        jQuery.each($("input[name^='photo']"), function(i, input) {
            let file = input.files
            if (file.length > 0) {
                jQuery.each(file, function(j, info) {
                    ajaxData.append('photo' + i, info);
                });
            }
        });
        $.ajax({
            url: "server/cmd/index.php",
            data: ajaxData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'text',
            success: function(data) {

                if (data == 'success') {
                    $('#beAffiliat').html('<div class="text-center alert alert-success ">  <pre>  Votre demande a était Validé ! Nous la Traiterons sous peu <br> Merci ! </pre>  <hr> </div>')


                }
            }
        });
    })

    /// -- ModifyUser


    $('section#clientInfo  form , #DoCommande_form  ').submit(e => {
        e.preventDefault();
        var option = {
            form: e.target.getAttribute('id'),
            btnid: $('#' + e.target.getAttribute('id') + " button ").attr("id")
        }
        jQuery.each($("#" + e.target.getAttribute('id') + " input  , " + "#" + e.target.getAttribute('id') + " textarea "), (i, input) => {
            option = Object.assign({
                [input.getAttribute('name')]: $(input).val()
            }, option);
        })
        console.log('option:', option)
        ajax(option, (data) => {

            $(".alert ").parent().html('  ')

            console.log('data:', data)
            if (data.includes("success")) {

                $(e.target).html(' <p class="my-4 alert alert-success "> ' + data + ' </p> ')
            } else {
                $('<div>').html(' <p class="my-4 alert alert-danger"> ' + data + '</p> ').appendTo($(e.target));
            }
        })
    });

    //-- dlete CMD
    $("a[id^='DeletCmd-']").click(e => {

        e.stopPropagation()
        e.preventDefault()
        let DeletCmd = $(e.target).attr('id').split('-')[1]
        ajax({
            req: 'DeletCmd',
            CMDid: DeletCmd
        }, data => {

            if (data == 'success') {
                $(e.target).parent().parent().remove()
            }
        })
    })

    //--- edit CMD

    $("a[id^='EditCmd-']").click(e => {

        e.stopPropagation()
        e.preventDefault()
        let EditCmdid = $(e.target).attr('id').split('-')[1]
        $('#DoCommande_form button').attr("id", EditCmdid)
        console.log('id:', EditCmdid)
        let $type = $('#type-' + EditCmdid).text()
        let $desct = $('#description-' + EditCmdid).text()
        console.log('type:', $type)
        console.log('desct:', $desct)

        $('a[href="#DoCommande"]').siblings('.actif').removeClass('actif')
        $('a[href="#DoCommande"]').addClass('actif')
        $("#DoCommande").slideDown(300)
        $("#DoCommande").siblings().slideUp(300)
        $('#type').val($type)
        $('#descr').val($desct)

    })


});