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

function reArrayFiles( $file_post ){
	$file_ary = array();
    for( $i=0; $i<count( $file_post["name"] ); $i++ )
        foreach( array_keys( $file_post ) as $key )
            $file_ary[$i][$key] = $file_post[$key][$i];

    return $file_ary;
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
	print_r( $_FILES );
	
	if( empty( $_POST["title"] ) || empty( $_POST["description"] ) )
		$error = "napačen vnos.";
	else if( $_FILES["show"]["error"] == 0 && $_FILES["images"]["error"][0] == 0 ){
		$title = mysqli_real_escape_string( $conn, $_POST["title"] );
		$description = mysqli_real_escape_string( $conn, $_POST["description"] );
		$cat = $_POST["category"];
		
		$pics = glob( $oglas->images . "*" );
		foreach( $pics as $pic ){
			unlink( $pic );
		}
		
		$show_pic = $_FILES["show"];
		$allowed = array( "image/jpeg", "image/gif", "image/png" );
		if( in_array( $show_pic["type"], $allowed ) ){
			$images = reArrayFiles( $_FILES["images"] );
			$show = $show_pic["name"];
			
			move_uploaded_file( $show_pic["tmp_name"], $oglas->images . $show );
			foreach( $images as $img ){
				if( $img["name"] != $show ){
					if( in_array( $img["type"], $allowed ) )
						move_uploaded_file( $img["tmp_name"], $oglas->images . $img["name"] );
				}
			}
			
			$query = "UPDATE ads SET title='$title', description='$description', show_image='$show',
					category_id = '$cat' WHERE id='$id';";
		
			if( !$conn->query( $query ) )
				$error = mysqli_error( $conn );
		}else
			$error = "Napaka! Datoteka ni slika!";
	}else if( $_FILES["images"]["error"][0] == 0 ){
		$title = mysqli_real_escape_string( $conn, $_POST["title"] );
		$description = mysqli_real_escape_string( $conn, $_POST["description"] );
		$cat = $_POST["category"];
		$query = "UPDATE ads SET title='$title', description='$description', category_id = '$cat'
				WHERE id='$id';";
	
		if( !$conn->query( $query ) )
			$error = mysqli_error( $conn );
		
		$pics = glob( $oglas->images . "*" );
		foreach( $pics as $pic ){
			if( $pic != $oglas->images . $oglas->show_image )
				unlink( $pic );
		}
		
		$allowed = array( "image/jpeg", "image/gif", "image/png" );
		$images = reArrayFiles( $_FILES["images"] );
		foreach( $images as $img ){
			if( in_array( $img["type"], $allowed ) )
				move_uploaded_file( $img["tmp_name"], $oglas->images . $img["name"] );
		}
	}else if( $_FILES["show"]["error"] == 0 ){
		$show_pic = $_FILES["show"];
		$allowed = array( "image/jpeg", "image/gif", "image/png" );
		if( in_array( $show_pic["type"], $allowed ) ){
			$title = mysqli_real_escape_string( $conn, $_POST["title"] );
			$description = mysqli_real_escape_string( $conn, $_POST["description"] );
			$cat = $_POST["category"];
			
			$show = $show_pic["name"];
			unlink( $oglas->images . $oglas->show_image );
			move_uploaded_file( $show_pic["tmp_name"], $oglas->images . $show );
			
			$query = "UPDATE ads SET title='$title', description='$description', show_image='$show',
					category_id='$cat' WHERE id='$id';";
				
			if( !$conn->query( $query ) )
				$error = mysqli_error( $conn );
		}else
			$error = "Napaka! Datoteka ni slika!";
	}else if( $_FILES["show"]["error"] != 0 && $_FILES["images"]["error"][0] != 0 ){
		$title = mysqli_real_escape_string( $conn, $_POST["title"] );
		$description = mysqli_real_escape_string( $conn, $_POST["description"] );
		$cat = $_POST["category"];
			
		$query = "UPDATE ads SET title='$title', description='$description', category_id = '$cat' WHERE id='$id';";
		
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
		$error = $tmp->format( 'Y-m-d H:i:s' );
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
?>

	<form action="uredi_oglas.php?id=<?php echo $oglas->id;?>" method="POST" enctype="multipart/form-data" style="padding: 1%;">
		<label>Naslov</label><input type="text" name="title" value="<?php echo $oglas->title;?>" /> <br/>
		<label>Opis</label><br/><textarea name="description" rows="5" cols="35"><?php echo $oglas->description;?></textarea> </br> <br/>
		
		<img src="<?php echo $oglas->images . $oglas->show_image;?>" width="200"/>
		<input type="file" name="show" > </br> </br>
		
		<?php $pics = glob( $oglas->images . "*" );
		foreach( $pics as $pic ){
			if( $pic != $oglas->images . $oglas->show_image )
				echo '<img src="' . $pic . '" width="200"/>';
		} ?>
		<input type="file" name="images[]" multiple > </br> </br>
		
		<label>Kategorija</label> <select name="category">
			<?php foreach( $categories as $category ){
				if( $category->isMainCategory ){ ?>
					<option value=<?php echo $category->id;?>
					<?php if( $category->id == $oglas->category_id ) echo 'selected="selected"';?>>
					<?php echo $category->name;?></option>
			<?php } } ?>
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
		<?php
			$ts = new DateTime( "now", new DateTimeZone( "Europe/Ljubljana" ) );
			$ts->setTimestamp( time() );
			if( $ts->format( 'Y-m-d H:i:s' ) > $oglas->enddate ){ ?>
			<input type="submit" name="podaljsaj" value="Podaljšaj" />
		<?php } ?>
		<input type="submit" name="izbrisi" value="Izbriši" />
	</form>
	
<?php include_once( "noga.php" ); ?>