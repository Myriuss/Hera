<?php
/* 
    register

*/
    /**
     * 
     */


    class Register
    {
    	private $_error ;
		private $_email ;
		private $_pass ;
        private $_isbeenregister ;
    	
    	public function __construct( $nom = null ,  $prenom = null ,  $email = null , $pass = null , $pass2= null  , $condition= null ,  $tel = null  )
    	{
			
			$this->_email = $email  ;
			$this->_pass = $pass ;
			$this->_error = [];
			
    		if (strlen($prenom) < 3   ) {
				$this->_error['prenom']  =  'Prenom trop court ';

                 return '' ;

			}
			if ( strlen($nom) < 3  ) {
				$this->_error['nom']  =  ' Nom trop court ';

                 return '' ;

			}
			
    		if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
    			$this->_error['email'] =  'email invalide' ;

                 return '' ;
			}
			
    		if ($pass !== $pass2 ) {
    			$this->_error ['pass2'] = ' Mot de passe Incompatible ' ;

                 return '' ;

			}
			elseif (strlen($pass) < 8 ) {
    			$this->_error ['pass'] = ' Mot de passe  trop court' ;

                 return '' ;

			} 
			if ($condition != 'on') {
    			$this->_error ["condition"] =  'Veuillez lire et accepter les Termes du site ' ;

                 return '' ;

    		}
    		if ($this->issub()  ) {
    			$this->_error['email']=  " Cet email a déjà était Utilisé " ;

                 return '' ;

			}
			


    		if (isset($prenom , $nom , $email , $pass) ) {

    			require(ROOTPATH.'elements'.DIRECTORY_SEPARATOR.'db_config.php');


    			$pdo  = new PDO($db_DNS , $db_USER , $db_PASS ) ;
    			 $request = $pdo->prepare("INSERT INTO user  ( `nom`, `prenom`,`email`, `password` ,`tel` ) VALUES (?, ? , ? , SHA2( ?, 256) , ? )" );
    			  $request->execute(array($nom , $prenom , $email , $pass , $tel ));
                  $this->_isbeenregister = true  ;
				 
    		}
		}
		

    	public 	function issub()
    	{
    		require(ROOTPATH.'elements'.DIRECTORY_SEPARATOR.'db_config.php');
    		$pdo  = new PDO($db_DNS , $db_USER , $db_PASS ) ;
    		$request2 = $pdo->prepare("SELECT COUNT(*) FROM user WHERE is_affiliat >= 0 AND email =?  ");
    			 $request2->execute(array($this->_email ));
    			 $count =  $request2->fetch(PDO::FETCH_ASSOC);
    			 return $count['COUNT(*)'];

    	}
        public function isbeenregister()
        {
			if ($this->_isbeenregister )
			{
				return $this->_isbeenregister ;
			}elseif (empty($this->_isbeenregister)) {
				return false ;
			}
		}
		
    	public function seeErrors()
    	{
    		if (!empty($this->_error)) {
    			return $this->_error ;

    		}
    	}
}






