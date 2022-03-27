<?php
	function call( $controller, $action ){
		require_once( "controllers/" . $controller . "_controller.php" );
		require_once( "models/" . $controller . ".php" );
		$o = $controller."_controller";
		$controller = new $o;
		$controller->{ $action }();
	}

	$controllers = array( "strani" => ["domov", "napaka"], "oglasi" => ["index", "prikazi", "dodaj", "shrani", "moji", "uredi", "spremeni"],
		"uporabniki" => ["registracija", "registriraj", "prijava", "prijavi", "odjava", "admin", "profile", "edit_user"] );

	if( array_key_exists( $controller, $controllers ) ){
		if( in_array( $action, $controllers[$controller] ) )
			call( $controller, $action );
		else
			call( "strani", "napaka" );
	}else
		call( "strani", "napaka" );
?>