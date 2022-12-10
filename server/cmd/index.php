<?php 
/* server Sign up
*/

//--- session 
if (session_status()!=2) {
	session_start();
	session_regenerate_id(true);
} 
if(!isset($_SESSION))
{
    die();
} 

@$_id_user = @$_SESSION['id']  ;
@$_user_pass = @$_SESSION['password'] ;
define('ROOTPATH', dirname(dirname( __DIR__ )).DIRECTORY_SEPARATOR);

require(ROOTPATH.'elements'.DIRECTORY_SEPARATOR.'db_config.php');
$pdo = new PDO($db_DSN , $db_USER , $db_PASS ) ;


//---   Permition 

$secureCheck = $pdo->prepare(" SELECT id FROM user WHERE id = ? and is_affiliat >= 0 and password = ? ");
$secureCheck->execute(array($_SESSION['id'] ,$_SESSION['password'] ));
$resultSecureCheckID = $secureCheck->fetch(PDO::FETCH_ASSOC)['id'] ;
if($resultSecureCheckID !=  $_SESSION['id']){
    die();
}


// form s'affilier  
if(  !empty($_FILES)  )
 {
     require(ROOTPATH.'Class' .DIRECTORY_SEPARATOR . 'BeAff.php') ;
     $Beaff= new BeAff($_id_user , $_FILES);  
    if( $Beaff->result() ) :
        $dossier = 'user-'. $_id_user ;
    $pdo->query("INSERT INTO comende  (`client`,  `type`, `description` )VALUES ( $_id_user , 'Affiliation' , '$dossier') ");
    die('success') ; 
    endif ;

}

function verify_mdp($pass,$hashed)
{
   $pass =  hash('sha256' ,$pass);
   if($pass == $hashed)
   {
       return true ;
   }
   else
   {
       return false ;
   }
}
 

if (!empty(@$_POST['form']) && in_array(@$_POST['form'],["fm-nom","fm-email","fm-tel","fm-pass","fm-adress","DoCommande_form"]  ))
{ 
    require(ROOTPATH.'class'.DIRECTORY_SEPARATOR.'ModifyUser_DoCmd.php');
    $ModifyUser_DoCmd = new ModifyUser_DoCmd(@$_id_user, @$_user_pass ) ;


    //--- Change name 

   if ( $_POST['form'] == "fm-nom"  && !empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['pass-nom']) ) {
    		if (!verify_mdp($_POST['pass-nom'] , $_user_pass)) {
				die( 'mot de passse incorrect');
			}
    		if (strlen($_POST['prenom']) < 3   ) {
				die( 'Prenom trop court ');
			}
			if ( strlen($_POST['nom']) < 3  ) {
                die( 'Nom trop court ');
            }
            if ($_SESSION['prenom'] == $_POST['prenom']  && $_SESSION['nom'] == $_POST['nom']  ) {
                die(' Ces Coordonées Sont Déjà Present ');
            }
    $result = $ModifyUser_DoCmd->fm_nom( htmlentities($_POST['prenom']) , htmlentities($_POST['nom']));
    if( !empty($result['id']) )
    {
        
        $_SESSION = $result ;
        die( 'success !') ;
    }else 
    {
        die('erreur de requete') ;
    }
   }

   //--- add email && SEND Email 
   
    if ( $_POST['form'] == "fm-email"  && !empty($_POST['email1']) && !empty($_POST['email2']) && !empty($_POST['pass-email'] ) )
    {
        $pass =  hash( 'sha256' ,$_POST['pass-email']) ;
        $pass2 = hash('sha256', $pass);
        
         $request = $pdo->prepare("SELECT COUNT(*)from user WHERE email = ? ");
         $request->execute(array($_GET['email']) );
         $exist = $request->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] ;
         
          if ($exist) {
              die('CET Email est deja Utiliser');
          }

        
        if ( $pass != $_user_pass  ) {
            die('Mot de passe Incorrect ') ;
        }
        if ( $_POST['email1'] != $_POST['email2']  ) {
            die(' Email incompatible ') ;
        }
        if ( $_POST['email1'] != $_SESSION['email']  ) {
            die(' Cet email est déjà Utilisé ') ;
        }

        require(ROOTPATH.'Class'.DIRECTORY_SEPARATOR.'SendMail.php');
        $email = htmlentities($_POST['email1']) ;
        
$time = time() ;
$message = <<<HTML

<html><body  align='center'> <h1> Vérifiez Votre E - mail  </h1><br>                 <a href="127.0.0.1/server/cmd/index.php?req=changeMail&email={$email}&t={$time}&token={$pass2}" >  Cliquez ici </a> <br> <br><p> En cas de non confirmation , Cet email de Confirmation sera obsolet dans 15  minutes </p></body></html>

