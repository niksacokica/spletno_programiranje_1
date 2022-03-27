<?php foreach( $oglasi as $oglas ){ ?>
	<div class="oglas">
		<h4><?php echo $oglas->title;?></h4>
		<p><?php echo $oglas->description;?></p>
		<img src="<?php echo $oglas->images . $oglas->show_image;?>" width="400"/>
		<p>Kategorije: </p>
		<?php
			/*$cats = arrayFromString( $oglas->categories_ids );
			foreach( $cats as $cat ){ ?>
				<p><?php echo get_category( $cat ); ?></p>
		<?php }*/ ?>
		</br> <p>Datum objave: <?php echo $oglas->postdate;?></p>
		<p>Datum zapadlosti: <?php echo $oglas->enddate;?></p>
		<a href="?controller=oglasi&action=uredi&id=<?php echo $oglas->id; ?>"><button>Uredi</button></a>
	</div>
	<hr/>
<?php } ?>