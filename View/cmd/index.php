<?php 

/* cmd */

if (  empty(@$_SESSION['id'] ) ) {
    header('location:logout');
}

//-- check permission

$secureCheck = $pdo->prepare(" SELECT id FROM user WHERE id = ?  and is_affiliat >= 0 and password = ? ");
$secureCheck->execute(array($_SESSION['id'] ,$_SESSION['password'] ));
$resultSecureCheckID = $secureCheck->fetch(PDO::FETCH_ASSOC)['id'] ;
if($resultSecureCheckID !=  $_SESSION['id']){
    header("location:LOGOUT");
}

$__id_user = $_SESSION['id'];

$SeeCmd = $pdo->query("SELECT  * FROM comende  WHERE client = $__id_user AND type !='Affiliation' ORDER BY id DESC");
$SeeCmd = $SeeCmd->fetchAll(PDO::FETCH_ASSOC);

?>

<h1 class="text-center">  Espace Client  </h1>
</div>

<main id='main-cmd' >
    <div class="navigation" >
        <?=  (empty($_SESSION['is_affiliat'] ))?("<a class='btn' href='#beAffiliat' > S'affilier </a>"):( '') ?>
        
        <a class="btn" href='#clientInfo' > Editer Mes infos </a>
        <a class="btn" href='#DoCommande' > Commander Un Produit  </a>
        <a  class="btn"href='#historique' > Historique de Commandes</a>
        
    </div>
    <div class='Commande'>
        
    <section id='beAffiliat'  >
    <h2> S'affilier </h2> 
    <form class=" fm-vertical w-50 " name="uploadImages" method="post" enctype="multipart/form-data"  > 
         <p> Importez une Photo de Votre carte D'identité Recto verso En bonne qualitée </p>
       
       <input type="file" name="photo[0]" value="">
       <input type="file" name="photo[1]" value="">
       <input type="file" name="photo[2]" value=""> 
      <br>
<br>
        <p> Importez une Photo / Scan Du formulaire Fournit dans une de nos Agence </p> 
        <input type="file" name="photo[3]" value="">
        <input type="file" name="photo[4]" value=""> 
        <br> 
        <button class="mt-4 btn btn-outline-primary">Envoyer</button>
    </form>
    </section>

    <section id='clientInfo'  class="w-1000 " >
    <h2> Mes infos </h2>
      

          <p> Prenom Nom   <a href="#fm-nom"> <?= $_SESSION['prenom'] ?> <?= $_SESSION['nom'] ?></a> </p> 

          <form class="d-none mt-4 mb-4 fm-vertical" action="" method="post" id="fm-nom">
          <label for="prenom"> Prenom </label> <input type="text" name="prenom" id="prenom" value="<?= $_SESSION['prenom'] ?>" >
             <label for="nom"> Nom </label> <input type="text" name="nom" id="nom" value="<?= $_SESSION['nom'] ?> " >
             <label for="pass-nom"> Mot de passe </label>
             <input type="password" name="pass-nom" id="pass-nom">
             <button  class="btn btn-primary">Modifier</button>
          </form>


          <p>Email <a href="#fm-email"> <?= $_SESSION['email'] ?> </a> </p> 

          <form class="d-none mt-4 mb-4 fm-vertical" action="" method="post" id="fm-email">
          <label for="email1"> Saisissez Un nouvel Email </label> <input type="text" name="email1" id='email1' >
             <label for="email2"> Confirmez le Nouvel email </label> <input type="text" name='email2' id="email2" >
             <label for="pass-email"> Mot de passe </label>
             <input type="password" name="pass-email"  id="pass-email">
             <button  class="btn btn-primary">Modifier</button>
          </form>
          
          <p> telephone  <a href="#fm-tel"> Changer   <?= @$_SESSION['tel'] ?> </a> </p> 

          <form class="d-none mt-4 mb-4 fm-vertical" action="" method="post" id="fm-tel">
          <label for="tel"> Saisissez Un nouveau Numéro  </label> <input type="text" name="tel" id=="tel"  value="<?= @$_SESSION['tel'] ?>"" >
          
             <button  class="btn btn-primary">Modifier</button>
          </form>

          <p>Mot de passe  <a href="#fm-pass"> Editer</a> </p> 

          <form class="d-none mt-4 mb-4 fm-vertical" action="" method="post" id="fm-pass">
          <label for="nmdp1"> Saisissez Un nouveau Mot de passe  </label> <input type="password" name="nmdp1" id="nmdp1">
             <label for="nmdp2"> Confirmez le Nouveau Mot de passe </label> <input type="password" name="nmdp2" id="nmdp2">
             <label for="pass-pass"> Ancien Mot de passe </label>
             <input type="password" name="pass-pass" id="pass-pass">
             <a class="my-4" href="forgetpassword" >Mot De Passe Oublié? </a>
             <button  class="btn btn-primary">Modifier</button>
          </form>

          <p> Adresse  <a href="#fm-adress"> Changer  <?= @$_SESSION['adress'] ?> </a> </p> 
        
          <form class="d-none mt-4 mb-4 fm-vertical" action="" method="post" id="fm-adress">
          <label for="adress"> Saisissez Votre adresse   </label> 
          <textarea name="adress" id="adress"  cols="10" rows="10" value="<?= @$_SESSION['adress'] ?>"  ></textarea>
              
             <button  class="btn btn-primary">Modifier</button>
          </form>
    </section>

    <section id='DoCommande'>
        <h2> Faire Une Commande  </h2>
        <form action="" method="post" id='DoCommande_form'>  
            <label for="type"> Type de Commande</label> <input type="text" name="type" id="type" placeholder="ex: Ordinateur" requireed  > 
            
            <label for="descr">Description  </label><textarea name="desct" id="descr" cols="60" rows="10" placeholder="ex: marque xxxxx" requireed></textarea>
            <button  class="mt-4 btn btn-outline-primary"> Envoyer </button>

        </form>
    </section>
    <section id='historique'   >
