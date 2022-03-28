<html>
	<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
    
    <body>
		<div class="container text-center">
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<a class="navbar-brand">Oglasnik</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			  
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<?php if( isset( $_SESSION["USER_ID"] ) ){ ?>
							<li class="nav-item <?php if( $controller != "oglasi" && $controller != "uporabniki" ) echo( "active" );?>">
								<a class="nav-link" href="?controller=oglasi&action=moji">Domov </a>
							</li>
						<?php } ?>
					
						<li class="nav-item <?php if( $controller == "oglasi" ) echo( "active" );?>">
							<a class="nav-link" href="?controller=oglasi&action=index">Oglasi</a>
						</li>
					</ul>
					
					<ul class="navbar-nav ml-auto">
						<?php if( !isset( $_SESSION["USER_ID"] ) ){ ?>
							<li class="nav-item">
								<a class="nav-link" href="?controller=uporabniki&action=registracija"> <i class="fas fa-user"> </i> Registracija</a>
							</li>
							
							<li class="nav-item">
								<a class="nav-link" href="?controller=uporabniki&action=prijava"><i class="fas fa-sign-in-alt"></i> Prijava</a>
							</li>
						<?php }else{
								require_once( "models/uporabniki.php" );
								if( Uporabnik::isAdmin( $_SESSION["USER_ID"] ) ){ ?>
									<li class="nav-item">
										<a class="nav-link" href="?controller=uporabniki&action=admin"><i class="fas fa-lock"></i></i> Admin</a>
									</li>
								<?php } ?>
							<li class="nav-item">
								<a class="nav-link" href="?controller=uporabniki&action=profile&id=<?php echo $_SESSION["USER_ID"]; ?>"><i class="fas fa-user"></i> Profil</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="?controller=uporabniki&action=odjava"><i class="fas fa-sign-out-alt"></i> Odjava</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</nav>
			
			<div class="row content">
				<div class="col-sm">
					<h3>Latest comments</h3>
					<?php $comments = json_decode( file_get_contents("http://localhost/api2.php/comment/top5" ) );
						foreach( $comments as $comment ){
							$country = json_decode( file_get_contents("http://ip-api.com/json/" . $comment->ip ) ); ?> 
							<a class="card" href="?controller=oglasi&action=prikazi&id=<?php echo $comment->ad_id; ?>"><?php echo $comment->username; ?> ( <?php echo $country->country; ?> ):<?php echo " " . $comment->content; ?></a> </br>
					<?php } ?>
				</div>
			
				<div class="col-sm-8 text-left"> 
					<?php require_once( "routes.php" ); ?>
				</div>
			</div>
		</div>
			<body>
				</html>