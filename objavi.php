<?php include_once( "glava.php" );

function reArrayFiles( $file_post ){
	$file_ary = array();
    for( $i=0; $i<count( $file_post["name"] ); $i++ )
        foreach( array_keys( $file_post ) as $key )
            $file_ary[$i][$key] = $file_post[$key][$i];

    return $file_ary;
}

function publish( $title, $desc, $imgs, $show, $cat ){
	$allowed = array( "image/jpeg", "image/gif", "image/png" );
	if( !in_array( $show["type"], $allowed ) )
		return false;
	
	global $conn;
	$title = mysqli_real_escape_string( $conn, $title );
	$desc = mysqli_real_escape_string( $conn, $desc );
	$user_id = $_SESSION["USER_ID"];
	
	$ts = new DateTime( "now", new DateTimeZone( "Europe/Ljubljana" ) );
	$ts->setTimestamp( time() );
	$postdate = $ts->format( 'Y-m-d H:i:s' );
	
	$pot = "./slike/" . $user_id . "/" . $ts->format( 'Y-m-d_H-i-s' ) . "/";
	mkdir( $pot, 0777, true );
	
	$show_pic = $show["name"];
	$images = reArrayFiles( $imgs );
	move_uploaded_file( $show["tmp_name"], $pot . $show_pic );
	foreach( $images as $img ){
		if( $img["name"] != $show_pic ){
			if( !in_array( $img["type"], $allowed ) ){
				$files = glob( $pot . "*" );
				foreach( $files as $file ){
					unlink( $file );
				}
				rmdir( $pot );
				return false;
			}
			move_uploaded_file( $img["tmp_name"], $pot . $img["name"] );
		}
	}
	
	$query = "INSERT INTO ads (title, description, user_id, images, show_image, postdate, enddate,
				category_id, views) VALUES('$title', '$desc', '$user_id', '$pot', '$show_pic',
				'$postdate', DATE_ADD('$postdate', INTERVAL 30 DAY), '$cat', 0);";
				
	if( $conn->query( $query ) )
		return true;
	
	echo mysqli_error( $conn );
	return false;
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
		$_FILES["show"]["error"] != 0 || $_FILES["images"]["error"][0] != 0 )
		$error = "napačen vnos.";
	else if( publish( $_POST["title"], $_POST["description"], $_FILES["images"], $_FILES["show"], $_POST["category"] ) ){
		header( "Location: index.php" );
		die();
	}else
		$error = "Prišlo je do napake pri objavi oglasa.";
}

function get_category( $category ){
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

$subCats = array();
array_push( $subCats, 1 );
function get_SubCats( $cat ){
	foreach( get_subCategories( $cat ) as $category ){
		array_push( $subCats, getCategory( $category ) );
		get_SubCats( $category );
	}
	print_r( $subCats );
}

?>
	<div style="padding: 1%;">
		<h2>Objavi oglas</h2>
		<form action="objavi.php" method="POST" enctype="multipart/form-data">
			<label>Naslov </label><input type="text" name="title" > <br/>
			<label>Opis</label><br/><textarea name="description" rows="10" cols="50"></textarea> <br/>
			<label>Predstavitvena slika </label><input type="file" name="show" > <br/>
			<label>Slike </label><input type="file" name="images[]" multiple > <br/>
			<label>Kategorija</label> <select name="category">
				<?php foreach( $categories as $category ){
					if( $category->deep == 1 ){
						print_r( $subCats );
						get_SubCats( $category->id );
						print_r( $subCats ); ?>
						<option value=<?php echo $category->id;?>><?php echo "&nbsp" . $category->name;?></option>
						<?php foreach( $subCats as $cat ){ ?>
							<option value=<?php echo $cat->id;?>><?php echo "&nbsp" . $cat->name;?></option>
						<?php 
				} } } ?>
			</select>
			<input type="submit" name="poslji" value="Objavi" /> <br/>
			<label><?php echo $error;?></label>
		</form>
	</div>
<?php include_once( "noga.php" ); ?>