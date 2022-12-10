 $(document).ready(() => {

     //-- define AJAX
     let ajax = (data, option) => {

         $.post("server/admin/index.php", data,
             option,
             "text"
         );
     }

     //------ Theme

     let hash = window.location.hash
     if (hash == '') {
         hash = '#prodiuits'
     }
     if (hash != "" && !$(' .nav-admin a[href="' + hash + '" ]').hasClass('nav-element-actif')) {

         let $elemnt = $('[href="' + hash + '"]')
         let $href = $($elemnt).attr('href')
         $('.nav-element-actif').removeClass('nav-element-actif')
         $($elemnt).addClass('nav-element-actif')

         $($href).siblings('main').hide()
         $($href).show()
         $($href + ' nav a').first().addClass('nav-element-actif')
         $($href + ' section').first().show()
     }

     $(' main .nav-admin a').click(e => {
         e.preventDefault()
         e.stopPropagation()
     })
     $(' .nav-admin a').click(e => {
         let $this = $(e.target)
         if ($this.hasClass('nav-element-actif')) {
             return false;
         }

         let $idTarget = $this.attr('href')
         $($this).siblings().removeClass('nav-element-actif')
         $($this).addClass('nav-element-actif')


         $($idTarget).show(100)
         $($idTarget).siblings('main , section').hide(100)

     })


     //---- form Add Post
     $('#fm-addPost ').submit(e => {
         e.preventDefault();
         e.stopPropagation()
         $('div#AddPostErrorMsg').html(' ')
         var option = {
             form: e.target.getAttribute('id'),
             BtnId: $('#' + e.target.getAttribute('id') + ' button').attr('id')
         }
         let FormID = e.target.getAttribute('id')
         jQuery.each($("#" + FormID + " select , " + "#" + FormID + " input[type=text]  , " + "#" + FormID + " input[type=number]  , " + "#" + FormID + " textarea "), (i, input) => {
             option = Object.assign({
                 [input.getAttribute('name')]: $(input).val()
             }, option);
         })

         ajax(
             option,
             data => {
                 if (data.includes("success")) {
                     let $id = parseInt(data.replace('success', ''))
                         //-- send Img

                     var ajaxData = new FormData();
                     ajaxData.append('action', 'uploadImages');
                     ajaxData.append('IdPost', $id);
                     jQuery.each($("input[name^='photo']"), function(i, input) {
                         let file = input.files
                         if (file.length > 0) {
                             jQuery.each(file, function(j, info) {
                                 ajaxData.append('photo' + i, info);
                             });
                         }
                     });


                     $.ajax({
                         url: "server/admin/index.php",
                         data: ajaxData,
                         cache: false,
                         contentType: false,
                         processData: false,
                         type: 'POST',
                         dataType: 'text',
                         success: function(data) {

                             if (data == 'success') {
                                 $('#fm-addPost ').html('<p class="alert alert-success ">Article Ajouté <a class="btn btn-primary" href="admin"> Ajouter un Autre Article </a> </p>')
                             } else {
                                 $('<p>').addClass("alert alert-danger").html(data).appendTo('#AddPostErrorMsg');
                             }

                         }
                     })



                 } else {
                     $('<p>').addClass("alert alert-danger").html(data).appendTo('#AddPostErrorMsg');
                 }
             })
     })

     // Add Categ

     let SetFmCategorie = e => {
         e.preventDefault()
         e.stopPropagation()
         form = $(e.target).attr('id')

         $('#MessageEroorCateg').html('')
         var idCateg = $('#' + form + ' button').attr('id')
         idCateg = idCateg.replace('#', '')
         let slug = $('#' + form + ' input[name="slug"]').val()
         let name = $('#' + form + ' input[name="name"]').val()


         ajax({
             form: form,
             slug: slug,
             name: name,
             idCateg: idCateg
         }, (data) => {

             if (data.includes('success')) {
                 $('<p class="alert alert-success">' + data + '</p>').appendTo('#' + form + '  #MessageEroorCateg')
                 if (form == 'fm-SetCateg') {

                     $(e.target).parent().html('<a href="#' + slug + '" id="' + idCateg + ' "   > ' + name + ' </a> <br> <p >SLUG:  ' + slug + '</p>  <a href="#DeleteObj" id="DeletCtg-' + idCateg + '" class = " btn text-danger " > SUpprimer </a><a href = "#EditCategorie" id = "EditCtg-' + idCateg + '"  class = "btn text-primary"> Editer </a> <hr> ')
                 } else {
                     $('<li>').html('<a href="#' + slug + '" id="' + idCateg + ' "   > ' + name + ' </a> <br> <p >SLUG:  ' + slug + '</p>  <a href="#DeleteObj" id="DeletCtg-' + idCateg + '" class = " btn text-danger " > SUpprimer </a><a href = "#EditCategorie" id = "EditCtg-' + idCateg + '"  class = "btn text-primary"> Editer </a> <hr> ').appendTo("#categs")
                 }
                 $('#fm-AddCateg input').val(' ')


             } else {
                 $('<p class="alert alert-danger">' + data + '</p><a href="admin" > annuler ? </a> ').appendTo('#' + form + ' #MessageEroorCateg')
             }
             setTimeout(() => {
                 $('#MessageEroorCateg').html(' ')
             }, 15000);
         })

     }

     $('#fm-AddCateg').submit(e => {

         e.preventDefault()
         e.stopPropagation()
         SetFmCategorie(e)
     })



     // --- Generate Users

     let SetStatus = ($stat) => {
         if ($stat == -3) {
             return '<p class="text-danger"> Bloqué</pre>';
         } else if ($stat == -2) {
             return '<p class="text-warning">En Cours de Confirmation</p>';
         } else if ($stat == 0) {
             return '<p class="text-primary">Utilisateur</p>';
         } else if ($stat == 1) {
             return '<p class="text-success">Affilié</p>';
         }
     }

     let GeneratUsers = () => {

         var option = {
             form: 'GenerateUser',
             q_user: $('#q_user').val()
         }

         let offset = $("#target-Intersection-Users").attr('href')

         if ($('#UserBoxItems').children().length >= 30) {
             option = Object.assign({ offset: offset }, option)
         }

         ajax(option, data => {
             data = JSON.parse(data);
             if (data.length == 30) {
                 $("#target-Intersection-Users").removeClass('d-none')
                 offset = parseInt(offset) + 30;
                 $("#target-Intersection-Users").attr('href', offset)
             } else {
                 $("#target-Intersection-Users").addClass('d-none')
                 $("#target-Intersection-Users").attr('href', '30')
             }

             let box = $('#UserBoxItems')

             for (let index = 0; index < data.length; index++) {
                 let user = data[index];

                 if (user.is_admin == 1) {
                     var admin = 'Administrateurr'
                 } else {
                     var admin = '-'
                 }

                 let UserOption = () => {
                     let block = '<a class="btn text-danger" href="#">bloquer</a>'
                     let beUser = '<a class="btn text-primary" href="#">Définir comme Utilisateur</a>'
                     let beAff = '<a class="btn text-secondary" href="#">Définir comme Affilié</a>'
                     let beAdmin = '<a class="btn text-success" href="#">Définir comme Administrateur</a>'
                     let beNotAdmin = '<a class="btn text-info" href="#">Retirer Des Administrateur</a>'
                     if (user.is_affiliat <= -2) {
                         return block + beUser
                     }
                     var opt = block
                     if (user.is_affiliat == 0) {
                         opt += beAff
                     }
                     if (user.is_affiliat == 1) {
                         opt += beUser
                     }
                     if (user.is_admin == 1) {
                         opt += beNotAdmin
                     }
                     if (user.is_admin == 0) {
                         opt += beAdmin
                     }
                     return opt
                 }

                 let item = $('<div>').html('<span> <strong> ' + user.id + ' </strong> <p> ' + user.nom + ' ' + user.prenom + ' </p> </span>  <span> <p>' + user.email + '</p> <p>' + user.tel + '</p> <p>' + user.adress + '</p></span>  <span> <p>' + time(user.sub_date) + '</p><p> ' + SetStatus(user.is_affiliat) + ' </p>  <p class="text-success" >' + admin + ' </p> </span> <span class="useroptionbtns"> ' + UserOption() + ' </span>  ')
                 item.attr('id', 'UserItem-' + user.id)

                 item.appendTo(box)

             }
         })
     }
     let SetUsersPanels = (e) => {
         e.preventDefault()
         let $this = $(e.target)
         let id = ($this.parent().parent().attr('id')).replace('UserItem-', '')
         var spanthird = $this.parent().parent()
         spanthird = $(spanthird.children('span')[2])


         let req = $this.text()
         ajax({
             form: "fm-setUsers",
             id: id,
             req: req
         }, data => {

             if (data == 'DéfinircommeAdministrateur') {
                 $(e.target).text('Retirer Des Administrateur')
                 $(e.target).addClass('text-info')
                 $(e.target).removeClass('text-success')
                 var item = $(spanthird.children('p')[4])
                 item.text('Administraeur')

             } else if (data == 'RetirerDesAdministrateur') {
                 $(e.target).text('Définir comme Administrateur')
                 $(e.target).addClass('text-success')
                 $(e.target).removeClass('text-info')
                 var item = $(spanthird.children()[4])
                 item.text('-')

             } else if (data == 'DéfinircommeAffilié') {
                 $(e.target).text('Définir comme Utilisateur')
                 $(e.target).addClass('text-primary')
                 $(e.target).removeClass('text-secondary')
                 var item = $(spanthird.children()[2])
                 item.text('Affiliée')
                 item.addClass('text-success')

             } else if (data == 'DéfinircommeUtilisateur') {
                 $(e.target).text('Définir comme Affilié')
                 $(e.target).addClass('text-secondary')
                 $(e.target).removeClass('text-primary')
                 var item = $(spanthird.children()[2])
                 item.text('Utilisateur')
                 item.addClass('text-primary')

             } else if (data == 'bloquer') {
                 $(e.target).text('Définir comme Utilisateur')
                 $(e.target).addClass('text-primary')
                 $(e.target).removeClass('text-secondary')
                 var item = $(spanthird.children()[2])
                 item.text('bloqué')
                 item.addClass('text-danger')
                 var itemadmin = $(spanthird.children()[4])
                 itemadmin.text('-')

             }


         })
     }


     let options = {
         root: null,
         rootMargin: '0px',
         threshold: 0.5
     }

     let observerUser = new IntersectionObserver(GeneratUsers, options);
     let target = document.querySelector('#target-Intersection-Users');
     observerUser.observe(target);


     //-*-- if search user 
     $('#q_user').change(e => {
         $('#UserBoxItems  div').remove()
         GeneratUsers()
         setTimeout(() => {
             $('span.useroptionbtns a').click(e => {
                 SetUsersPanels(e)
             })
         }, 1000);
     })


     // -- generate affiliation Demande
     ajax({
         form: 'affiliationGenerate'
     }, data => {
         let box = $("#approbation")
         data = JSON.parse(data);
         $.each(data, (i, val) => {
             let dir = Object.getOwnPropertyNames(val)[0]
             let imgs = Object.values(val)[0]
             let item = $('<div>').html('<p> ' + dir + ' </p>')
             let img_affiliation = $('<span class="imgDemandeAff" >')
             let modal_affiliation = $('<aside id="modal_' + dir + '" class="bg-color-primary text-color-primary modal fade" tabindex="100" data-width="760" >').html('<div="modal-header"><button  class=" close text-danger " data-dismiss="modal" aria-hidden="true">x</button> </div> ')

             imgs.forEach((img) => {
                 $('<button href="#modal_' + dir + '" data-toggle="modal">').html('<img src="' + img + '" alt="img"  >').appendTo(img_affiliation)

                 $('<i>').html('<img src="' + img + '" alt="img"  >').appendTo(img_affiliation).appendTo(modal_affiliation)

             });
             img_affiliation.appendTo(item)
             modal_affiliation.appendTo(item)
             if (dir.includes('user-')) {
                 $('<span class="addiliatConfigBtn fm-vertical">').html('<button id="refuser-' + dir + '"  class="my-1 btn-lg btn-danger">Refuser</button><button id="suppr-' + dir + '"  class="my-1 btn-lg btn-secondary">Supprimer</button><button id="accepte-' + dir + '"  class="my-1 btn-lg btn-success"> Accepter</button> ').appendTo(item)

             } else {
                 $('<span class="addiliatConfigBtn fm-vertical">').html('<button id="suppr-' + dir + '"  class="my-1 btn-lg btn-secondary">Supprimer</button>').appendTo(item)
             }
             item.attr('id', dir)
             item.appendTo(box)

         });
     })


     //--- Generate cmd
     let statustext = (statut) => {
         if (statut == -2) {
             return '<p Class="stat text-warning"> En Attente </p>'
         } else if (statut == 0) {
             return '<p Class="stat text-danger">Refuser</p>'
         } else if (statut == 1) {
             return '<p Class="stat text-success">Accepter</p>'
         }
     }


     let getCmdPanel = () => {
         let option = {
             form: 'getcmdPanel',
             q_cmd: $('#q_cmd').val()
         }

         $("#target-Intersection-cmd").removeClass('d-none')
         let offset = $("#target-Intersection-cmd").attr('href')

         if ($('#cmdBoxItems').children().length >= 30) {
             option = Object.assign({ offset: offset }, option)
         }

         ajax(option, data => {
             data = JSON.parse(data)
             if (data.length == 30) {
                 $("#target-Intersection-cmd").removeClass('d-none')
                 offset = parseInt(offset) + 30;
                 $("#target-Intersection-cmd").attr('href', offset)
             } else {
                 $("#target-Intersection-cmd").addClass('d-none')
                 $("#target-Intersection-cmd").attr('href', '30')
             }

             for (let i = 0; i < data.length; i++) {

                 let cmd = data[i];


                 $('<div class="cmd-items" id="cmd-' + cmd.id + '" >').html('<div class="w-50"> <p>ID:' + cmd.id + '</p> <p>Client:' + data.nom + ' ' + $('<div class="cmd-items" id="cmd-' + cmd.id + '" >').html('<div class="w-50"> <p>ID:' + cmd.id + '</p> <p>Client:' + cmd.nom + ' ' + cmd.prenom + '</p> ' + statustext(cmd.statut) + '  <p class="font-weight-bolder"> ' + cmd.type + '</p><p>  ' + cmd.description + '</p> </div> <div   class="w-25 fm-vertical" id="cmd-btn-' + cmd.id + '" > <button class="btn btn-success">Accepter</button><button cLass="btn btn-danger">Refuser</button><button class=" btn btn-secondary"> Supprimer</button></div>').appendTo('#cmdBoxItems')
                     .prenom + '</p> ' + statustext(cmd.statut) + '  <p class="font-weight-bolder"> ' + cmd.type + '</p><p>  ' + cmd.description + '</p> </div> <div   class="w-25 fm-vertical" id="cmd-btn-' + cmd.id + '" > <button class="btn btn-success">Accepter</button><button cLass="btn btn-danger">Refuser</button><button class=" btn btn-secondary"> Supprimer</button></div>').appendTo('#cmdBoxItems')

             }
         })
     }

     let SEtCmdPanels = e => {
         e.preventDefault()
         let $this = $(e.target)
         let idTarget = parseInt($this.parent().attr('id').replace('cmd-btn-', ''))
         let action = $this.text().replace(' ', '')
         ajax({
             form: "SetConfigCommande",
             idTarget: idTarget,
             action: action
         }, data => {


             let statutText = $this.parent().siblings().children('.stat')

             if (data == "Supprimer") {
                 $this.parent().parent().remove()
                 return true;
             } else if (parseInt(data) == false || parseInt(data) == true) {


                 statutText.html(statustext(parseInt(data)))
             }
         })
     }

     //-- Chargement Cmd
     let observerCmd = new IntersectionObserver(getCmdPanel, options);
     let targetcmd = document.querySelector('#target-Intersection-cmd');
     observerCmd.observe(targetcmd);


     //-- q_cmd
     $("#q_cmd").change(() => {
         $('.cmd-items').remove()
         getCmdPanel()
         setInterval(() => {
             $('.cmd-items div button').click(e => {
                 SEtCmdPanels(e)
             })
         }, 1000);

     });

     setTimeout(() => {

         //-------  Modifer Les POST


         //-- Delete 

         $('a[href="#DeleteObj"]').click(e => {
             e.preventDefault()
             e.stopPropagation()

             var id = $(e.target).attr("id")

             if (id.includes('DeletCtg')) {
                 var $target = 'categorie'
             } else if (id.includes('DeletPost')) {
                 var $target = 'post'
             }
             id = parseInt(id.replace('DeletPost-', '').replace('DeletCtg-', ''))


             $.post("server/admin/index.php", {
                     operation: 'SetDelete',
                     target: $target,
                     idTarget: id
                 },
                 (data) => {

                     if (data.includes("success")) {
                         $(e.target).parent().parent().remove()
                     }
                 },
                 "text"
             );
         })

         //--- Modify Categ

         $(' a[href="#EditCategorie"]').click(e => {


             $('#fm-SetCateg').remove()
             e.stopPropagation()
             e.preventDefault()
             var id = $(e.target).attr('id')

             id = parseInt(id.replace('EditCtg-', ''))

             let name = $(e.target).parent().siblings('a').text()
             let slug = $(e.target).siblings('p').text()
             slug = slug.replace('SLUG:', '')

             $('#fm-AddCateg').clone().appendTo($(e.target).parent().parent())

             let newForm = $('li #fm-AddCateg')
             newForm.attr('id', 'fm-SetCateg')

             $('li #fm-SetCateg input[name="name"] ').val(name)
             $('li #fm-SetCateg input[name="slug"] ').val(slug)
             $('li #fm-SetCateg button').attr("id", '#' + id)
             $('li #fm-SetCateg button').text('Modier')
             $('#MessageEroorCateg').html('')


             $('#fm-SetCateg').submit(e => {
                 SetFmCategorie(e)

             })

         })

         //--- dispo 
         $('a[href="#EditPost"] ').click(e => {
             e.stopPropagation()
             e.preventDefault()

             let actualStatut = ($(e.target).text()).replace(' ', '')
             let idPost = parseInt(($(e.target).attr('id')).replace('EditPost-', ''))

             if (actualStatut.includes('Disponible')) {
                 var req = 0
                 var futurStatut = 'Vendu'
             } else if (actualStatut.includes('Vendu')) {
                 var req = 1
                 var futurStatut = 'Disponible'
             }


             ajax({
                 req: req,
                 form: 'fm-diponibility',
                 idPost: idPost
             }, data => {


                 if (data.includes('success')) {

                     let $this = $(e.target).attr('id')
                     $('#' + $this + ' , #PPD-' + idPost).text(futurStatut)
                 }
             })

         })

         //--  set Users

         $('span.useroptionbtns a').click(e => {

             SetUsersPanels(e)
         })
         $('.addiliatConfigBtn button').click(e => {
             let id = $(e.target).attr('id')
             let UserTarget = id.split('-')[2]
             let action = id.split('-')[0]

             ajax({
                 form: 'fm-affiliation-config',
                 action: action,
                 UserTarget: UserTarget,
                 fileName: id.split('-')[1] + '-' + UserTarget
             }, (data) => {


                 if (data.includes('success')) {
                     $(e.target).parent().siblings('p').text('Checked-' + UserTarget)
                     $(e.target).parent().html('<button id="suppr-' + dir + '"  class="my-1 btn-lg btn-secondary">Supprimer</button>')
                 }
                 if (data.includes('delete')) {
                     $(e.target).parent().parent().remove()
                 }
             })

         })

         //--- Config Commande Panel
         $('.cmd-items div button').click(e => {
             SEtCmdPanels(e)
         })

     }, 2000);

     /// -- genereate image 
     ajax({ form: 'getImg' },
         data => {
             data = JSON.parse(data);

             for (let i = 0; i < data.length; i++) {
                 let img = data[i];

                 let box = $("<div>").html(' <span class = "spanForImg" > <img width ="100px" height ="auto" src = "' + img + '" alt = "img" > </span> <br> <input type="text" value="' + img + '" >').addClass('imgGalerieBox')
                 box.appendTo($("section#galerie"))

             }
         }
     );

     //---  ** EMAILS

     // html 



     //--- SEND Email ---
     $("#sendNws form").submit(e => {
         e.preventDefault()
         let elemnts = $(e.target).children('div').children('select , input , textarea')
         let option = { form: 'send_newsletter' }
         $(elemnts).each((index, element) => {
             let i = $(element).attr('id')
             if ($(element).prop("type") == 'checkbox' && $(element).prop("checked") == true) {
                 var obj = {
                     [i]: true
                 };
             } else if ($(element).prop("type") != 'checkbox' && $(element).val() != '') {
                 var obj = {
                     [i]: $(element).val()
                 }
             } else {
                 var obj = {};
             }
             option = Object.assign(obj, option)
         });

         ajax(option, data => {
             $('#result_message').remove()
             let result_message = $('<span id="result_message" class="w-100 alert my-4 py-4 text-center" >').html(data)
             if (data.includes('success')) {
                 result_message.addClass('text-success alert-success')
             } else {
                 result_message.addClass('text-danger alert-danger')
             }
             result_message.appendTo($(e.target))
             setTimeout(() => {
                 result_message.remove();
             }, 60000);
         })
     })

     /// --Historique Des Newsletter

     let escapeHtml = (text) => {
         var map = {
             '&': '&amp;',
             '<': '&lt;',
             '>': '&gt;',
             '"': '&quot;',
             "'": '&#039;'
         };

         return text.replace(/[&<>"']/g, function(m) { return map[m]; });
     }

     /// --Historique Des Newsletter
     let get_newsletter = () => {

         let option = {
             form: 'fm-GetNewletter'
         }

         $("#target-Intersection-cmd").removeClass('d-none')
         let offset = $("#target-Intersection-nwsltr").attr('href')

         if ($('#Nwletter_Content').children().length >= 30) {
             option = Object.assign({ offset: offset }, option)
         }


         ajax(option, data => {
             data = JSON.parse(data)

             if (data.length == 30) {
                 $("#target-Intersection-nwsltr").removeClass('d-none')
                 offset = parseInt(offset) + 30;
                 $("#target-Intersection-nwsltr").attr('href', offset)
             } else {
                 $("#target-Intersection-nwsltr").addClass('d-none')
                 $("#target-Intersection-nwsltr").attr('href', '30')
             }

             for (let i = 0; i < data.length; i++) {
                 let info = data[i];
                 let email = JSON.parse(info.email_info)
                 console.log('email:', email)

                 let item = $("<div>").html('<p>ID :' +
                     info.id + ' </p> <ul> ENvoyer à : <li> non Validés : ' + email.Email_non_validés + ' </li><li>Utilisateurs Simples :' + email.email_users + '</li><li>Affiliés :' + email.email_affiliés + '</li> <li>Autres :' + email.autre_email + ' </li> </ul> <p>Envoyer Au Utilisateurs inscrit Apràs le :' + time(parseInt(email.heur_envoie)) + ' </p><p>Envoyer le :' + email.heur_envoie + ' </p> <p class="for_code"> ' + email.objet_emil + '</p>')
                 $('<div class="for_code">').text(email.corp_email).appendTo(item)
                 item.appendTo('#Nwletter_Content')

             }
         })

     }
     let observernewsletter = new IntersectionObserver(get_newsletter, options);
     let targetnws = document.querySelector('#target-Intersection-nwsltr');
     observernewsletter.observe(targetnws);

     $("#fm-terminal").submit(e => {
         e.preventDefault()
         if (($('#terminal_Promp').val()).includes('--clear')) {
             $('#result_terminal').text(' ')
             return true
         }
         ajax({
             form: "fm-terminal",
             sql: $('#terminal_Promp').val()
         }, data => {
             $('<span class="text-info">').text('=> ' + $('#terminal_Promp').val()).appendTo($('#result_terminal'))
             if (!data.includes('Array')) {
                 $('<span class="text-danger">').text('Erreur De Syntaxe!').appendTo($('#result_terminal'))
                 $('<hr class="border-bottom">').appendTo($('#result_terminal'))
                 return false
             }
             $('<span>').text(data).appendTo($('#result_terminal'))
             $('<hr class="border-bottom">').appendTo($('#result_terminal'))
         })
     })
 })