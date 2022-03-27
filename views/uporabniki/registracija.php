<h2>Registracija</h2>
<form action="?controller=uporabniki&action=registriraj" method="post">
	<div class="form-group">
		<label>Obvezni podatki:</label> <br/>
		
		<label for="email">Elektronski naslov:</label>
		<input type="email" class="form-control" name="email" placeholder="ime.priimek@student.um.si" />
		
		<label for="username">Uporabniško ime:</label>
		<input type="text" class="form-control" name="username" placeholder="ime.priimek" />
		
		<label for="firstname">Ime:</label>
		<input type="text" class="form-control" name="firstname" placeholder="ime" />
		
		<label for="lastname">Priimek:</label>
		<input type="text" class="form-control" name="lastname" placeholder="priimek" />
		
		<label for="password">Geslo:</label>
		<input type="password" class="form-control" name="password" />
		
		<label for="repeat_password">Ponovi geslo:</label>
		<input type="password" class="form-control" name="repeat_password" />
		
		<br/> <label>Neobvezni podatki:</label> <br/>
		
		<label for="address">Naslov:</label>
		<input type="text" class="form-control" name="address" placeholder="ulica 0, mesto" />
		
		<label for="postalcode">Pošta:</label>
		<input type="number" class="form-control" min="0" max="1000000" value="0" name="postalcode" />
		
		<label for="phone">Telefonska številka:</label>
		<input type="tel" class="form-control" name="phone" value="0" />
		
		<label for="sex">Spol:</label>
		<input type="text" class="form-control" name="sex" />
		
		<label for="age">Starost:</label>
		<input type="number" class="form-control" min="0" max="100" value="0" name="age" />
		
		<br/> <input class="btn btn-primary" type="submit" name="register" value="Registriraj"/>
	</div>
</form>