<?php include_once( "glava.php" );

function username_exists( $username ){
	global $conn;
	$username = mysqli_real_escape_string( $conn, $username );
	$query = "SELECT * FROM users WHERE username='$username';";
	$res = $conn->query( $query );
	return mysqli_num_rows( $res ) > 0;
}

function register_user( $username, $password ){
	global $conn;
	$username = mysqli_real_escape_string( $conn, $username );
	$pass = sha1( $password );
	$query = "INSERT INTO users (username, password) VALUES ('$username', '$pass');";
	if( $conn->query( $query ) )
		return true;
	
	echo mysqli_error( $conn );
	return false;
}

$error = "";
if( isset( $_POST["poslji"] ) ){
	if( $_POST["password"] != $_POST["repeat_password"] )
		$error = "Gesli se ne ujemata.";
	else if( username_exists( $_POST["username"] ) )
		$error = "Uporabniško ime je že obstaja.";
	else if( register_user( $_POST["username"], $_POST["password"] ) ){
		header( "Location: prijava.php" );
		die();
	}else
		$error = "Prišlo je do napake pri registraciji.";
}

?>

	<h2>Registracija</h2>
	<form action="registracija.php" method="POST">
		<label>Uporabniško ime</label><input type="text" name="username" /> <br/>
		<label>Geslo</label><input type="password" name="password" /> <br/>
		<label>Ponovi geslo</label><input type="password" name="repeat_password" /> <br/>
		<input type="submit" name="poslji" value="Pošlji" /> <br/>
		<label><?php echo $error;?></label>
	</form>
	
<?php include_once( "noga.php" ); ?>