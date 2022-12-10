<?php
/* 
   ModifyUser_DoCmd
*/


class ModifyUser_DoCmd  
{
    private $_id_user ;
    private $_pass ;

    public function __construct($id = null , $pass=null  )
    {
        $this->_id_user = $id ; 
        $this->_pass = $pass ;
    }
    public function fm_nom($prenom , $nom )
    {
        require(ROOTPATH.'elements'.DIRECTORY_SEPARATOR.'db_config.php');
        $pdo  = new PDO($db_DSN , $db_USER , $db_PASS ) ;
        $request = $pdo->prepare("UPDATE user SET prenom  = ? , nom = ? WHERE id = ? and password = ?");
             $request->execute(array($prenom ,$nom , $this->_id_user , $this->_pass ));
             
        $request2 = $pdo->prepare("SELECT * from user WHERE prenom  = ? and nom = ? and id = ? and password = ?");
             $request2->execute(array($prenom ,$nom , $this->_id_user , $this->_pass ));
             $result = $request2->fetch(PDO::FETCH_ASSOC);
             return $result	;
             
    }
    public function fm_Tel_Pass_Adrr( $E_change , $change )
    {
        require(ROOTPATH.'elements'.DIRECTORY_SEPARATOR.'db_config.php');
        $pdo  = new PDO($db_DSN , $db_USER , $db_PASS ) ;
        $request = $pdo->prepare("UPDATE user SET $E_change  = ? WHERE id = ? and password = ?");
        $request->execute(array($change, $this->_id_user , $this->_pass ));
             
        $request2 = $pdo->prepare("SELECT * from user WHERE $E_change  = ?  and  id = ?  ");
        $request2->execute(array($change, $this->_id_user  ));
        $result = $request2->fetch(PDO::FETCH_ASSOC);
        return $result	;
    }
}
