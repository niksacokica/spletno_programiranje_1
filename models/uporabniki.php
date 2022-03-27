<?php class Uporabnik{
	public static function dodaj( $email, $username, $firstname, $lastname, $password, $address, $postalcode, $phone, $sex, $age ){
		$db = Db::getInstance();
		
		if( $stmt = mysqli_prepare( $db, "Insert into users ( email, username, firstname, lastname, password, address, postalcode, phone, sex, age, isAdmin )
			Values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, false)" ) ){
			mysqli_stmt_bind_param( $stmt, "sssssssisi", $email, $username, $firstname, $lastname, $password, $address, $postalcode, $phone, $sex, $age );
			mysqli_stmt_execute( $stmt );
			mysqli_stmt_close( $stmt );
			
			return true;
		}
		
		return false;
	}
	
	public static function prijavi( $username, $password ){
		$db = Db::getInstance();
		
		if( $result = mysqli_query( $db, "SELECT * FROM users WHERE username='$username' AND password='$password'" ) ){
			$row = mysqli_fetch_assoc( $result );
			return $row["id"];
		}else
			return false;
	}
	
	public static function isAdmin( $id ){
		$db = Db::getInstance();
		
		if( $result = mysqli_query( $db, "SELECT * FROM users WHERE id=$id" ) ){
			$row = mysqli_fetch_assoc( $result );
			return $row["isAdmin"];
		}else
			return false;
	}
	
	public static function getAll(){
		$db = Db::getInstance();
		$result = mysqli_query( $db, "SELECT * FROM users" );
		
		$users = array();
		while( $row = mysqli_fetch_assoc( $result ) )
			array_push( $users, $row );
    
		return $users;
	}
	
	public static function getUser( $id ){
		$db = Db::getInstance();
		
		if( $result = mysqli_query( $db, "SELECT * FROM users WHERE id=$id" ) ){
			$row = mysqli_fetch_assoc( $result );
			return $row;
		}else
			return false;
	}
	
	public static function editUser( $id, $email, $username, $firstname, $lastname, $password, $address, $postalcode, $phone, $sex, $age ){
		$db = Db::getInstance();
		
		return mysqli_query( $db, "UPDATE users SET email='$email', username='$username', firstname='$firstname', lastname='$lastname',
			password='$password', address='$address', postalcode='$postalcode', phone='$phone', sex='$sex', age='$age' WHERE id=$id" );
	}
}?>