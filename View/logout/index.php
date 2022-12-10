<?php 

session_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name()," ",0 , null , null ,false , true );
session_regenerate_id(true);
if (empty($_SESSION)) 
{ 
setcookie("ooioo", "", time(),null,null,false,true ); 
setcookie("oiiooiiioo", "", time(),null,null,false,true ); 
	header('location:LOGIN');
	exit();
}