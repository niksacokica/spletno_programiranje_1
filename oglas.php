<?php include_once('glava.php');

function get_ad( $id ){
	global $conn;
	$id = mysqli_real_escape_string( $conn, $id );
	$query = "SELECT ads.*, users.username, users.email FROM ads LEFT JOIN users ON users.id = ads.user_id WHERE ads.id = $id;";
	$res = $conn->query( $query );
	if( $obj = $res->fetch_object() )
		return $obj;
	
	return null;
}

function inc_views( $id ){
	global $conn;
	$query = "UPDATE ads SET views = views + 1 WHERE id = $id;";
	if( !$conn->query( $query ) )
		echo mysqli_error( $conn );
}

if( !isset( $_GET["id"] ) ){
	echo "Manjkajoči parametri.";
	die();
}

inc_views( $_GET["id"] );

$id = $_GET["id"];
$oglas = get_ad( $id );
if( $oglas == null ){
	echo "Oglas ne obstaja.";
	die();
}

?>

	<div class="oglas">
		<h4><?php echo $oglas->title;?></h4>
		<p><?php echo $oglas->description;?></p>
		<?php $pics = glob( $oglas->images . "*" );
		echo '<img src="' . $oglas->images . $oglas->show_image . '" width="400"/>';
		foreach( $pics as $pic ){
			if( $pic != $oglas->images . $oglas->show_image )
				echo '<img src="' . $pic . '" width="400"/>';
		} ?>
		<p>Objavil: <?php echo $oglas->username;?></p>
		<p>E-mail: <?php echo $oglas->email;?></p>
		<a href="index.php"><button>Nazaj</button></a>
	</div>
	<hr/>
	
<?php include_once( "noga.php" ); ?>