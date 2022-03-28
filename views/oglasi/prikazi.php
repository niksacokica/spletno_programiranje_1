<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<div class="panel panel-default" id="id" name="<?php echo $oglas->id ?>">
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
	
	</br> <label>Dodaj komentar</label><br/><textarea id="comment" rows="1" cols="40"></textarea> <button onclick="add();">Dodaj</button>
	</br> <label>Komentarji</label> <?php $comments json_decode( file_get_contents("http://localhost/api2.php/comment/" . $oglas->id ) ); ?>
</div> 

<script>
function show(data) {
    let tab = 
        "<tr> <th>Name</th> <th>Office</th> <th>Position</th> <th>Salary</th> </tr>";
    
    // Loop to access all rows 
    for (let r of data.list) {
        tab += "<tr> <td>${r.name} </td> <td>${r.office}</td> <td>${r.position}</td> <td>${r.salary}</td> </tr>";
    }
    // Setting innerHTML as tab variable
    document.getElementById("employees").innerHTML = tab;
}

async function getAll(){
	var response = await fetch( "https://employeedetails.free.beeceptor.com/my/api/path" );
    
    var data = await response.json();
    console.log( data );
	
    show( data );
}

getAll()

function add(){
	comment = { comment:$( "#comment" ).val(), email:$( "#email" ).text(), username:$( "#username" ).text(), id:$( "#id" ).attr( "name" ) }
		$.post( "api2.php/comment", comment, function( data ){
			$( "#comment" ).append( JSON.stringify( data ) );
			$( "#email" ).append( JSON.stringify( data ) );
			$( "#username" ).append( JSON.stringify( data ) );
			$( "#id" ).append( JSON.stringify( data ) );
	});
}
</script>