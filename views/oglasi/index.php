<?php if( isset( $_SESSION["USER_ID"] ) ){ ?>
	<a href="?controller=oglasi&action=dodaj" class="btn btn-primary">Dodaj <i class="fas fa-plus"></i></a>
<?php } ?>

<table class="table table-hover">
    <thead>
		<tr>
			<th>Naslov</th>
			<th>Vsebina</th>
			<th>Datum Objave</th>
		</tr>
    </thead>
    <tbody>

<?php foreach( $oglasi as $oglas ){ ?>
		<tr>
		<td><?php echo $oglas->title; ?></td>
  
		<td>
			<a href='?controller=oglasi&action=prikazi&id=<?php echo $oglas->id; ?>'>Poglej vsebino</a>
		</td>
		<td><?php echo $oglas->postdate; ?></td>
		</tr>
<?php } ?>
	</tbody>
</table>