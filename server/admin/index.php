<?php

// --admin
 
//--- session

if (session_status()!=2) {
	session_start();
	session_regenerate_id(true);
}

define('ROOTPATH', dirname(dirname( __DIR__ )).DIRECTORY_SEPARATOR);
require(ROOTPATH.'elements'.DIRECTORY_SEPARATOR.'db_config.php');
$pdo = new PDO($db_DSN , $db_USER , $db_PASS ) ;
function getImage($message)
{
    $a = explode(  "<img" ,$message) ;   

if (count($a) >=1)
{
    for ($i=1; $i < count($a) ; $i++) { 
        $a[$i]  = str_replace(  "'", '"',$a[$i]  ); 
        $a[$i] = explode( 'src="' , $a[$i])[1] ;  
        $a[$i] = explode( '"' , $a[$i])[0] ;  
        $a[$i-1]= $a[$i]; 

    }
    unset($a[count($a)-1]) ;
    return $a ;
}
}


//---  Permition 
if(!isset($_SESSION))
{
    die();
}
$secureCheck = $pdo->prepare(" SELECT id FROM user WHERE id = ? and is_admin =1 and is_affiliat >= 0 and password = ?  ");
$secureCheck->execute(array(@$_SESSION['id'] ,@$_SESSION['password'] ));
$resultSecureCheckID = $secureCheck->fetch(PDO::FETCH_ASSOC)['id'] ;
if($resultSecureCheckID !=  @$_SESSION['id']){
    die();
}
function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    rmdir($dir);
}


//--- Add POST 

if (!empty($_POST['form']) && !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['prix']) && !empty($_POST['monnais']) && !empty($_POST['slug'])) { 
    
    $_POST['slug'] = str_replace( ' ', '' , $_POST['slug'] ) ; 
    $_POST['slug'] = str_replace( "/" , '' , $_POST['slug'] ) ; 
    $_POST['slug'] = str_replace( `\ ` , '' , $_POST['slug'] ) ;
    $_POST['slug'] = str_replace( '^', '' , $_POST['slug'] ) ;
    $_POST['slug'] = str_replace( '_', '' , $_POST['slug'] ) ; 

   $count = $pdo->prepare("SELECT COUNT(*) from post WHERE slug =? ") ;
$count->execute(array($_POST['slug'])); 
$count = $count->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] ;
if($count && empty($_POST['BtnId']) )
{
    die("Le Slug est Déja Utiliser") ;
}
$add = $pdo->prepare("INSERT INTO post  (`slug`,  `name`, `description`,  `prix`, `monnais` ) VALUES ( ? , ? , ? , ? , ? ) ") ;
$add->execute(array( htmlentities( $_POST['slug']) ,htmlentities($_POST['name']),$_POST['description'] ,htmlentities($_POST['prix']),htmlentities($_POST['monnais'] )));

$id = $pdo->prepare("SELECT id from post WHERE slug =? ") ;
$id->execute(array($_POST['slug'])); 
$id = $id->fetch(PDO::FETCH_ASSOC)['id'] ;
if( !empty($id) )
{
    $add = $pdo->query(" DELETE FROM image WHERE name= ' View/image/post/image7.jpeg'  AND for_post=  $id  ") ; 
    if( count(explode(  "<img" ,$_POST['description'])) > 1  ) 
    {
        $getimg = getImage($_POST['description']);
        foreach ($getimg as $key) {
            $add = $pdo->prepare("INSERT INTO image  (`name`,  `for_post`  ) VALUES ( ?,? ) ") ;
            $add->execute(array( $key , $id )); 

        }
    }
    else
    {
        
        $add = $pdo->query("INSERT INTO image  (`name`,  `for_post`  ) VALUES ( 'View/image/post/default.jpeg',$id ) ") ; 
    }
    if( !empty(@$_POST['categories']) ){
        $_POST['categories']  = str_replace(' ','', $_POST['categories']  );
        $categories = explode('-' ,$_POST['categories'] );
        $request = " SELECT id FROM  categorie WHERE id IS NULL " ;
        foreach ($categories as $key ) { 
            $request .= " OR slug = ? " ;
            
        } 
        
        $categ = $pdo->prepare($request) ;
        $categ->execute( $categories  ); 
        $ids = $categ->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($ids as $key ) {
            $pdo->query("INSERT INTO categorie_post  ( `id_post`,`id_categorie`  ) VALUES ( $id ,$key ) ");

        }
    }
    die('success '.$id );
}
}



