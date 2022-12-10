<?php 

if (!isset($_SESSION['is_admin']) ) {
    header("location:home");
}

$secureCheck = $pdo->prepare(" SELECT id FROM user WHERE id = ? and is_admin =1 and is_affiliat >= 0 and password = ?  ");
$secureCheck->execute(array($_SESSION['id'] ,$_SESSION['password'] ));
$resultSecureCheckID = $secureCheck->fetch(PDO::FETCH_ASSOC)['id'] ;
if($resultSecureCheckID !=  $_SESSION['id']){
    header("location:LOGOUT");
}

?>

<link rel="stylesheet" href="elements/css/admin.css">

    <nav class="mt-1 nav-admin">
<a href="#prodiuits">Produits</a>
<a href="#users">Utilisateurs</a>
<a href="#nws"> Newsletter</a>
<a href="#db"> Terminal </a>
</nav> 

<h1 align='center' class="my-4" > Espace Administrateur </h1>


<main id="prodiuits">
    <nav class="mt-1 nav-admin">
        <a href="#postAdd">Ajouter</a> 
        <a href="#postGestion">Modifer </a>
        <a href="#ctgs"> Catégories </a>
        <a href="#galerie"> Galerie </a>
    </nav> 
    <section id="postAdd" align='center' > 
      <h2 class="my-4">Ajouter un Produit</h2> 
    <form class=" fm-vertical w-50 container"  name="CreatePost" method="post" enctype="multipart/form-data" id ='fm-addPost'>
    
    <p> Chargez Une ou Plusieur images (facultatif)</p>
        <input type="file" name='photo[0]'  value="">
        <input type="file" name='photo[1]'  value=""> 
        <input type="file" name='photo[2]'  value="">
        <input type="file" name='photo[3]'  value=""> 
        <input type="file" name='photo[4]'  value=""> 
        <input type="file" name='photo[5]'  value="">
        <input type="file" name='photo[6]'  value=""> 
        <br>
        <p>Saisissez Les Infos Relative au Produit</p>
        <label for="titlePostAdd"> Nom Du Produit </label><input type="text" name="name" id="titlePostAdd" required >
        <label for="PostDescAdd">Description </label><textarea name="description" id=PostDescAdd"" cols="2" rows="10" placeholder="l'integration HTML est Autorisée !  "></textarea>
        <label for="SlugPostAdd">Slug </label><input type="text" name="slug" id="SlugPostAdd" required placeholder="">
        <label for="PostPriceAdd">Prix</label><input type="number" name='prix' id='PostPriceAdd' required >
        

             <label for="monnais" > Unitée </label> <select name="monnais" id="monnais"  required >
                  
        <option value="DZD">DZD </option>
        <option value="point">Points</option>
    </select> </label>  
             <label for="CategPostAdd"> Catégorie(s) </label><input type="text" name="categories" id="CategPostAdd" placeholder="Séparrer pa un '- " >
    <button class=" mt-3 btn btn-primary"> Publier  </button>
    <div class="my-4"  id="AddPostErrorMsg"></div>
    </form>
    </section>
    

    <section class="mt-4" id="postGestion">
     
    <div id="main-home"  >
   
    <div id="home-produits">
    <p class="spinner-border"> 
    </p> 
    </div>
    </div>
    <div class="parentmore"> 
        <div class="more">
            <a id="pv" class='btn btn-outline-secondary  rounded-circle  '  href="#1"> <  </a> <a class='btn btn-outline-secondary  rounded-circle ' id="nx" href="#31" >  > </a>
        </div>
    </div> 
</section>


<section id="ctgs">
<div id="home-categories">
    <p class="spinner-border"> 
    </p> 
    </div>
    <form action="" method="Post" id="fm-AddCateg">
        <input type="text" name="slug" required placeholder="Slug"   >
        <input type="text" name="name" required placeholder="name"    >
        <button class="btn btn-primary" id='idCateg' > Ajouter </button>
        <span class="my-4" id='MessageEroorCateg'></span>
    </form>
    
</section>

<section id="galerie"> 
</section>

</main>

