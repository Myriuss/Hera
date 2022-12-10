<?php 
/* server Sign up
*/

define("ROOTPATH",   dirname(dirname(__dir__)).DIRECTORY_SEPARATOR );



if (isset($_POST) && !empty($_POST)) {

	require( ROOTPATH .'Class'.DIRECTORY_SEPARATOR.'Register.php');
  $subscrib = new Register(htmlentities(@$_POST['nom']) , htmlentities(@$_POST['prenom']) , htmlentities(@$_POST['email']) , htmlentities(@$_POST['pass']) ,  htmlentities(@$_POST['pass2']) , htmlentities(@$_POST['conditions']) , htmlentities(@$_POST['tel'])  ); 

	if(  $subscrib->isbeenregister() ){
    if (session_status()!=2) {
      session_start();
      session_regenerate_id(true);
    }
    $_SESSION['password'] = hash('sha256' ,htmlentities($_POST['pass'])) ;
   	die('Success') ;
  }	
  elseif(  !$subscrib->isbeenregister() )
  {
    die(json_encode( $subscrib->seeErrors())) ;
  }
  
}



