<?php

if(isset( $_POST['adress'] ,$_POST['obj'] , $_POST['message']))
{
$header="MIME-Version: 1.0\r\n";
$header.='From:"PrimFX.com"<support@primfx.com>'."\n";
$header.='Content-Type:text/html; charset="uft-8"'."\n";
$header.='Content-Transfer-Encoding: 8bit';






if(mail( $_POST['adress'] ,$_POST['obj'] , $_POST['message'],  $header))
{ 
    echo "Email successfully sent " ;
} else {
    echo "Email sending failed...";
}
}  
