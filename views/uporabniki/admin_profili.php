<table class="table table-hover">
    <thead>
		<tr>
			<th>Uporabniško ime</th>
			<th>Elektronski naslov</th>
			<th>Urejanje</th>
			<th>Brisanje</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach( $users as $user ){ ?>
			<form action="?controller=uporabniki&action=delete_user&id=<?php echo $user["id"]; ?>" method="post">				
				<tr>
				<td><?php echo $user["username"]; ?></td>
				<td><?php echo $user["email"]; ?></td>
				<td>
					<a href='?controller=uporabniki&action=profile&id=<?php echo $user["id"]; ?>'><i class="fas fa-edit"></i> Urejaj</a>
				</td>
				<td>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmModal">
						<i class="fas fa-trash-alt"></i>
					</button>
				</td>
				</tr>
				
				<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="confirmModalLabel" >Are you sure you want to delete this user?</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								<input class="text" name="id" value="<?php echo $user["id"]; ?>" />
								<input class="btn btn-primary" type="submit" name="del" value="Confirm"/>
								<a href='?controller=uporabniki&action=profile&id=<?php echo $user["id"]; ?>'>Urejaj</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		<?php } ?>
	</tbody>
</table>