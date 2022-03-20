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
	$cats = "";
	foreach( $_POST as $key => $val ){
	  if( is_int( $key ) ){
		$cats = $cats . $key . " ";
	  }
	}
	$cats = substr( $cats, 0, -1);
	
	if( empty( $_POST["title"] ) || empty( $_POST["description"] ) || empty( $cats ) )
		$error = "napačen vnos.";
	else if( $_FILES["show"]["error"] == 0 && $_FILES["images"]["error"][0] == 0 ){
		$title = mysqli_real_escape_string( $conn, $_POST["title"] );
		$description = mysqli_real_escape_string( $conn, $_POST["description"] );
		
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
					categories_ids = '$cats' WHERE id='$id';";
		
			if( !$conn->query( $query ) )
				$error = mysqli_error( $conn );
			else
				header( "Refresh:0" );
		}else
			$error = "Napaka! Datoteka ni slika!";
	}else if( $_FILES["images"]["error"][0] == 0 ){
		$title = mysqli_real_escape_string( $conn, $_POST["title"] );
		$description = mysqli_real_escape_string( $conn, $_POST["description"] );
		$query = "UPDATE ads SET title='$title', description='$description',
			categories_ids = '$cats' WHERE id='$id';";
	
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
		
		header( "Refresh:0" );
	}else if( $_FILES["show"]["error"] == 0 ){
		$show_pic = $_FILES["show"];
		$allowed = array( "image/jpeg", "image/gif", "image/png" );
		if( in_array( $show_pic["type"], $allowed ) ){
			$title = mysqli_real_escape_string( $conn, $_POST["title"] );
			$description = mysqli_real_escape_string( $conn, $_POST["description"] );
			
			$show = $show_pic["name"];
			unlink( $oglas->images . $oglas->show_image );
			move_uploaded_file( $show_pic["tmp_name"], $oglas->images . $show );
			
			$query = "UPDATE ads SET title='$title', description='$description',
				show_image='$show', categories_ids='$cats' WHERE id='$id';";
				
			if( !$conn->query( $query ) )
				$error = mysqli_error( $conn );
			else
				header( "Refresh:0" );
		}else
			$error = "Napaka! Datoteka ni slika!";
	}else if( $_FILES["show"]["error"] != 0 && $_FILES["images"]["error"][0] != 0 ){
		$title = mysqli_real_escape_string( $conn, $_POST["title"] );
		$description = mysqli_real_escape_string( $conn, $_POST["description"] );
			
		$query = "UPDATE ads SET title='$title', description='$description',
			categories_ids = '$cats' WHERE id='$id';";
		
		if( !$conn->query( $query ) )
			$error = mysqli_error( $conn );
		else
			header( "Refresh:0" );
	}
	
	echo $error;
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
	$files = glob( $oglas->images . "*" );
	foreach( $files as $file ){
		unlink( $file );
	}
	rmdir( $oglas->images );
	
	$id = mysqli_real_escape_string( $conn, $id );
	$query = "DELETE FROM ads WHERE id='$id';";
	$conn->query( $query );
	header( "Location: moji_oglasi.php" );
	die();
}

function getCategory( $category ){
	global $conn;
	$query = "SELECT * FROM categories WHERE id='$category';";
	$res = $conn->query( $query );
	
	if( $cat = $res->fetch_object() )
		return $cat;
	
	return null;
}

function get_subCategories( $category ){
	global $conn;
	$query = "SELECT * FROM categories WHERE id='$category';";
	$res = $conn->query( $query );
	
	$sub_cats = array();
	$subs = "";
	if( $name = $res->fetch_object() )
		$subs = $name->sub_categories;
	
	if( !empty( $subs ) ){
		$ids = explode( ' ', $subs );
		foreach( $ids as $id )
			array_push( $sub_cats, $id );
	}
	
	return $sub_cats;
}

$categories = get_categories();
$subCats = array();
function get_SubCats( $cat ){
	global $subCats;
	foreach( get_subCategories( $cat ) as $category ){
		array_push( $subCats, getCategory( $category ) );
		get_SubCats( $category );
	}
}

function arrayFromString( $string ){
	$sub_cats = array();
	if( !empty( $string ) ){
		$ids = explode( ' ', $string );
		foreach( $ids as $id )
			array_push( $sub_cats, $id );
	}
	
	return $sub_cats;
}
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
		
		<label>Kategorije</label> </br>
		<?php foreach( $categories as $category ){
			if( $category->deep == 1 ){
				$kategorije = arrayFromString( $oglas->categories_ids ); ?>
				<input type="checkbox" name="<?php echo $category->id; ?>" value="true"
				<?php if( in_array( $category->id, $kategorije ) ) { echo "checked"; } ?> >
				<label><?php echo $category->name; ?></label> <br/>
					
				<?php get_SubCats( $category->id );
				
				foreach( $subCats as $cat ){ ?>
					<label><?php echo str_repeat( "&nbsp", $cat->deep * $cat->deep ); ?></label>
					<input type="checkbox" name="<?php echo $cat->id; ?>" value="true"
					<?php if( in_array( $cat->id, $kategorije ) ) { echo "checked"; } ?> >
					<label><?php echo $cat->name; ?></label> <br/>
		<?php } } } ?>
		
		
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