<?php 
/* generate produit 
*/


define("ROOTPATH",   dirname(dirname(__dir__)).DIRECTORY_SEPARATOR );

require(ROOTPATH.'elements'.DIRECTORY_SEPARATOR.'db_config.php');
$pdo  = new PDO($db_DNS , $db_USER , $db_PASS ) ;

$db_choice = ['post'  ,'categorie' , 'categorie_post' ] ;
$db_table= 'post' ;


if ( !empty($_GET['table'])  && in_array($_GET['table'] , $db_choice  ) ) 
{
    $db_table =  $_GET['table']  ;
}

if($db_table == "categorie"  )
{
    $requestText = " SELECT * FROM categorie  Where id  IS NOT NULL " ;
}
elseif( $db_table == "post"  )
{
    $requestText = " SELECT post.* , image.name AS'miniature' FROM post , image  Where ( post.id =  image.for_post )  " ;
}
if ( $db_table == 'categorie_post' && !empty( @$_GET['id_categ'])  ) {
    $id_categ = htmlentities($_GET['id_categ']) ;
   //$requestText = "  SELECT post.* , image.name from categorie_post  ,image   left join  post ON categorie_post.id_post = post.id  where (categorie_post.id_categorie = $id_categ ) and  ( post.id =  image.for_post )  " ;

   $requestText = "SELECT post.* , image.name AS'miniature' , categorie_post.id_categorie  as 'category_id'  from  image,  categorie_post    left  JOIN post ON categorie_post.id_post = post.id     where (categorie_post.id_categorie = $id_categ  and post.id =  image.for_post ) and  ( post.id =  image.for_post )   ";

}




if( !empty(@$_GET['q']))
{
    $q = htmlentities(@$_GET["q"] );
    $requestText .=  " and ( post.name like '%$q%' or post.description like '%$q%' )" ;
}
$v = 0 ;
$order = 'id ';
$dir = 'DESC' ;
$ordertype = [ 'name' , 'id' , 'prix' , 'p_date' ] ;
$dirtype = ['ASC' , 'DESC']; 
if (   !empty(@$_GET['order']) &&  in_array( $_GET['order' ] ,$ordertype)  ) {
    $order = htmlentities($_GET['order']) ;
}

if (  !empty(@$_GET['dir']) &&  in_array( $_GET['dir' ] ,$dirtype)  ) {
    $dir = htmlentities($_GET['dir']) ;
}

$_deviseChoice = ["point"  , 'DZD'] ; 


 
if ( !empty( @$_GET["min"] )  && @$_GET["min"]  >= 0  )
{
    $min = @$_GET['min'] ;
    $requestText .=  "  and ( prix >= ' $min' ) ";
}
if ( !empty(@$_GET["max"])  && @$_GET["max"]  >= 0   )
{
    $max = @$_GET['max'] ;
    $requestText .=  "  and ( prix <= '$max' ) ";
}
if ( !empty(@$_GET["devise"]) && in_array(@$_GET["devise"] , $_deviseChoice) )
{
    $devise = @$_GET['devise'] ;
    $requestText .=  "  and ( monnais = '$devise' ) ";
}

if (!empty(@$_GET['v']) && @$_GET['v'] >=0  ) {
    $v = $_GET['v'] -1 ;
}


$requestText .= " ORDER BY $order $dir   limit 30 OFFSET $v " ;

if(count(explode(";" , $requestText)) > 1)
{
    die('STOP');
}
$request = $pdo->query($requestText);
$result =  $request->fetchAll(PDO::FETCH_ASSOC );
 

echo json_encode( $result );
// debug// echo $requestText  ;
