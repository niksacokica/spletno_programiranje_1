<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<form action="?controller=oglasi&action=spremeni&id=<?php echo $oglas->id;?>" method="POST" enctype="multipart/form-data">
	<label id="user" name="<?php $_SESSION["USER_ID"] ?>">Naslov</label><input type="text" name="title" value="<?php echo $oglas->title;?>" /> <br/>
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
		if( $category["deep"] == 1 ){
			$kategorije = Oglas::arrayFromString( $oglas->categories_ids ); ?>
			<input type="checkbox" name="<?php echo $category["id"]; ?>" value="true"
			<?php if( in_array( $category["id"], $kategorije ) ) { echo "checked"; } ?> >
			<label><?php echo $category["name"]; ?></label> <br/>
				
			<?php $subCats = Oglas::get_SubCats( $category["id"] );
			
			foreach( $subCats as $cat ){ ?>
				<label><?php echo str_repeat( "&nbsp", $cat["deep"] * $cat["deep"] ); ?></label>
				<input type="checkbox" name="<?php echo $cat["id"]; ?>" value="true"
				<?php if( in_array( $cat["id"], $kategorije ) ) { echo "checked"; } ?> >
				<label><?php echo $cat["name"]; ?></label> <br/>
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
	<?php $ts = new DateTime( "now", new DateTimeZone( "Europe/Ljubljana" ) );
		$ts->setTimestamp( time() );
		if( $ts->format( 'Y-m-d H:i:s' ) > $oglas->enddate ){ ?>
		<input type="submit" name="podaljsaj" value="Podaljšaj" />
	<?php } ?>
	<input type="submit" name="izbrisi" value="Izbriši" />
		
	</br> </br> <label>Komentarji</label> </br>
	<?php $comments = json_decode( file_get_contents("http://localhost/api2.php/comment/" . $oglas->id ) );
		foreach( $comments as $comment ){ ?> 
			<div style="display: inline-table;">
				<label style="display:table-cell;" id="id" name="<?php echo $comment->id; ?>"><?php echo $comment->username; ?>:<?php echo " " . $comment->content; ?></label>
				<a class="nav-link" onclick="del()"><i class="fas fa-trash-alt"></i></a>
			</div> </br>
	<?php } ?>
</form>

<script>
	function del(){
		comment = { id:$( "#id" ).attr( "name" ), user:$( "#user" ).attr( "name" ) }
			$.ajax( { method:"DELETE", url:"http://localhost/api2.php/comment/"+$("#id").attr( "name" ), data:JSON.stringify( comment ), success:function( data ){} } );
	}
</script>