<?php

if (!empty(@$infoRooter['unset']) ) {
    if (!empty(@$_SESSION['id'])) {
        header('Location:logout');
    }else
    {
        setcookie( "email",'',( time()-1 ),null,null,false,true) ;
    }
}

if( isset($_POST['adress']) || isset($infoRooter['email']) || isset($_SESSION['email']) || isset($_COOKIE['email']))
{
    if(!empty(@$_SESSION['email']))
    {
        $adress = htmlentities((@$_SESSION['email']));
    } elseif(!empty($_COOKIE['email']))
    {
        $adress = htmlentities(@$_COOKIE['email']);
    }
    elseif(!empty(@$infoRooter['email']))
    {
        $adress = htmlentities((@$infoRooter['email']));
    } elseif(!empty($_POST['adress']))
    {
        $adress = htmlentities(($_POST['adress']));

    }
    $req = $pdo->prepare("SELECT email , password FROM user WHERE email= ? AND is_affiliat >= 0");
    $req->execute(array($adress));
    $result = $req->fetch(PDO::FETCH_ASSOC) ;
    

    if( $result['email'] != $adress  )
    {
        $error ='<p class="w-50 text-center my-4 alert alert-danger"> Cet Email n\'est pas enregistrer sur Hera Shopping </p>';
    }
    else{
        
        $error ='<p class="w-50 text-center my-4 alert alert-success">  un Email vous a été envoyer à <span id="sendTo">'.$adress.' </span><br> <a href="forgetpassword"> Toujour Pas Reçu? Renvoyer</a> <br> <a class="text-danger" href="forgetpassword_unset=true">Cet Email n\'est pas le Votre ? </a> </p>';
        setcookie('email', $adress , time()+(900),null,null,false,true );
        
    }
    
    $time = time();
    $hashedPass = hash('sha256',$result['password'] );
$message = <<<HTML
 <html><body  align='center'> <h1> Vérifiez Votre E - mail  </h1><br> 
 <a href='127.0.0.1/forgetpassword_email={$adress}_time={$time}_token={$hashedPass} '>  Cliquez ici </a> <br> <br><p>  le lien ci-dessus sera invalide dans 15 minutes </p></body></html>
HTML;


require(ROOTPATH.'Class'.DIRECTORY_SEPARATOR.'SendMail.php');
    $sendMail = new SendMail( $adress , 'Récuperez Votre Mot De Passe ' , $message ); 
}

if (isset($_POST['pass1'],$_POST['pass2'] ) ) {
   
    
    if ( $_POST['pass1'] != $_POST['pass2']  ) {
        $mdpErr='<p class="text-center  my-4 alert alert-danger"> Mot de passe incompatible</p> ';
    }
    if ( strlen($_POST['pass1']) <8  ) {
        $mdpErr = '<p class="text-center  my-4 alert alert-danger"> Un Mot de Passe doit contenir au moin 8 Caractàre </p> ';
    }
    
if (isset($infoRooter['email'],$infoRooter['token']  ) &&$_POST['pass1'] == $_POST['pass2']  && strlen($_POST["pass1"]) >= 8 ) {
    if ( ($infoRooter['time'] + 900)< time()  ) {
        die('url exipired') ;
    }
    if ($infoRooter['token'] != $hashedPass ) {
         die('error 404 ');
    }
    $req2 = $pdo->prepare("UPDATE user SET password = SHA2(?, 256) WHERE email= ? AND password= ? AND is_affiliat >= 0");
    $req2->execute(array( htmlentities($_POST['pass1']) , htmlentities($infoRooter['email']) , $result['password'] ));
    $req3 = $pdo->prepare("SELECT *  FROM user WHERE email= ? AND password = SHA2(?, 256)  AND is_affiliat >= 0");
    $req3->execute(array(htmlentities($infoRooter['email']),htmlentities($_POST['pass1'])  ));
    $result3 = $req3->fetch(PDO::FETCH_ASSOC) ;
    if (!empty($result3['id'] )) {
        $_SESSION= $result3 ;
        header('Location:home');
    }
    else
    {
        $mdpErr= '<p class="text-center  my-4 alert alert-danger"> Erreur </p>';
    }



}

}

?>

<h1 class='text-center mt-5 mb-5'>  Récuperez Votre Mot De Passe </h1>


<?php if (empty(@$_COOKIE['email']) ) :?>
    <form id='fm-sendMail' action="" class='my-4  text-center   ' method="POST">
        <label for='email'>  Saisissez Votre Email</label> 
        <br>
        <input class='w-50 my-4' type="email" name="adress" id="email">  
        <br>
        <button class='btn btn-primary' > Envoyez Un lien De Récuperation</button>
    </form>
    <?php endif  ; ?>
    <div align="center">
<?= @$error ; ?>
    </div> 
    
    <?php if (isset($infoRooter['email'],$infoRooter['token']) ): ?>
    <form action="" class='my-4  text-center  ' method="POST">
        <label for='pass1'>  Saisissez Un Mot De Passe</label> 
        <br>
        <input class='w-25 my-4' type="password" name="pass1" id="pass1"> 
        <br>
        <label for='pass2'> Confirmez Mot de Passe </label> 
        <br>
        <input class='w-25 my-4' type="password" name="pass2" id="pass2"> 
        <br>
        <?= @$mdpErr ?>
        <br>
        <button class=' my-4 btn btn-primary' > Réinitialiser</button>
    </form>
    <?php endif ; ?>


<script type="text/javascript"  src="elements/js/jquery.js"></script> 






