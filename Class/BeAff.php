<?php

class BeAff 
{
    private $_move ;
    function __construct($_id_user , $FILE )
    {
       
    $path =  (dirname( __DIR__ )).DIRECTORY_SEPARATOR.'View/image/doc/user-'.$_id_user .DIRECTORY_SEPARATOR ; 
    foreach ($FILE as $key  ) { 
        if (empty($key['error']) && $this->isImage($key['type'] , $path)) {
            
           $move = move_uploaded_file(  $key['tmp_name'] ,  $path.'image-'.round(microtime(true) * 1000).'.' .explode( "/" , $key['type'] ) [1]   ) ; 
           $this->_move =  $move ;
           

       }
   }
    }
    
    public function isImage($ImgType ,$path )
    {
        $ttue_format = ['jpeg','png','jpg' ];
        $imgInfo = explode( "/" , $ImgType ) ;
        if ($imgInfo[0] == 'image' && in_array($imgInfo[1] , $ttue_format)   ) {
           if(!is_dir($path))
           {
               mkdir($path , 0777 ,true);
           }
            return true ; 
        }else{
            return false ;
        }
    } 
    public function result()
    {
        return $this->_move ;
    }
} 