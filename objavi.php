<?php include_once( "glava.php" );

function publish( $title, $desc, $img ){
	global $conn;
	$title = mysqli_real_escape_string( $conn, $title );
	$desc = mysqli_real_escape_string( $conn, $desc );
	$user_id = $_SESSION["USER_ID"];

	$img_file = mysqli_real_escape_string( $conn, file_get_contents( $img["tmp_name"] ) );
	
	$query = "INSERT INTO ads (title, description, user_id, image)
				VALUES('$title', '$desc', '$user_id', '$img_file');";
				
	if( $conn->query( $query ) )
		return true;
	return false;
	
	/*
		$imeSlike=$photo["name"]; //Pazimo, da je enolično!
		//sliko premaknemo iz začasne poti, v neko našo mapo, zaradi preglednosti
		move_uploaded_file($photo["tmp_name"], "slika/".$imeSlike);
		$pot="slika/".$imeSlike;		
		//V bazo shranimo $pot
	*/
}

$error = "";
if( isset( $_POST["poslji"] ) ){
	if( publish( $_POST["title"], $_POST["description"], $_FILES["image"] ) ){
		header( "Location: index.php" );
		die();
	}
	else
		$error = "Prišlo je do našpake pri objavi oglasa.";
}
?>

	<h2>Objavi oglas</h2>
	<form action="objavi.php" method="POST" enctype="multipart/form-data">
		<label>Naslov</label><input type="text" name="title" /> <br/>
		<label>Vsebina</label><textarea name="description" rows="10" cols="50"></textarea> <br/>
		<label>Slika</label><input type="file" name="image" /> <br/>
		<input type="submit" name="poslji" value="Objavi" /> <br/>
		<label><?php echo $error;?></label>
	</form>
<?php

include_once( "noga.php" ); ?>