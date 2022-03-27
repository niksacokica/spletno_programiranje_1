<table class="table table-hover">
    <thead>
		<tr>
			<th>Uporabniško ime</th>
			<th>Elektronski naslov</th>
			<th>Urejanje</th>
		</tr>
    </thead>
    <tbody>

<?php foreach( $users as $user ){ ?>
		<tr>
		<td><?php echo $user["username"]; ?></td>
		<td><?php echo $user["email"]; ?></td>
		<td>
			<a href='?controller=uporabniki&action=profile&id=<?php echo $user["id"]; ?>'>Urejaj</a>
		</td>
		</tr>
<?php } ?>
	</tbody>
</table>