<main id="users">
    <nav class="mt-1 nav-admin">
        <a href="#userGestion">Utilisateurs </a>
        <a href="#approbation">Approbation</a> 
        <a href="#userCmd"> Commandes </a>
    </nav> 
    
    <section id="userGestion"  class="text-right w-100" >
        <input class=' my-4 text-left' type="text" name="q" id="q_user">
        <div id="UserBoxItems"></div>

        <p class="w-100 text-center" id="target-Intersection-Users" href='30'>
        <span class="spinner-border"></span>
         </p> 
    </section> 

    <section id="approbation"  class=" " >
       
    </section>
    <section id="userCmd" class="my-4 w-100" >
        <div class="text-right">
            <input type="text"  id="q_cmd" >
        </div>
        <div id="cmdBoxItems"></div>
        <p class="w-100 text-center" id="target-Intersection-cmd" href='30'>
        <span class="spinner-border"></span>
         </p>
       </section> 

    
</main>

<main id="nws">
    <nav class="mt-1 nav-admin">
        <a href="#sendNws">Envoyer</a> 
        <a href="#HistoriqueNws"> Historique </a>
</nav> 
<section id="sendNws" >
    <form action="" method="post" id="fm-Send-email">
<div id="filterEmailSend" class="form-check">
<p>Filtrez Les Destinataires :</p>
<br>

   <input type="checkbox" class="form-check-input" id="Email_non_validés" checked="">
   <label class="form-check-label" for="Email_non_validés">Utilisateurs Non Validés</label>
<br>

   <input type="checkbox" class="form-check-input fm-vertical" id="email_users" checked="">
   <label class="form-check-label" for="email_users">Utilisateurs Non Affiliés</label>
<br>
   <input type="checkbox" class="form-check-input" id="email_affiliés" checked="">
   <label class="form-check-label" for="email_affiliés">Affiliés</label>
<br> 
<select id='inscrit_le' name="filterbydate" class="w-50 my-4">
    <option value="0">Inscrits Depuis...</option>
    <option value="<?= time()-(3600*24) ; ?>">Aujourd'hui</option>
    <option value="<?= time()-(3600*24*7) ; ?>">Cette Semaine</option>
    <option value="<?= time()-(3600*24*7*30) ; ?>">Ce Mois</option>
    <option value="<?= time()-(3600*24*7*30*365) ; ?>">Cette Année</option>
</select>
<br>
<textarea id='autre_email' class="my-4" name="spetified_email" cols="30" rows="5" placeholder="Spécifiez D'autres Adresses, Séparez Chaque Email Par un ' / ' "></textarea>
</div>
<div id="sObj-Message-Email-Send" class="fm-vertical">
    <input type="text" id="objet_emil" placeholder="Objet" require='' >
    <textarea id="corp_email" cols="30" rows="13" placeholder=" text de la news letter ( l'integration HTML:5 est Autorisé ) " require='' ></textarea>

</div>
<button class="btn btn-lg btn-outline-primary">Envoyer</button>
</form>
</section>
<section id="HistoriqueNws">
<div id="Nwletter_Content"></div>

<p class="w-100 text-center" id="target-Intersection-nwsltr" href='30'>
        <span class="spinner-border"></span>
         </p>
</section>


</main>

<main id="db">
    <pre class="for_code" id="result_terminal">
        <span class="text-warning">
            Bienvenue Sur Le Terminal De Base de Données...
            Cet interface est Déstiner Uniquement aux Dévloppeur.
            Certaines Actions Sont Irréversibles !
            Assurez-vous bien de Vos Commandes Avant Toute Manipulation .
                =================================================
                # Inseeez Vos Commande ci-dessous 
                # le Language Utiliser est MySQL 
                # Tapez '--clear' pour effacer l'interface
        </span>
                <hr class="border-bottom">
    </pre>
    <form action=""  method="post" class="py-4" id="fm-terminal" >
        <textarea id="terminal_Promp"  rows="3" placeholder=">_"></textarea>
        <button class="w-100 btn btn-lg btn-outline-info">></button>
    </form>

    </main>

    

    <script  src="elements/js/jquery.js"></script> 
    <script type="text/javascript" src="elements/js/home.js"></script> 
    <script async src="elements/js/admin.js"></script> 
 
 