<h2>Historique Des Commandes </h2>

<?php foreach($SeeCmd as $key   ): ?>
<div  class="elemnt-Content my-4">
  <?php  if($key['statut'] == -2 ): ?>
    <div class="text-right">
        <a href='#' id='DeletCmd-<?= $key["id"] ?>' class="btn text-danger text-right ">     <svg class="bi bi-trash-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
</svg> </a>
        <a  href='#'id='EditCmd-<?= $key["id"] ?>' class="btn text-primary text-right "> <svg class="bi bi-pen" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M5.707 13.707a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391L10.086 2.5a2 2 0 0 1 2.828 0l.586.586a2 2 0 0 1 0 2.828l-7.793 7.793zM3 11l7.793-7.793a1 1 0 0 1 1.414 0l.586.586a1 1 0 0 1 0 1.414L5 13l-3 1 1-3z"/>
  <path fill-rule="evenodd" d="M9.854 2.56a.5.5 0 0 0-.708 0L5.854 5.855a.5.5 0 0 1-.708-.708L8.44 1.854a1.5 1.5 0 0 1 2.122 0l.293.292a.5.5 0 0 1-.707.708l-.293-.293z"/>
  <path d="M13.293 1.207a1 1 0 0 1 1.414 0l.03.03a1 1 0 0 1 .03 1.383L13.5 4 12 2.5l1.293-1.293z"/>
</svg> </a>
    </div>
  <?php endif; ?>
    
    <span class='font-weight-bolder mb-1 '> Type : </span>  <p id="type-<?= $key['id'] ?>">  <?= $key['type']?> </p>
    <span class="font-weight-bolder mb-1 " > Description : </span>   <p id="description-<?= $key['id'] ?>"> <?= $key['description']?> </p>
    
    <p class='small font-italic my-4'>Le  <?= date('d-m-Y H:i' ,@$key['c_date']+3600 ) ?> </p>
    
    <?php
    if ($key['statut'] == -2) {
        echo '<p class="text-warning">En Cours D\'évaluation  
        <svg class="bi bi-clock-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
</svg>
        </p>';
    }
    if ($key['statut'] == 0) {
        echo '<p class="text-danger">Vore Commande as été Refusé 
        <svg class="bi bi-x-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.146-3.146a.5.5 0 0 0-.708-.708L8 7.293 4.854 4.146a.5.5 0 1 0-.708.708L7.293 8l-3.147 3.146a.5.5 0 0 0 .708.708L8 8.707l3.146 3.147a.5.5 0 0 0 .708-.708L8.707 8l3.147-3.146z"/>
</svg>
        </p>' ;
    }
        if($key['statut'] == 1)
        {
            
        echo '<p class="text-success">Vore Commande as Bien été Accepté
        <svg class="bi bi-check-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
      </svg>
</svg>
        </p>' ;
        }
        
   
    ?>

</div>
<?php endforeach ; ?>
    
   

    </section>

    </div>
</main>
<div>









<script  src="elements/js/jquery.js"></script> 
<script async src="elements/js/cmd.js"></script> 
 