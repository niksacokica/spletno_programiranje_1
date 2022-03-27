<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<div class="panel panel-default">
	<div class="panel-heading"><h2><?php echo $oglas->title; ?></h2><span class="label label-primary"><?php echo $oglas->postdate; ?></span></div>
	
	<?php $pics = glob( $oglas->images . "*" );
		echo '<img src="' . $oglas->images . $oglas->show_image . '" width="400"/>';
		foreach( $pics as $pic ){
			if( $pic != $oglas->images . $oglas->show_image )
				echo '<img src="' . $pic . '" width="400"/>';
	} ?>
	
	<div class="panel-body"><?php echo $oglas->description; ?></div> </br>
	
	<label>E-Objavil:&nbsp</label><label id="username"><?php echo $user["username"];?></label>
	<label>E-mail:&nbsp</label><label id="email"><?php echo $user["email"];?></label>
	
	<br/> <label>Dodaj komentar</label><br/><textarea id="comment" rows="1" cols="40"></textarea> <button onclick="add();">Dodaj</button>
</div> 

<script>
function add(){
	comment = { comment:$( "#comment" ).val(), email:$( "#email" ).text(), username:$( "#username" ).text() }
		$.post( "api2.php/comment", comment, function( data ){
			$( "#comment" ).append( JSON.stringify( data ) );
			$( "#email" ).append( JSON.stringify( data ) );
			$( "#username" ).append( JSON.stringify( data ) );
	});
}
</script>