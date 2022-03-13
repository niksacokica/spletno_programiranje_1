<?php include_once( "glava.php" );

function email_exists( $email ){
	global $conn;
	$email = mysqli_real_escape_string( $conn, $email );
	$query = "SELECT * FROM users WHERE email='$email';";
	$res = $conn->query( $query );
	return mysqli_num_rows( $res ) > 0;
}

function username_exists( $username ){
	global $conn;
	$username = mysqli_real_escape_string( $conn, $username );
	$query = "SELECT * FROM users WHERE username='$username';";
	$res = $conn->query( $query );
	return mysqli_num_rows( $res ) > 0;
}

function register_user( $email, $username, $firstname, $lastname, $password,
		$address, $postalcode, $phone, $sex, $age ){
	global $conn;
	
	$email = mysqli_real_escape_string( $conn, $email );
	$username = mysqli_real_escape_string( $conn, $username );
	$firstname = mysqli_real_escape_string( $conn, $firstname );
	$lastname = mysqli_real_escape_string( $conn, $lastname );
	$address = mysqli_real_escape_string( $conn, $address );
	$sex = mysqli_real_escape_string( $conn, $sex );
	
	$pass = sha1( $password );
	$query = "INSERT INTO users (email, username, firstname, lastname, password,
		address, postalcode, phone, sex, age)
		VALUES ('$email', '$username', '$firstname', '$lastname', '$pass',
		'$address', '$postalcode', '$phone', '$sex', '$age');";
	if( $conn->query( $query ) )
		return true;
	
	echo mysqli_error( $conn );
	return false;
}

$error = "";
if( isset( $_POST["poslji"] ) ){
	if( empty( $_POST["email"] ) || empty( $_POST["username"] ) ||
		empty( $_POST["firstname"] ) || empty( $_POST["lastname"] ) ||
		empty( $_POST["password"] ) )
		$error = "Obvezna podatki rabijo biti izpoljneni.";
	else if( $_POST["password"] != $_POST["repeat_password"] )
		$error = "Gesli se ne ujemata.";
	else if( email_exists( $_POST["email"] ) )
		$error = "Elektronski naslov že obstaja.";
	else if( username_exists( $_POST["username"] ) )
		$error = "Uporabniško ime že obstaja.";
	else if( register_user( $_POST["email"], $_POST["username"], $_POST["firstname"],
		$_POST["lastname"], $_POST["password"], $_POST["address"], $_POST["postalcode"],
		$_POST["phone"], $_POST["sex"], $_POST["age"] ) ){
		header( "Location: prijava.php" );
		die();
	}else
		$error = "Prišlo je do napake pri registraciji.";
}

?>

	<h2>Registracija</h2>
	<form action="registracija.php" method="POST">
		<label>Obvezni podatki:</label> <br/>
		<label>Elektronski naslov</label><input type="email" name="email" /> <br/>
		<label>Uporabniško ime</label><input type="text" name="username" /> <br/>
		<label>Ime</label><input type="text" name="firstname" /> <br/>
		<label>Priimek</label><input type="text" name="lastname" /> <br/>
		<label>Geslo</label><input type="password" name="password" /> <br/>
		<label>Ponovi geslo</label><input type="password" name="repeat_password" /> <br/>
		<br/> <label>Neobvezni podatki:</label> <br/>
		<label>Naslov</label><input type="text" name="address" /> <br/>
		<label>Pošta</label><input type="number" min="0" max="1000000" value="0" name="postalcode" /> <br/>
		<label>Telefonska številka</label><input type="tel" name="phone" /> <br/>
		<label>Spol</label><input type="text" name="sex" /> <br/>
		<label>Starost</label><input type="number" min="0" max="100" value="0" name="age" /> <br/>
		<br/> <input type="submit" name="poslji" value="Pošlji" /> <br/>
		<label><?php echo $error;?></label>
	</form>
	
<?php include_once( "noga.php" ); ?>