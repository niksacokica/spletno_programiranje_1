<p>Dodaj nov oglas</p>
<form action="?controller=oglasi&action=shrani" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label for="naslov">Naslov:</label>
		<input type="text" class="form-control" name="title" placeholder="Naslov" />
		
		<label for="vsebina">Vsebina:</label>
		<textarea name="description" class="form-control" placeholder="Dodaj vsebino..."></textarea>
		
		<label>Predstavitvena slika </label>
		<input type="file" class="form-control" name="show" >
		
		<label>Slike </label>
		<input type="file" class="form-control" name="images[]" multiple > </br>
		
		<label>Kategorije</label> </br>
		<?php foreach( $categories as $category ){
			if( $category["deep"] == 1 ){ ?>
				<input type="checkbox" name="<?php echo $category["id"]; ?>" value="true" >
				<label><?php echo $category["name"]; ?></label> <br/>
					
				<?php $subCats = Oglas::get_SubCats( $category["id"] );
				foreach( $subCats as $cat ){ ?>
					<label><?php echo str_repeat( "&nbsp", $cat["deep"] * $cat["deep"] ); ?></label>
					<input type="checkbox" name="<?php echo $cat["id"]; ?>" value="true" >
					<label><?php echo $cat["name"]; ?></label> <br/>
		<?php } } } ?>
		
		<br/> <input class="btn btn-primary" type="submit" name="Dodaj" value="Dodaj"/>
	</div>
</form>