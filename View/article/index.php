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
  <path d="M4.715 6.542L3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.001 1.001 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
  <path d="M5.712 6.96l.167-.167a1.99 1.99 0 0 1 .896-.518 1.99 1.99 0 0 1 .518-.896l.167-.167A3.004 3.004 0 0 0 6 5.499c-.22.46-.316.963-.288 1.46z"/>
  <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 0 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 0 0-4.243-4.243L6.586 4.672z"/>
  <path d="M10 9.5a2.99 2.99 0 0 0 .288-1.46l-.167.167a1.99 1.99 0 0 1-.896.518 1.99 1.99 0 0 1-.518.896l-.167.167A3.004 3.004 0 0 0 10 9.501z"/>
</svg> <?= $post['slug'] ?> </a>
<?php if(!empty($categorie)): foreach(@$categorie as $categ ):  ?>
<a class="btn btn-secondary" href="home#<?= @$categ ?>"> <svg class="bi bi-tag-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1H2zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
</svg> <?= @$categ ?> </a>
<?php endforeach ; endif; ?>
<p class="my-4">Ajouté le : <?= date('d-m-Y' ,$post['p_date']) ?> </p>

<div class="text-center w-100">

    <a href=" cmd" class="my-4 btn btn-primary btn-lg"> <svg class="bi bi-bag-plus" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
    <path fill-rule="evenodd" d="M14 5H2v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5zM1 4v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4H1z"/>
    <path d="M8 1.5A2.5 2.5 0 0 0 5.5 4h-1a3.5 3.5 0 1 1 7 0h-1A2.5 2.5 0 0 0 8 1.5z"/>
    <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5z"/>
    <path fill-rule="evenodd" d="M7.5 10a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-2z"/>
</svg>Commander</a>
</div>

<script  src="elements/js/jquery.js"></script>