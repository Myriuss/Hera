<?php

class AddImg 

{
    public $_move ;
    public $_ImgName ;
    function __construct($FILE )
    { 
        
        $this->_ImgName =  array() ;
    $path =  (dirname( __DIR__ )).DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'post'.DIRECTORY_SEPARATOR ; 
    foreach ($FILE as $key  ) { 
        
        
        if (empty($key['error']) && $this->isImage($key['type'] , $path)) {
            
           $name = $path.'image-'.round(microtime(true) * 1000) .'.' .explode( "/" , $key['type'] )[1]  ;
            
           $move = move_uploaded_file($key['tmp_name'] , $name ) ; 
           $this->_move =  $move ; 
           array_push($this->_ImgName,$name) ; 
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
    
    
     
} 