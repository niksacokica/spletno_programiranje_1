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

<style>
	body{
		margin:0;
		padding:0;
	}

	.glava{
		background-color: lightblue;
		padding: 1%;
		border-bottom: solid;
	}

	.links{
		float: right;
	}
	
	.title{
		font-size: 40px;
		height: 1%;
	}
	
	.register{
		text-align: center;
	}
	
	.register_table{
		margin-left: auto;
		margin-right: auto;
	}
	
	.prijava{
		text-align: center;
		margin-top: 11%;
	}
	
	.oglas{
		text-align: center;
	}
</style>

<body>
	<div class="glava">
		<nav>
			<div class="title"> <a>OGLASNIK</a> </div>
			<a href="index.php" class="links">Domov</a> </br>
			<?php
			if( isset( $_SESSION["USER_ID"] ) ){ ?>
				<a href="objavi.php" class="links">Objavi oglas</a> </br>
				<a href="moji_oglasi.php" class="links">Moji Oglasi</a> </br>
				<a href="odjava.php" class="links">Odjava</a>
				<?php
			}else{ ?>
				<a href="prijava.php" class="links">Prijava</a> </br>
				<a href="registracija.php" class="links">Registracija</a>
				<?php
			} ?>
		</nav>
		</br>
	</div> </br>