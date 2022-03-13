<?php
include_once('glava.php');

// Funkcija prebere oglase iz baze in vrne polje objektov
function get_oglasi(){
	global $conn;
	$query = "SELECT * FROM ads;";
	$res = $conn->query($query);
	$oglasi = array();
	while($oglas = $res->fetch_object()){
		array_push($oglasi, $oglas);
	}
	return $oglasi;
}

//Preberi oglase iz baze
$oglasi = get_oglasi();

//Izpiši oglase
//Doda link z GET parametrom id na oglasi.php za gumb 'Preberi več'
foreach($oglasi as $oglas){
	?>
	<div class="oglas">
		<h4><?php echo $oglas->title;?></h4>
		<p><?php echo $oglas->description;?></p>
		<a href="oglas.php?id=<?php echo $oglas->id;?>"><button>Preberi več</button></a>
	</div>
	<hr/>
	<?php
}


include_once('noga.php');
?>