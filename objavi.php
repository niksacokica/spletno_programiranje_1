<?php include_once( "glava.php" );

function publish( $title, $desc, $img, $cat ){
	global $conn;
	$title = mysqli_real_escape_string( $conn, $title );
	$desc = mysqli_real_escape_string( $conn, $desc );
	$user_id = $_SESSION["USER_ID"];

	$img_file = mysqli_real_escape_string( $conn, file_get_contents( $img["tmp_name"] ) );
	
	$postdate = date( "Y-m-d H:i:s" );
	
	$query = "INSERT INTO ads (title, description, user_id, image, postdate, enddate, category_id)
				VALUES('$title', '$desc', '$user_id', '$img_file', '$postdate', 
				DATE_ADD('$postdate', INTERVAL 30 DAY), $cat);";
				
	if( $conn->query( $query ) )
		return true;
	
	echo mysqli_error( $conn );
	return false;
	
	/*
		$imeSlike=$photo["name"]; //Pazimo, da je enolično!
		//sliko premaknemo iz začasne poti, v neko našo mapo, zaradi preglednosti
		move_uploaded_file($photo["tmp_name"], "slika/".$imeSlike);
		$pot="slika/".$imeSlike;		
		//V bazo shranimo $pot
	*/
}

function get_categories(){
	global $conn;
	$query = "SELECT * FROM categories;";
	$res = $conn->query( $query );
	
	$categories = array();
	while( $category = $res->fetch_object() )
		array_push( $categories, $category );
	
	return $categories;
}

$categories = get_categories();
$error = "";
if( isset( $_POST["poslji"] ) ){
	if( empty( $_POST["title"] ) || empty( $_POST["description"] ) ||
		( !is_uploaded_file( $_FILES["image"]["tmp_name"]  ) || !getimagesize( $_FILES["image"]["tmp_name"] ) ) )
		$error = "napačen vnos.";
	else if( publish( $_POST["title"], $_POST["description"], $_FILES["image"], $_POST["category"] ) ){
		header( "Location: index.php" );
		die();
	}
	else
		$error = "Prišlo je do napake pri objavi oglasa.";
}
?>

	<h2>Objavi oglas</h2>
	<form action="objavi.php" method="POST" enctype="multipart/form-data">
		<label>Naslov</label><input type="text" name="title" /> <br/>
		<label>Opis</label><br/><textarea name="description" rows="10" cols="50"></textarea> <br/>
		<label>Slika</label><input type="file" name="image" /> <br/>
		<label>Kategorija</label> <select name="category">
			<?php foreach( $categories as $category ){ ?>
				<option value=<?php echo $category->id;?>><?php echo $category->name;?></option>
			<?php } ?>
		</select>
		<input type="submit" name="poslji" value="Objavi" /> <br/>
		<label><?php echo $error;?></label>
	</form>
	
<?php include_once( "noga.php" ); ?>