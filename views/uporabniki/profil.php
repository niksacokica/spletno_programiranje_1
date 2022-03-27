<h2>Profil</h2>
<form action="?controller=uporabniki&action=edit_user&id=<?php echo $user["id"];?>" method="post">
	<div class="form-group">		
		<label for="email">Elektronski naslov:</label>
		<input type="email" class="form-control" name="email" value="<?php echo $user["email"]; ?>" />
		
		<label for="username">Uporabniško ime:</label>
		<input type="text" class="form-control" name="username" value="<?php echo $user["username"]; ?>" />
		
		<label for="firstname">Ime:</label>
		<input type="text" class="form-control" name="firstname" value="<?php echo $user["firstname"]; ?>" />
		
		<label for="lastname">Priimek:</label>
		<input type="text" class="form-control" name="lastname" value="<?php echo $user["lastname"]; ?>" />
		
		<label for="password">Geslo:</label>
		<input type="password" class="form-control" name="password" value="<?php echo $user["password"]; ?>" />
		
		<br/><br/>
		
		<label for="address">Naslov:</label>
		<input type="text" class="form-control" name="address" value="<?php echo $user["address"]; ?>" />
		
		<label for="postalcode">Pošta:</label>
		<input type="number" class="form-control" min="0" max="1000000" name="postalcode" value="<?php echo $user["postalcode"]; ?>" />
		
		<label for="phone">Telefonska številka:</label>
		<input type="tel" class="form-control" name="phone" value="<?php echo $user["phone"]; ?>" />
		
		<label for="sex">Spol:</label>
		<input type="text" class="form-control" name="sex" value="<?php echo $user["sex"]; ?>" />
		
		<label for="age">Starost:</label>
		<input type="number" class="form-control" min="0" max="100" name="age" value="<?php echo $user["age"]; ?>" />
		
		<br/> <input class="btn btn-primary" type="submit" name="save" value="Shrani spremembe"/>
	</div>
</form>