<?php require_once( "connection.php" );
	$controller = "strani";
	$action = "domov";
	
	if( isset( $_GET["controller"] ) && isset( $_GET["action"] ) ) {
		$controller = $_GET["controller"];
		$action = $_GET["action"];
	}
	
	session_start();
	
	if( isset( $_SESSION["LAST_ACTIVITY"] ) && time() - $_SESSION["LAST_ACTIVITY"] < 1800 )
		session_regenerate_id( true );
	$_SESSION["LAST_ACTIVITY"] = time();

require_once( "views/layout.php" ); ?>