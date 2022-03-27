<?php class uporabniki_controller{
	public function registracija(){
		require_once( "views/uporabniki/registracija.php" );
	}

	public function registriraj(){
		$upo = Uporabnik::dodaj( $_POST["email"], $_POST["username"], $_POST["firstname"], $_POST["lastname"], $_POST["password"],
			$_POST["address"], $_POST["postalcode"], $_POST["phone"], $_POST["sex"], $_POST["age"] );
		
		if( $upo )
			require_once( "views/uporabniki/prijava.php" );
		else
			echo "Something wen't wront!";
	}
	
	public function prijava(){
		require_once( "views/uporabniki/prijava.php" );
	}
	
	public function prijavi(){
		$upo = Uporabnik::prijavi( $_POST["username"], $_POST["password"] );
		if( $upo ){
			$_SESSION["USER_ID"] = $upo;
			header( "Location: index.php" );
		}else
			echo "Something wen't wront!";
	}
	
	public function odjava(){
		session_unset();
		session_destroy();
		header( "Location: index.php" );
	}
	
	public function admin(){
		$users = Uporabnik::getAll();
		
		require_once( "views/uporabniki/admin_profili.php" );
	}
	
	public function profile(){
		if( !isset( $_GET["id"] ) )
			return call( "strani", "napaka");
		
		$user = Uporabnik::getUser( $_GET["id"] );
		require_once( "views/uporabniki/profil.php" );
	}
	
	public function edit_user(){
		if( !isset( $_GET["id"] ) )
			return call( "strani", "napaka");
		
		$res = Uporabnik::editUser( $_GET["id"], $_POST["email"], $_POST["username"], $_POST["firstname"], $_POST["lastname"], $_POST["password"],
			$_POST["address"], $_POST["postalcode"], $_POST["phone"], $_POST["sex"], $_POST["age"] );
		
		$user = Uporabnik::getUser( $_GET["id"] );
		require_once( "views/uporabniki/profil.php" );
	}
} ?>