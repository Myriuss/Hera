<?php 
// views Login 


if (!empty($_SESSION)) {
		header('location:home');
	
}


?>

<div class="flexsignup">

<div class="img">
<h1 class="font-weight-light" >Connectez Vous </h1>

<img src="View/Image/photo-1454165804606-c3d57bc86b40.jpg " alt='image' >
</div>


<form   method="post" action="">
	<p>Saisissez votre nom d'utlisateur et mot de passe </p>
	<label for="email"> Email </label> <input id="email" type="text" name="email"  required="">  
  <label for="pass"> Mot De Passe </label> <input id="pass" type="password" name="pass"  require  >  
<br>
<div class="errorMessage"></div>
  <button class="btn btn-primary  my-4" >Se Connecter  </button><br> 

<a href="forgetpassword" class=" my-4"> Vous avez Oublié Votre Mot de passe ? </a>
<p class="mt-5">Toujours pas inscrit ? <a href='signup' class=" btn btn-success">inscrivez vous</a></p>


</form>

</div>


<script  src="elements/js/jquery.js"></script>
<script   src="elements/js/login.js"></script>
