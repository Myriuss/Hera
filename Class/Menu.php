<?php 

/*

*/
class Menu 
{
	private $_aray ;

	public function __construct($aray = [])
	{
		@$this->_aray = $aray ;

	}

	function ToHTML()
	{
		if ( @$this->_aray == [] ) 
		{
			return  <<<HTML

			<a href='home'  >home</a>
			  <a href='login'  > Se Connecter </a>
			  <a href="singup" > S'inscrire </a>
			  <a href='contact'>Contact &  Adreesse </a>
HTML;
  
		}

		if (  @$this->_aray['is_admin'] == true ) 
		{
	return  <<<HTML
	<a href='home'  >home</a> 
	<a href='cmd'  >Espace  Client </a>
	<a href="admin" >Espace Administrateur </a>
	<a href='contact'>Contact &  Adreesse </a>
	<a href="#logout" data-toggle="modal" >Se Déconnecter </a>
HTML;
      
	  }

	  if (  @$this->_aray['is_affiliat']== false   ) 
	  {
		return  <<<HTML
		<a href='home'  >home</a>
		<a href='cmd'  >Espace  Client </a>
		<a href='contact'>Contact &  Adreesse </a>
		<a href="#logout" data-toggle="modal" >Se Déconnecter </a>
HTML;


	  }


	  if (@$this->_aray['is_affiliat'] == true ) {
		return  <<<HTML
		<a href='home'  >home</a>
		<a href='cmd'  > Espace  Client </a>
		<a href='contact'>Contact &  Adreesse </a>
		<a href="#logout" data-toggle="modal" >Se Déconnecter </a>
HTML;


		  }

	}
}