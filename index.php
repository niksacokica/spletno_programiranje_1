<?php include_once( "glava.php" );

function get_oglasi(){
	global $conn;
	$query = "SELECT * FROM ads;";
	$res = $conn->query( $query );
	
	$oglasi = array();
	while( $oglas = $res->fetch_object() )
		array_push( $oglasi, $oglas );
	
	return $oglasi;
}

$oglasi = get_oglasi();
foreach( $oglasi as $oglas ){ ?>
	<div class="oglas">
		<h4><?php echo $oglas->title;?></h4>
		<p><?php echo $oglas->description;?></p>
		<a href="oglas.php?id=<?php echo $oglas->id;?>"><button>Preberi več</button></a>
	</div>
	<hr/>
<?php }


include_once( "noga.php" ); ?>