<?php

if(!isset($infoRooter['slug']))
{
    header('location:home');
}
//--- POST
$slug = $infoRooter['slug'];
$post = $pdo->prepare("SELECT * FROM post WHERE slug = ?") ;
$post->execute(array($slug));
$post= $post->fetch(PDO::FETCH_ASSOC);

///---Image 
$imgs = $pdo->prepare("SELECT name FROM image WHERE for_post = ?") ;
$imgs->execute(array($post['id']));
$imgs= $imgs->fetchAll(PDO::FETCH_ASSOC);
for ($i =0; $i < count(($imgs)) ; $i ++) {
    $images[$i] = $imgs[$i]["name"];
}
//--- CATEGORIE 
$categ = $pdo->prepare("SELECT categorie.slug FROM categorie_post LEFT JOIN categorie ON categorie_post.id_categorie = categorie.id WHERE categorie_post.id_post =? ");
$categ->execute(array($post["id"]));
$categ= $categ->fetchAll(PDO::FETCH_ASSOC);
for ($i =0; $i < count(($categ)) ; $i ++){
    $categorie[$i] = $categ[$i]["slug"];
}
?>


<h1 id='m_s'  class="font-weight-light my-4 ">Bijouterie Hera</h1>

 <div class="w-100">
 <?php foreach($images as $image): ?>
<img class="my-1 w-100 rounded" src="<?= $image ?>" alt="image">
<?php endforeach ; ?>
 </div>
 <hr class="border-bottom"> 
 
<h2 align="center" class="font-weight-light my-4"><?= $post['name'] ?> </h2>
<div class="my-2">
    <?=  $post['description']?> 
</div>
<h5 class="my-4"> Prix:  <?= $post['prix'] ?> <sup><?= $post['monnais'] ?> </sup> </h5>

<?=  ($post['disponible'])?"<p class='text-success'>Disponible</p>":"<p class='text-danger'>Vendu</p>" ?>

<p>ID du Produit : <strong><?= $post['id'] ?></strong></p>  
<a class="btn btn-secondary" href="article_<?= $post['slug'] ?>"><svg class="bi bi-link-45deg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
<!--  ici les path-->
</svg> <?= $post['slug'] ?> </a>
<?php if(!empty($categorie)): foreach(@$categorie as $categ ):  ?>
<a class="btn btn-secondary" href="home#<?= @$categ ?>"> <svg class="bi bi-tag-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1H2zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
</svg> <?= @$categ ?> </a>
<?php endforeach ; endif; ?>
<p class="my-4">Ajouté le : <?= date('d-m-Y' ,$post['p_date']) ?> </p>

<div class="text-center w-100">

    <a href=" signup" class="my-4 btn btn-primary btn-lg"> <svg class="bi bi-bag-plus" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
</svg>Commander</a>
</div>

<script  src="elements/js/jquery.js"></script>