// Add Pic
if(  !empty($_FILES)  )
 {

        
    if(!empty($_POST['IdPost'] ))
    {
        
    $insertIMg = $pdo->prepare("DELETE  FROM image WHERE  name ='View/image/post/default.jpeg'  AND  for_post = ? ");
    $insertIMg->execute(array($_POST['IdPost'] ) );
}

     require(ROOTPATH.'Class' .DIRECTORY_SEPARATOR . 'AddImg.php') ;
    
     $AddImg= new AddImg(  $_FILES);   
    if( $AddImg->_move ) {

        $IMG = $AddImg->_ImgName;

        // compresser les IMages 
        require(ROOTPATH.'Class' .DIRECTORY_SEPARATOR. 'Zebra_Image.php') ;
        foreach ($IMG as $i => $key) {
        
            $image = new Zebra_Image();
            $image->auto_handle_exif_orientation = false;
            $image->source_path =  $key; 
            $image->target_path = $key ;
            $image->jpeg_quality = 360;
            $image->preserve_aspect_ratio = true;
            $image->enlarge_smaller_images = true;
            $image->preserve_time = true;
            $image->handle_exif_orientation_tag = true;
           $image->resize(360, "", ZEBRA_IMAGE_CROP_CENTER) ;
             
        if(!empty($_POST['IdPost'] ))
        { 
            $key = explode('View'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'post',$key)[1];
                $insertIMg = $pdo->prepare("INSERT INTO image  (`name`,  `for_post`  ) VALUES ( ? , ? ) ");
                $insertIMg->execute(array('View'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'post'.$key , $_POST['IdPost'] ) );
            }
        }
        die('success') ; 
    }
}


//--- Add Categ

if ( isset($_POST['form'] , $_POST['slug'] , $_POST['name'] ) && $_POST['form'] == 'fm-AddCateg' ){
    

    $_POST['slug'] =  str_replace(' ','', $_POST['slug']  );
    $count = $pdo->prepare("SELECT COUNT(*) FROM categorie WHERE slug= ? or name= ?  ");
    $count->execute(array($_POST['slug'] ,$_POST['name']));
    $count = $count->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] ; 

    if($count)
    {
        die("Ce nom ou ce Slug est déjà Uriliseer" );
    } 
    $add = $pdo->prepare("INSERT INTO  categorie ( slug , name) VALUE (? , ? ) ");
    $add->execute(array($_POST['slug'] ,$_POST['name'])); 
    die('success ! Element Ajouter  ');
}

//--- UPDATE categ

if ( isset($_POST['form'] , $_POST['slug'] , $_POST['idCateg'] , $_POST['name'] ) && $_POST['form'] == 'fm-SetCateg' ){
   
    $_POST['slug'] =  str_replace(' ','', $_POST['slug']  );
    $count = $pdo->prepare("SELECT COUNT(*) FROM categorie WHERE id = ?   ");
    $count->execute(array($_POST['idCateg'] ));
    $count = $count->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] ; 

    if($request)
    {
        die(" " );
    } 
    $add = $pdo->prepare("UPDATE   categorie SET   slug =?  , name = ?  WHERE id = ?  ");
    $add->execute(array($_POST['slug'] ,$_POST['name'] ,$_POST['idCateg'] ));

    die('success ! Modification Mis à Jour ');
}

//--- Update Post Disponibility
if( isset($_POST['form'] ,$_POST['idPost']) && $_POST['form']  == 'fm-diponibility' )
{
    $req=0; 
    if( $_POST['req'] ==1 )
    {
        $req = 1 ;
    }
    $update = $pdo->prepare("UPDATE post SET disponible = ? WHERE id = ? ");
    $update->execute(array($req , $_POST['idPost'] ));

    die('success');
}


// -- Delete Post -Categ -  
if (!empty( $_POST["operation"]) && !empty($_POST['target']) && !empty($_POST['idTarget']) && $_POST["operation"] == 'SetDelete') {
    
    
     $targetChoice = ['categorie' ,"user", 'post'];
     if ( in_array(  $_POST['target'] , $targetChoice )) {
         $target= $_POST['target'] ;
         echo $target.'     '. $_POST['idTarget'] ."      " ;

         $delete =  $pdo->prepare(" DELETE FROM  $target WHERE id = ? ");
         $delete->execute(array( $_POST['idTarget']));

         if ($_POST['target'] == 'post' || $_POST['target'] == 'categorie'  ) {
             $id_obj = 'id_'.$_POST['target'] ;
            $delete =  $pdo->prepare(" DELETE FROM categorie_post WHERE ? = ? ");
            $delete->execute(array($id_obj ,$_POST['idTarget']));
            if ($_POST['target'] == 'post' )
            {
                $delete =  $pdo->prepare(" DELETE FROM image WHERE for_post = ? ");
                $delete->execute(array($_POST['idTarget']));
            }
            die("success");
         }elseif ($_POST['target'] == 'user' )
         {
             $delete =  $pdo->prepare(" DELETE FROM comende WHERE client = ? ");
            $delete->execute(array($_POST['idTarget']));
            die("success");
         }
     } 

} 


