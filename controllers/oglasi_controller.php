<?php class oglasi_controller{

	public function index(){
		$oglasi = Oglas::vsi();

		require_once( "views/oglasi/index.php" );
	}

	public function prikazi(){
		if( !isset( $_GET["id"] ) )
			return call( "strani", "napaka");
		
		$oglas = Oglas::najdi( $_GET["id"] );
		require_once( "models/uporabniki.php" );
		$user = Uporabnik::getUser( $oglas->user_id );
		require_once( "views/oglasi/prikazi.php" );
    }

    public function dodaj(){
		$categories = Oglas::kategorije();
		$subCats = array();
		
		require_once( "views/oglasi/dodaj.php" );
    }

	public function shrani(){
		$cats = "";
		foreach( $_POST as $key => $val ){
		  if( is_int( $key ) ){
			$cats = $cats . $key . " ";
		  }
		}
		
		if( $og = Oglas::dodaj( $_POST["title"], $_POST["description"], $_SESSION["USER_ID"], $_FILES["show"], $_FILES["images"], $cats ) ){
			$oglas = $og;
			require_once( "views/oglasi/uspesnoDodal.php" );
		}else
			echo "Something went wrong!";
    }
	
	public function moji(){
		$oglasi = Oglas::vsi();
		
		require_once( "views/oglasi/moji.php" );
	}
	
	public function uredi(){
		if( !isset( $_GET["id"] ) )
			return call( "strani", "napaka");
		
		$categories = Oglas::kategorije();
		$oglas = Oglas::najdi( $_GET["id"] );
		require_once( "views/oglasi/uredi.php" );
	}
	
	public function spremeni(){
		if( !isset( $_GET["id"] ) )
			return call( "strani", "napaka");
		
		if( isset( $_POST["uredi"] ) ){
			$cats = "";
			foreach( $_POST as $key => $val ){
			  if( is_int( $key ) ){
				$cats = $cats . $key . " ";
			  }
			}
			
			Oglas::uredi( $_GET["id"], $_POST["title"], $_POST["description"], $_FILES );
			header( "Location: index.php?controller=oglasi&action=prikazi&id=" . $_GET["id"] );
		}
	}
} ?>