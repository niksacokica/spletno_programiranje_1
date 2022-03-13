<?php include_once( "glava.php" );

function cmp( $a, $b ){
    return $a->enddate < $b->enddate;
}

function get_oglasi(){
	global $conn;
	$query = "SELECT * FROM ads;";
	$res = $conn->query( $query );
	
	$oglasi = array();
	while( $oglas = $res->fetch_object() )
		array_push( $oglasi, $oglas );
	
	usort( $oglasi, "cmp" );
	
	return $oglasi;
}

function get_category( $category ){
	global $conn;
	$query = "SELECT * FROM categories WHERE id='$category';";
	$res = $conn->query( $query );
	
	if( $name = $res->fetch_object() )
		return $name->name;
	
	return "NULL";
}

$oglasi = get_oglasi();?>

<input href="index.php?id=10" type="checkbox" name="show_expired" value="true">
<label>Prikaži zapadle oglase</label><br>
	
<?php foreach( $oglasi as $oglas ){	
	if( date( "Y-m-d H:i:s" ) < $oglas->enddate ){?>
		<div class="oglas">
			<h4><?php echo $oglas->title;?></h4>
			<p>Kategorija: <?php echo get_category( $oglas->category_id );?></p>
			<p>Opis: </br><?php echo $oglas->description;?></p>
			<a href="oglas.php?id=<?php echo $oglas->id;?>"><button>Preberi več</button></a>
		</div>
		<hr/>
<?php }}

include_once( "noga.php" ); ?>