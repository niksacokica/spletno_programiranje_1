<?php include_once( "glava.php" );

function cmp( $a, $b ){
    return $a->enddate < $b->enddate;
}

function get_oglasi(){
	global $conn;
	$user = $_SESSION['USER_ID'];
	$query = "SELECT * FROM ads WHERE user_id='$user';";
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

function arrayFromString( $string ){
	$sub_cats = array();
	if( !empty( $string ) ){
		$ids = explode( ' ', $string );
		foreach( $ids as $id )
			array_push( $sub_cats, $id );
	}
	
	return $sub_cats;
}

$oglasi = get_oglasi();
foreach( $oglasi as $oglas ){ ?>
	<div class="oglas">
		<h4><?php echo $oglas->title;?></h4>
		<p><?php echo $oglas->description;?></p>
		<img src="<?php echo $oglas->images . $oglas->show_image;?>" width="400"/>
		<p>Kategorije: </p>
		<?php
			$cats = arrayFromString( $oglas->categories_ids );
			foreach( $cats as $cat ){ ?>
				<p><?php echo get_category( $cat ); ?></p>
		<?php } ?>
		</br> <p>Datum objave: <?php echo $oglas->postdate;?></p>
		<p>Datum zapadlosti: <?php echo $oglas->enddate;?></p>
		<a href="uredi_oglas.php?id=<?php echo $oglas->id;?>"><button>Uredi</button></a>
	</div>
	<hr/>
<?php }

include_once( "noga.php" ); ?>