<?php
	session_start();
	
	if( isset( $_SESSION['LAST_ACTIVITY'] ) && time() - $_SESSION['LAST_ACTIVITY'] < 1800 )
		session_regenerate_id( true );
	$_SESSION['LAST_ACTIVITY'] = time();
	
	$conn = new mysqli( 'localhost', 'root', '', 'vaja1' );
	$conn->set_charset( "UTF8" );
?>
<html>
<head>
	<title>Vaja 1</title>
</head>
<body>
	<h1>Oglasnik</h1>
	<nav>
		<ul>
			<li><a href="index.php">Domov</a></li>
			<?php
			if( isset( $_SESSION["USER_ID"] ) ){
				?>
				<li><a href="objavi.php">Objavi oglas</a></li>
				<li><a href="odjava.php">Odjava</a></li>
				<?php
			}else{
				?>
				<li><a href="prijava.php">Prijava</a></li>
				<li><a href="registracija.php">Registracija</a></li>
				<?php
			}
			?>
		</ul>
	</nav>
	<hr/>