// -- images of Galerie  
if(isset($_POST['form']) && $_POST["form"] ==  'getImg' )
{
    $dir = ROOTPATH.'View'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'post'.DIRECTORY_SEPARATOR. '*' ;
    $images = glob( $dir ); 
    $imgs = [];
    foreach($images as $image )
    { 
        $image = 'View'.explode('View' , $image)[1]; 
       array_push( $imgs ,  $image  ); 
    } 
    die(json_encode($imgs));
}

if (isset($_POST['form']) && $_POST['form']=='GenerateUser'   ) {
     
//-- Userrs
$syntax = "SELECT * FROM user ";

if(!empty($_POST['q_user']) && isset($_POST['q_user']) )
 {
     $q = $_POST['q_user'] ;
    $syntax .= " WHERE id ='$q' or is_affiliat ='$q' or nom like '%$q%'  or prenom like '%$q%' or tel like '%$q%'  or email like '%$q%' " ;
    
    if(count(explode(";" , $syntax)) > 1){
        die('STOP');
    }
 }
 if( isset($_POST['offset'])){
     $offset =  $_POST['offset'] ;
 }else{
    $offset = 0;
 }
 $syntax .= " ORDER BY is_admin DESC LIMIT 30 OFFSET $offset " ;
 
$usersReq = $pdo->query($syntax);
$users = $usersReq->fetchAll(PDO::FETCH_ASSOC); 
die(json_encode($users) ); 

}


// -- setUserConfig

if(isset($_POST['form'],$_POST['id'],$_POST['req'] ) && $_POST["form"] ==  'fm-setUsers' ){
    $req= str_replace(' ','' , $_POST['req']);
    if($req == 'DéfinircommeAdministrateur')
    {
        $sql = "is_admin = 1"; 
    }elseif($req == 'RetirerDesAdministrateur')
    {
        $sql = "is_admin = 0"; 
    }elseif($req == 'DéfinircommeAffilié')
    {
        $sql = "is_affiliat = 1"; 
    }elseif($req == 'DéfinircommeUtilisateur')
    {
        $sql = "is_affiliat = 0";
    }elseif($req == 'bloquer')
    {
        $sql = "is_affiliat = -3 , is_admin = 0 "; 
    }
    $request = $pdo->prepare("UPDATE user SET $sql WHERE id=? ");
    $request->execute(array($_POST['id']));
    
    die($req);

}

// -- Affiliatuin Demande  Generate
 if(isset($_POST['form']) && $_POST["form"] ==  'affiliationGenerate' ){
    $dir = ROOTPATH.'View'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'doc'.DIRECTORY_SEPARATOR. '*' ;
    $folders = glob( $dir ); 
    $docs = [];
    $result =[];
    foreach($folders as $folder )
    {
        $folder = 'View'.explode('View' , $folder)[1]; 
       array_push( $docs ,  $folder  ); 
       
    
    

    foreach($docs as $doc => $val )
    {
        $dir = ROOTPATH.$val.DIRECTORY_SEPARATOR.'*' ;
        $folders = glob( $dir ); 
         $img = [];
        foreach($folders as $folder )
        {
            $folder = 'View'.explode('View' , $folder)[1]; 
            array_push( $img ,  $folder  ); 
        }
    }
    $val= explode('doc'.DIRECTORY_SEPARATOR , $val)[1]   ;
    array_push($result ,[$val=>$img]); 
}

die(json_encode( array_reverse($result)));
}

///---- COnfig  Affiliation Demande 
if ( isset($_POST['form'],$_POST['action'] , $_POST['UserTarget'] , $_POST['fileName'] ) && $_POST['form'] =='fm-affiliation-config') {
    $idUser = $_POST['UserTarget'];
    $path =  ROOTPATH.'View'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'doc'.DIRECTORY_SEPARATOR.$_POST['fileName'];
    $NewPath =  ROOTPATH.'View'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'doc'.DIRECTORY_SEPARATOR.'Checked-'.$idUser;
    $cmd = $pdo->prepare("UPDATE comende SET statut = ? WHERE statut = -2 and  type = 'Affiliation' and  client = ? ");

    if ($_POST['action'] == 'refuser') {
        $cmd->execute(array("false",$idUser));
        
    }elseif ($_POST['action'] == 'accepte') {
        $cmd->execute(array("1",$idUser));
        $req = $pdo->prepare("UPDATE user SET is_affiliat = 1 WHERE id = ? ");
        $req->execute(array($idUser));
    } elseif ($_POST['action'] == 'suppr') {
         rmdir_recursive($path);
        $cmd->execute(array("false",$idUser));
        die('delete');
    }
    rename($path , $NewPath);
    die('success') ;
}

