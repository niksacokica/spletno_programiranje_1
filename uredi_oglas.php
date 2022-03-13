<?php include_once('glava.php');

function get_ad( $id ){
	global $conn;
	$id = mysqli_real_escape_string( $conn, $id );
	$query = "SELECT * FROM ads WHERE id='$id';";
	$res = $conn->query( $query );
	if( $obj = $res->fetch_object() )
		return $obj;
	
	return null;
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

if( !isset( $_GET["id"] ) ){
	echo "Manjkajoči parametri.";
	die();
}
$id = $_GET["id"];
$oglas = get_ad( $id );
if( $oglas == null ){
	echo "Oglas ne obstaja.";
	die();
}

$error = "";
if( isset( $_POST["uredi"] ) ){
	if( empty( $_POST["title"] ) || empty( $_POST["description"] ) )
		$error = "napačen vnos.";
	else if( !is_uploaded_file( $_FILES["image"]["tmp_name"]  ) ){
		$title = mysqli_real_escape_string( $conn, $_POST["title"] );
		$description = mysqli_real_escape_string( $conn, $_POST["description"] );
		$query = "UPDATE ads SET title='$title', description='$description' WHERE id='$id';";
	
		if( !$conn->query( $query ) )
			$error = mysqli_error( $conn );
	}else if( getimagesize( $_FILES["image"]["tmp_name"] ) ){
		$title = mysqli_real_escape_string( $conn, $_POST["title"] );
		$description = mysqli_real_escape_string( $conn, $_POST["description"] );
		$img_file = mysqli_real_escape_string( $conn, file_get_contents( $_FILES["image"]["tmp_name"] ) );
		$query = "UPDATE ads SET title='$title', description='$description',
			image='$img_file' WHERE id='$id';";
			
		if( !$conn->query( $query ) )
			$error = mysqli_error( $conn );
	}else
		echo $error;
	
	header( "Refresh:0" );
}else if( isset( $_POST["podaljsaj"] ) ){
	$id = mysqli_real_escape_string( $conn, $id );
	$query = "UPDATE ads SET enddate=DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE id='$id';";
	if( $conn->query( $query ) ){
		$tmp = new DateTime( "now", new DateTimeZone( "Europe/Ljubljana" ) );
		$tmp->setTimestamp( time() );
		$error = $tmp->format( 'd-m-Y, H:i:s' );
	}else
		$error = mysqli_error( $conn );
}else if( isset( $_POST["izbrisi"] ) ){
	$id = mysqli_real_escape_string( $conn, $id );
	$query = "DELETE FROM ads WHERE id='$id';";
	$conn->query( $query );
	header( "Location: moji_oglasi.php" );
	die();
}

$categories = get_categories();
$img_data = base64_encode( $oglas->image );
?>

	<form action="uredi_oglas.php?id=<?php echo $oglas->id;?>" method="POST" enctype="multipart/form-data">
		<label>Naslov</label><input type="text" name="title" value="<?php echo $oglas->title;?>" /> <br/>
		<label>Opis</label><br/><textarea name="description" rows="10" cols="50"><?php echo $oglas->description;?></textarea> </br> <br/>
		
		<img src="data:image/jpg;base64, <?php echo $img_data;?>" width="200"/>
		<input type="file" name="image" /> </br> </br>
		
		<label>Kategorija</label> <select name="category">
			<?php foreach( $categories as $category ){ ?>
				<option value=<?php echo $category->id;?>
				<?php if( $category->id == $oglas->category_id ) echo 'selected="selected"';?>>
				<?php echo $category->name;?></option>
			<?php } ?>
		</select>
		
		
		<p>Datum objave: <?php echo $oglas->postdate;?></p>
		<p>Datum zapadlosti:
		<?php if( isset( $_POST["podaljsaj"] ) ){
			 echo $error;
		}else{
			echo $oglas->enddate;
		} ?>
		</p>
		<input type="submit" name="uredi" value="Uredi" />		
		<?php if( date( "Y-m-d H:i:s" ) > $oglas->enddate ){ ?>
			<input type="submit" name="podaljsaj" value="Podaljšaj" />
		<?php } ?>
		<input type="submit" name="izbrisi" value="Izbriši" />
	</form>
	
<?php include_once( "noga.php" ); ?>