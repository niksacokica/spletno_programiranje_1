<?php include_once('glava.php');

function get_ad( $id ){
	global $conn;
	$id = mysqli_real_escape_string( $conn, $id );
	$query = "SELECT ads.*, users.username FROM ads LEFT JOIN users ON users.id = ads.user_id WHERE ads.id = $id;";
	$res = $conn->query( $query );
	if( $obj = $res->fetch_object() )
		return $obj;
	
	return null;
}

function get_category( $category ){
	global $conn;
	$query = "SELECT * FROM categories WHERE id='$category';";
	$res = $conn->query( $query );
	
	if( $name = $res->fetch_object() )
		return $name->name;
	
	return "NULL";
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

$img_data = base64_encode( $oglas->image );
?>

	<div class="oglas">
		<h4><?php echo $oglas->title;?></h4>
		<p><?php echo $oglas->description;?></p>
		<img src="data:image/jpg;base64, <?php echo $img_data;?>" width="400"/>
		<p>Objavil: <?php echo $oglas->username;?></p>
		<p>Kategorija: <?php echo get_category( $oglas->category_id );?></p>
		<p>Datum objave: <?php echo $oglas->postdate;?></p>
		<a href="index.php"><button>Nazaj</button></a>
	</div>
	<hr/>
	
<?php include_once( "noga.php" ); ?>