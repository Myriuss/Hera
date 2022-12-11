<?php 


// SESSION
if (!empty(@$_SESSION['prenom']  ) ) { 
    $welcome = 'Bienvenu '. $_SESSION['prenom'] . ' ! '; 
    }
     
?>
<h4 class="my-4"> <?= @$welcome ?>  </h4>
</div>

<div class="logo">
            <a href="#"><img src="img/logo.png" alt=""></a>
        </div>

<div class="parentSlider" id="c1"></div>
    <div class="slick d-none ">
        <img src="View/image/image1.1.png" alt ="image2">
        <img src="View/image/image2.2.jpg" alt="image1">
        <img src="View/image/image3.3.jpg" alt="image3">
        <img src="View/image/image4.4.jpg" alt="image4">
    </div>
</div>

<h1  id='m_s' class=" container font-weight-light my-4 ">Hera Shopping</h1> 

<div class="container">
    
<form class='q'  method="get">

<input type="text" name=""q id="q" placeholder="Rechercher" >
 <a class="btn  ">
 <svg class="bi bi-search" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
</svg>
 </a>


</form>
<br>
</div>
<main id="main-home" >

<section id="home-categories">
<p class="spinner-border"></p> 

</section>
<section id="home-produits">
<p class="spinner-border"></p> 
</section>
</main>
<div class="parentmore">
    
    <div class="more  ">
        <a id="pv" class='btn btn-outline-secondary  rounded-circle  '  href="#1"> <  </a> <a class='btn btn-outline-secondary  rounded-circle ' id="nx" href="#31" >  > </a>
    </div>
</div>









<div class='container' > 

<script type="text/javascript"  src="elements/js/jquery.js"></script>
<script async type="text/javascript"  src="elements/js/home.js"></script>
<script src="elements/js/slick/slick.js" type="text/javascript" ></script>
<script async type="text/javascript"  src="elements/js/carou.js"></script>








