<?php include "comment.php";
	
	$method = $_SERVER['REQUEST_METHOD'];

	if( isset( $_SERVER["PATH_INFO"] ) )
		$request = explode( '/', trim( $_SERVER['PATH_INFO'], '/' ) );
	else
		$request="";
	
	$db=mysqli_connect( "localhost", "root", "", "vaja2" );
	$db->set_charset( "UTF8" );
	
	if( isset( $request[0] ) && ( $request[0] == "comment" ) ){
		switch( $method ){
			case "GET":
				if( isset( $request[1] ) && $request[1] == "top5" )
					$comment = Comment::vrniPet( $db );
				else if( isset( $request[1] ) && isset( $request[2] ) )
					$comment = Comment::vrniEnega( $db, $request[1], request[2] );
				else if( isset( $request[1] ) )
					$comment = Comment::vrniVsePost( $db, $request[1] );
				else
					$comment = Comment::vrniVse( $db );
				
				break;
			case "POST":
				parse_str( file_get_contents( 'php://input' ), $input );
				if( isset( $input ) ){
					$ts = new DateTime( "now", new DateTimeZone( "Europe/Ljubljana" ) );
					$ts->setTimestamp( time() );
					$comment = new Comment( $input["id"], $input["email"], $input["username"], $input["comment"], $ts->format( 'Y-m-d H:i:s' ), $input["ip"] );
					$comment->dodaj( $db );
				}else
					$comment = array( "info" => "Ni podane vsebine oglasa" );
				
				break;
			case "DELETE":
				if( isset( $request[1] ) )
					$comment = array( "info" => Comment::del( $db, $request[1], $request[2] ) );
				else
					$comment = array( "info" => "komentar ni bil uspešno izbrisan" );
				break;
		}

		header( "Content-Type: application/json" );
		header( "Access-Control-Allow-Origin: *" );
		echo json_encode( $comment );
	}
?>