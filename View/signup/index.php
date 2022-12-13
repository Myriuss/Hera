<?php 

/* View  Sign up
*/

if (!empty($_SESSION)) {
	header('location:home');
}

?>
<div class="flexsignup">

<div class="img">
  <h1 class=" mb-4 font-weight-light "> Inscrivez Vous </h1>
<img src="View/Image/photo-1462206092226-f46025ffe607.jpg"  alt='image' >
<img src="View/Image/photo-1529333166437-7750a6dd5a70.jpg" alt='image' >
</div>

<form  method="post" >
	<p>Saisissez les champs suivant : </p>
  <label for="prenom"> Prénom</label> <input id="prenom" type="text" name="prenom"  required="">  
  <label for="nom"> Nom</label> <input id="nom" type="text" name="nom"  required="">  
  
  <label for="tel"> Téléphone </label> <input id="tel" type="tel" name="tel" placeholder="(facultatif) "  >  
  <label for="email"> Email </label> <input id="email" type="email" name="email"  required="">  
  <label for="pass"> Mot De Passe </label> <input id="pass" type="password" name="pass"  required >  
  <label for="pass2"> Confirmez Votre Mot de Passe </label> <input id="pass2" type="password" name="pass"  required >  
  
  <label for="conditions"  > <input id="conditions" type="checkbox" name="conditions" required > j'accepte et j'ai connaissance <a href="#condition" data-toggle="modal" > les conditions et treme dd'utilisations</a></label>  


  <div id="erreur" > </div>

  <button class="btn btn-primary  my-4" > Inscription </button>  


<p class="mt-5">Déjà Inscrit ? <a href='Login' class=" btn btn-success">Connectez Vous</a></p>

</form>
</div>

<! ----- display none ------>

    <aside id="condition" class="bg-color-primary text-color-primary modal fade" tabindex="100" data-width="760" >
       <div class="modal-header">
      <button  class=" close text-danger " data-dismiss="modal" aria-hidden="true">X</button>
  </div>
        
<div class="modal-body my-4 "> 
    <p>
    Termes et conditions d' utilisation : 
      <ul>
        <li>Nous ne somme en aucun cas respensable de toutes tentative de de Cyber attaque </li>
        <li> Nous ne somme pas respesable en cas de Phishing , Attaque en MITM ou autre </li>
        <li>nous ne somme pas respensable en cas fuite de vos données où d'information fournie à notre entreprise </li>
        <li>Des attaque judiciàre et aures represaille peuvent etre entamée en cas d'attaque ,essai de corruption de nos servers </li>
        <li> De grandes senctions serons prise en cas de plagiats </li>
      </ul>
    </p>
    </div>
    </aside>
    



<script  src="elements/js/jquery.js"></script>
<script  src="elements/js/sign.js"></script>