HTML;


        $sendMail = new SendMail( $email , 'Confirmez Votre Email' , $message ); 

        die('success ! Un email de Confirmation Voys a été envoyé à ' . $email );

    }

    //--add phone number 
    
    
    
    if (!empty($_POST['tel']) && $_POST['form'] == "fm-tel"  ) {
        
        
        if ( strlen($_POST['tel'] < 10 ) ) {
            die(' Un Numéro De Téléphone Contiens au moin 10 Chiffre');
        }
        
        if ( $_SESSION['tel'] == $_POST['tel']  ) {
            die('Ce numero Est déja Enregistré ');
        }
        $result = $ModifyUser_DoCmd->fm_Tel_Pass_Adrr( 'tel' , htmlentities($_POST['tel']));
        if( !empty($result['id']) )
        {
            $_SESSION=$result ;
            die('success ! Ce Numéro a bien étée Ajouter');
        }
    }

    //-adress
    
    
    if (!empty($_POST['adress']) && $_POST['form'] =="fm-adress" ) {
        
        
        
        if ( $_SESSION['adress'] == $_POST['adress']  ) {
            die('Cet Adresse Est déja Enregistré ');
        }
        $result = $ModifyUser_DoCmd->fm_Tel_Pass_Adrr( 'adress' , htmlentities($_POST['adress']));
        if( !empty($result['id']) )
        {
            $_SESSION=$result ;
            die('success ! Cet Adresse a bien étée Ajouter');
        }
    }


        //-- Mot de passe
        
    if (!empty($_POST['nmdp1']) && !empty($_POST['nmdp2']) && !empty($_POST['pass-pass']) && $_POST['form'] =="fm-pass")
    {
        
        if ( hash('sha256' ,$_POST['pass-pass']) == $_user_pass) {
            die(' Anciens Mot de passe incorrect');
        }
        if ($_POST['nmdp1'] != $_POST['nmdp2']) {
            die('Mot de passe Incompatible');
        }
        if ( strlen($_POST['nmdp1'] ) <8  ) {
            die('Mot de passe trop Court (min 8 Caractères)');
        }
        $pass = hash('sha256', htmlentities($_POST['nmdp1'])  ) ;

        if ( $_user_pass == $pass) {
            die('Ce mot de passe est Déjà utiliser ');
        }
        $result = $ModifyUser_DoCmd->fm_Tel_Pass_Adrr( 'password' ,$pass  );
        if( !empty($result['id']) )
        {
            $_SESSION=$result ;
            header('Location:LOGOUT');
        }
    }

    
    //-- ADD commande 
    
    if (!empty($_POST['type'])  && !empty($_POST['desct']) && $_POST['form'] =="DoCommande_form")
    { 
        $request = $pdo->prepare("INSERT INTO comende  (`client`,  `type`, `description` ) VALUES ( ? , ? , ? ) ");
         $request->execute(array($_id_user , htmlentities($_POST['type']) , htmlentities($_POST['desct']) )); 
         $request = $pdo->prepare(" SELECT COUNT(*) from comende WHERE client = ? and type = ? and description = ? ");
         $request->execute(array($_id_user , htmlentities($_POST['type']) , htmlentities($_POST['desct']) )); 
         $result = $request->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] ;
         
         
         if ($result) {
             if (!empty(@$_POST['btnid'])) {
         $request = $pdo->prepare(" DELETE from comende WHERE id = ? and  statut = -2 AND client =? ");
         $request->execute(array(@$_POST['btnid'] , $_id_user )); 
             }
             die("success ! Votre Requete a bien été reçu , Nous examinerons votre demande Sous peut <a href='http://127.0.0.1/cmd' class='btn btn-primary' >+ Nouvelle Commande</a> ");
         }else
         {
             die('Element(s) Manquant');
         }


    }
}

//-- Drop & edit Cmd
if ( @$_POST['req'] =='DeletCmd' && !empty($_POST['CMDid']) ) {
    $request = $pdo->prepare('DELETE FROM comende WHERE id = ? AND statut = -2 AND client =? ');
    $request->execute(array($_POST['CMDid'] , $_id_user));
    die('success');
}




//-* GET

//--confirm EMail - GET

if(  isset( $_GET['email'] , $_GET['req'] ,$_GET['t']) && $_GET['req']=='confirmsub' )
{
     if((  time() - @$_GET['t']) < 900 )
     {
         
        $request = $pdo->prepare("UPDATE user SET is_affiliat  = 0 WHERE email = ? AND is_affiliat = -2 ");
        $request->execute(array($_GET['email']  )); 

        if(  !empty($_user_pass) )
        {
            
        $request = $pdo->prepare("SELECT * from  user WHERE email = ? and password = ? and is_affiliat >= 0 ");
        $request->execute(array($_GET['email'], $_user_pass));
        $result = $request->fetch(PDO::FETCH_ASSOC);
        
        if ( !empty($result['id'])&& $result['is_affiliat'] >= 0  ) {
            $_SESSION = $result ;
        }
    }
    header('Location:http://127.0.0.1');
    }else
     {
         die('URL EXPIRED');
     }
 }
 

 //-- change email -GET

if(  isset( $_GET['email'] , $_GET['req'] ,$_GET['t'] , $_GET['token'] ) && $_GET['req']=='changeMail' )
{
    
         $request = $pdo->prepare("SELECT COUNT(*)from user WHERE email = ? ");
         $request->execute(array($_GET['email']) );
         $exist = $request->fetch(PDO::FETCH_ASSOC)['COUNT(*)']  ;
         
          
          if ($exist) {
              die('CET Email est deja Utiliser');
          }
    if( $_GET['token'] != hash('sha256', $_user_pass )  )
    {
         die('Pour votre securitée Ouvrez le lien dans le meme Navigateur où vous êtes connectez à Hera Shopping');
    }
    if( (time() - @$_GET['t']) <= 900 )
     {
         
         $request = $pdo->prepare("UPDATE user SET email  = ? WHERE id = ? and password = ? ");
         $request->execute(array($_GET['email'] , $_id_user  , $_user_pass )); 
         $request = $pdo->prepare(" SELECT * from user WHERE email = ? and password = ? ");
         $request->execute(array($_GET['email'] , $_user_pass )); 
        $result = $request->fetch(PDO::FETCH_ASSOC);
        
        if ( !empty($result['id'])) {
            $_SESSION = $result ;
            
        }
        header('Location:http://127.0.0.1');
     
    }else
    {
        die('URL EXPIRED');
     }
}