//--- generate cmd
if(isset($_POST['form']) && $_POST['form']== "getcmdPanel" )
{
    $syntax = "SELECT  user.prenom , user.nom , comende.* FROM comende ,user  WHERE (comende.type !='Affiliation' ) && (comende.client = user.id) ";
    if(!empty($_POST['q_cmd']) && isset($_POST['q_cmd']) )
    {
        $q = $_POST['q_cmd'] ;
    $syntax .= " && ( or comende.statut = '$q' or user.nom like '%$q%' or user.prenom like '%$q%' )" ;
    
    if(count(explode(";" , $syntax)) > 1){
        die('STOP');
    }
 }
 if( isset($_POST['offset'])){
     $offset =  $_POST['offset'] ;
 }else{
    $offset = 0;
 }
 $syntax .= " ORDER BY statut, id DESC LIMIT 30 OFFSET $offset " ;
 
$usersReq = $pdo->query($syntax);
$cmds = $usersReq->fetchAll(PDO::FETCH_ASSOC);
 die(json_encode($cmds) );
}

if(isset($_POST['form'],$_POST['action'] , $_POST['idTarget']) && $_POST['form']  == "SetConfigCommande" ){
    $action = str_replace(' ', '' ,$_POST['action']);
    $actuinCoice =['Refuser', 'Supprimer','Accepter'];
    if(in_array($action , $actuinCoice ))
    {
        if($action == 'Accepter' )
        {
             $sql = $pdo->prepare("UPDATE comende SET  statut = 1 WHERE id=? ");
             $result = "1";
    }elseif($action == 'Refuser' ){
        $sql = $pdo->prepare("UPDATE comende SET statut =  0 WHERE id=? ");
         $result = "0";
        }elseif($action == 'Supprimer' ){
            $sql = $pdo->prepare("DELETE FROM comende WHERE id=? ");
            $result ='Supprimer';
           }
           $sql->execute(array($_POST['idTarget']));
           die($result);
    }
}
//-- send Email
if(isset($_POST['form'] ,$_POST['objet_emil'],$_POST['corp_email']) && $_POST['form'] == "send_newsletter" )
{
    $emails = [];
    if( isset($_POST['Email_non_validés']) || isset($_POST['email_affiliés']) || isset($_POST['email_users']) ){
        $req = '' ;
        if (isset($_POST['Email_non_validés'])) {
            $req .= ' is_affiliat = -2 or ';
        } if (isset($_POST['email_affiliés'])) {
            $req .= ' is_affiliat = 0 or ';
        } if (isset($_POST['email_users'])) {
            $req .= ' is_affiliat = 1 or ';
        }
        $sql = $pdo->prepare("SELECT email FROM user WHERE ( $req  id=0) and sub_date > ? ");
        $sql->execute(array($_POST['inscrit_le']));
        $result = $sql->fetchAll(PDO::FETCH_COLUMN );
        foreach($result as $v){
            array_push( $emails, @$v);
        }
    }
    if(isset($_POST["autre_email"]))
    {
        $autre_email = explode( "/", $_POST["autre_email"] );
        foreach($autre_email as $v )
        {
            array_push($emails , $v);
        }
    }
    if( !isset($_POST['autre_email']) && !isset($_POST['email_users']) && !isset($_POST['email_affiliés']) && !isset($_POST['Email_non_validés'])){
        die ("aucun email a été envoyer" );
    }
    
    require(ROOTPATH."Class".DIRECTORY_SEPARATOR.'SendMail.php');
    foreach($emails as   $email){
        new SendMail($email ,$_POST['objet_emil'],$_POST['corp_email']);
    }
    $_POST['heur_envoie'] = date('Y-m-d h:m:i');
    unset($_POST['form']) ;
    $json_post = json_encode($_POST);
    $insert =$pdo->prepare("INSERT INTO newsletter(`email_info`)VALUES(?)");
    $insert->execute(array($json_post));
    die("success");
}

if(isset($_POST['form']) && $_POST['form'] == 'fm-GetNewletter' ){
   
    
    if(isset($_POST['offset'])) 
    {
        $offset= intval($_POST['offset']) ;
    }else{
        $offset=1;
    }
    $nwsletter = $pdo->query("SELECT * FROM newsletter  ORDER BY id DESC LIMIT 30 OFFSET $offset");
    $result = $nwsletter->fetchAll(PDO::FETCH_ASSOC); 
    die(json_encode($result));

}
if(isset($_POST['form']) && isset($_POST['sql']) && $_POST['form'] == "fm-terminal" )
{
    $sql = $_POST['sql'];
    $req = $pdo->query($sql);
    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    die(print_r($result));
}