<?php
/*
   herader

 */

require(ROOTPATH . 'Class' . DIRECTORY_SEPARATOR . 'Menu.php');
$menu = new Menu(@$_SESSION);
$menu = $menu->ToHTML();


if (empty($page_title)) {
	$page_title = "Hera Shoping";
}
 
if(isset($_POST['is_affiliat']) && @$_POST['is_affiliat']== -3 )
{
	header("location:logout");
}


?>
<!DOCTYPE html>
<html lang="fr" class="h-100 ">

<head>
	<meta charset="utf-8">
	<title><?= $page_title; ?> </title>

	<link rel="stylesheet" type="text/css" href="elements/js/slick/slick.css">
	<link rel="stylesheet" type="text/css" href="elements/js/slick/slick-theme.css">
	<link rel="stylesheet" type="text/css" href="elements/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="elements/css/style.css">

	
	<link rel="stylesheet" type="text/css" href="elements/css/bootstrap-modal-bs3patch.css">
	<link rel="stylesheet" type="text/css" href="elements/css/bootstrap-modal.css">

</head> 
<body class="d-flex flex-column w-100    bg-color-primary text-color-primary ">
	<header>

		<div class="   topbar-logo  ">
			<a href="home" class=' logo '> <img src="View/image/logo.png" width="50px" alt="logo"> </a>


			<diV>
				
					<label for="dark"class='inherit-txt' >Mode  <?= (empty(@$_COOKIE['color']) || @$_COOKIE['color']=='light')?("Nuit"):('Claire') ?> </label>
					<span class="radio bg-secondary">
						<input type="radio" name="theme" id="light" value="claire" <?= (empty(@$_COOKIE['color']) || @$_COOKIE['color']=='light')?("Checked"):('') ?> >
						<input type="radio" name="theme" id="dark" value="sombre" <?= ( !empty(@$_COOKIE['color'])&& @$_COOKIE['color']=='dark')?("Checked"):('') ?> >
					</span>
				</div>
			</diV>

		</div>

	</header>
	<nav class=" sticky menu bg-degrad ">
		<?= $menu ?>
	</nav>

    <aside id="logout" class=" py-4 bg-color-primary text-color-primary container modal fade" tabindex="100" data-width="760" >
	<p class="text-center  h-100 "> Volez Vous Vraiment Vous Déconnecter ?</p>
	
	<div class="h-25  modal-footer">
       <a  class=" close text-primary " data-dismiss="modal" aria-hidden="true"> Annuler </a> 
	<a href="logout" class="text-danger" >Déconnecter </a>
    </aside>

<div class="container  mt-4 ">
 