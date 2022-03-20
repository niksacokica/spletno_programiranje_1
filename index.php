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

function get_some_oglasi( $iskanje ){
	global $conn;
	$query = "SELECT * FROM ads WHERE title LIKE '$iskanje%' OR description LIKE '$iskanje%';";
	$res = $conn->query( $query );
	
	$oglasi = array();
	while( $oglas = $res->fetch_object() )
		array_push( $oglasi, $oglas );
	
	usort( $oglasi, "cmp" );
	
	return $oglasi;
}

$kategorije_filter = array();
function get_categories(){
	global $conn;
	global $kategorije_filter;
	$query = "SELECT * FROM categories;";
	$res = $conn->query( $query );
	
	$categories = array();
	while( $category = $res->fetch_object() ){
		array_push( $categories, $category );
		array_push( $kategorije_filter, $category->id );
	}
	
	return $categories;
}

function get_category( $category ){
	global $conn;
	$query = "SELECT * FROM categories WHERE id='$category';";
	$res = $conn->query( $query );
	
	if( $name = $res->fetch_object() )
		return $name->name;
	
	return "NULL";
}

$oglasi = get_oglasi();
$kategorije = get_categories();

$prikazi_zapadle = False;
if( isset( $_POST["filtriraj"] ) ){
	if( isset( $_POST["prikazi_zapadle"] ) ){
		$prikazi_zapadle = $_POST["prikazi_zapadle"];
	}
	
	$kategorije_filter = array();
	foreach( $_POST as $key => $val ){
		if( is_int( $key ) ){
			array_push( $kategorije_filter, $key );
		}
	}
}else if( isset( $_POST["isci"] ) ){
	if( isset( $_POST["prikazi_zapadle"] ) ){
		$prikazi_zapadle = $_POST["prikazi_zapadle"];
	}
	
	$kategorije_filter = array();
	foreach( $_POST as $key => $val ){
		if( is_int( $key ) ){
			array_push( $kategorije_filter, $key );
		}
	}
	
	if( !empty( $_POST["iskanje"] ) ){
		$oglasi = get_some_oglasi( $_POST["iskanje"] );
	}
}?>
<table style="width: 100%;">
<tr> <th style="width: 20%; vertical-align: top;">
<form action="index.php" method="POST" enctype="multipart/form-data">
	<input type="search" name="iskanje">
	<input type="submit" name="isci" value="isci" /> </br> <hr/>
	
	<?php foreach( $kategorije as $kategorija ){ ?>
			<input type="checkbox" name="<?php echo $kategorija->id; ?>" value="true"
			<?php if( in_array( $kategorija->id, $kategorije_filter ) ) { echo "checked"; } ?> >
			<label><?php echo $kategorija->name; ?></label> </br>
	<?php } ?>
	
	</br> </br>
	<input type="checkbox" name="prikazi_zapadle" value="true"
	<?php if( $prikazi_zapadle ){ echo "checked"; } ?> >
	<label>Prikaži zapadle oglase</label> </br> </br>
	<input type="submit" name="filtriraj" value="Filtriraj" />
	</th><th>
</form>
	
<?php foreach( $oglasi as $oglas ){	
	$ts = new DateTime( "now", new DateTimeZone( "Europe/Ljubljana" ) );
	$ts->setTimestamp( time() );
	if( ( ( $ts->format( 'Y-m-d H:i:s' ) < $oglas->enddate ) || ( $ts->format( 'Y-m-d H:i:s' ) > $oglas->enddate && $prikazi_zapadle ) ) && in_array( $oglas->category_id, $kategorije_filter ) ){?>
		<div class="oglas" class="posts">
			<h4><?php echo $oglas->title;?></h4>
			<label>Kategorija: <?php echo get_category( $oglas->category_id ); ?> </label> </br> </br>
			<img src="<?php echo $oglas->images . $oglas->show_image;?>" width="400"/>
			</br> </br> <label>Pogledov: <?php echo $oglas->views;?> </label> </br> </br>
			<a href="oglas.php?id=<?php echo $oglas->id;?>"><button>Preberi več</button></a>
			<hr/>
		</div>
<?php }} ?>

</th> </tr>
</table>

<?php include_once( "noga.php" ); ?>