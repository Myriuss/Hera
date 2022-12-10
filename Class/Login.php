<?php
/* 
    Login class

*/
    /**
     * 
     */ 
    class Login
    {
    	private $_error ;
        private $_email;
        private $_pass ;
    	
    	public function __construct($email = null , $pass = null)
        {
    		
            $this->_email = $email ;
            $this->_pass = $pass ;
        }
            public function Youlogin()
        {

    		if (isset( $this->_email,$this->_pass) && $this->issub() ) {
                return true ;
    		}
            else{
                $this->_error = 'Email ou Mot de passe incorrect '; 


            }
    	}
    	public 	function issub()
    	{
    		require(ROOTPATH.'elements'.DIRECTORY_SEPARATOR.'db_config.php');
    		$pdo  = new PDO($db_DSN , $db_USER , $db_PASS ) ;
    		$request2 = $pdo->prepare("SELECT COUNT(*) FROM user WHERE (email = ? or id = ? ) and (password = SHA2( ? , 256) or password = ? )  and is_affiliat >= 0 ");
    			 $request2->execute(array($this->_email ,$this->_email , $this->_pass , $this->_pass ));
    			 $count =  $request2->fetch(PDO::FETCH_ASSOC);
    			 return $count['COUNT(*)']	;
    	}
        public function Info()
        {
                 require(ROOTPATH.'elements'.DIRECTORY_SEPARATOR.'db_config.php');
            $pdo  = new PDO($db_DSN , $db_USER , $db_PASS ) ;
            $request3 = $pdo->prepare("SELECT * FROM user WHERE (email = ? or id = ? )  and (password = SHA2( ? , 256)   or password = ? ) and is_affiliat >= 0 ");
    			 $request3->execute(array($this->_email ,$this->_email , $this->_pass , $this->_pass ));
                 $info =  $request3->fetch(PDO::FETCH_ASSOC);
                 return $info  ;
            
        }

        
    	public function error()
    	{
            
    		if (isset($this->_error)) {
    			return $this->_error ;

    		}
    	}
}







