<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<div class="panel panel-default" id="id" name="<?php echo $oglas->id; ?>">
	<div class="panel-heading"><h2><?php echo $oglas->title; ?></h2><span class="label label-primary"><?php echo $oglas->postdate; ?></span></div>
	
	<?php $pics = glob( $oglas->images . "*" );
		echo '<img src="' . $oglas->images . $oglas->show_image . '" width="400"/>';
		foreach( $pics as $pic ){
			if( $pic != $oglas->images . $oglas->show_image )
				echo '<img src="' . $pic . '" width="400"/>';
	} ?>
	
	<div class="panel-body"><?php echo $oglas->description; ?></div> </br>
	
	<label>E-Objavil:&nbsp</label><label id="username"><?php echo $user["username"];?></label> </br>
	<label>E-mail:&nbsp</label><label id="email"><?php echo $user["email"];?></label>
	</br> <label>Dodaj komentar</label><br/>
	<?php if( !isset( $_SESSION["USER_ID"] ) ){ ?>
		<label for="email">Elektronski naslov:</label>
		<input type="email" class="form-control" id="emailGuest" placeholder="ime.priimek@student.um.si" />
		
		<label for="username">Uporabniško ime:</label>
		<input type="text" class="form-control" id="usernameGuest" placeholder="ime.priimek" /> </br>
		
		<textarea id="commentGuest" rows="1" cols="40"></textarea> <button onclick="addNoUser();">Dodaj</button>
	<?php }else{ ?>
		<textarea id="comment" rows="1" cols="40"></textarea> <button onclick="addUser();">Dodaj</button>
	<?php } ?>
	</br> <label>Komentarji</label>
	<?php $comments = json_decode( file_get_contents("http://localhost/api2.php/comment/" . $oglas->id ) );
		foreach( $comments as $comment ){
			$country = json_decode( file_get_contents("http://ip-api.com/json/" . $comment->ip ) ); ?> 
			<p><?php echo $comment->username; ?> ( <?php echo $country->country; ?> ):<?php echo " " . $comment->content; ?></p>
	<?php } ?>
</div> 

<script>
	function addUser(){		
		$.getJSON( "http://ip-api.com/json/", function(data){
			comment = { comment:$( "#comment" ).val(), email:$( "#email" ).text(), username:$( "#username" ).text(), id:$( "#id" ).attr( "name" ), ip:data["query"] }
				$.post( "api2.php/comment", comment, function( data ){ } );
		} );
	}
	
	function addNoUser(){
		fetch( "http://apilayer.net/api/check?access_key=14773d37eab1edfe208f6a1941a30b91&email=" + $( "#emailGuest" ).val() + "&smtp=1&format=1" )
		  .then( ( response ) => {
			return response.json();
		  } ).then( ( json ) => {
			if( json["smtp_check"] && $( "#usernameGuest" ).val().length > 0 ){
				comment = { comment:$( "#commentGuest" ).val(), email:$( "#emailGuest" ).val(), username:$( "#usernameGuest" ).val(), id:$( "#id" ).attr( "name" ) }
					$.post( "api2.php/comment", comment, function( data ){} );
		  } } );
	}
</script>