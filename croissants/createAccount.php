<?php
	session_start();
		
	require_once("fonction.php");
	htmlHeader();
?>
	<form action="creatingAccount.php" method="POST">
	<table border = "0">
		<tr>
			<td>Visa *</td>
			<td><input type="text" size="40" maxlength="40" name="visa"
				<?php
					if(isset($_GET["visa"]))
						echo "value = \"".$_GET["visa"]."\"";
				?>
			></td>
		</tr>
		<tr>
			<td>Nom</td>
			<td><input type="text" size="40" maxlength="40" name="nom"
				<?php
					if(isset($_GET["nom"]))
						echo "value = \"".$_GET["nom"]."\"";
				?>
			></td>
		</tr>
		<tr>
			<td>Prénom</td>
			<td><input type="text" size="40" maxlength="40" name="prenom"
				<?php
					if(isset($_GET["prenom"]))
						echo "value = \"".$_GET["prenom"]."\"";
				?>

			></td>
		</tr>
		<tr>
			<td>Mail *</td>
			<td><input type="text" size="40" maxlength="40" name="mail"
				<?php
					if(isset($_GET["mail"]))
						echo "value = \"".$_GET["mail"]."\"";
				?>
			></td>
		</tr>
		<tr>
			<td>Password *</td>
			<td><input type="password" size="40" maxlength="40" name="password"></td>
		</tr>
		<tr>
			<td>Répeter password *</td>
			<td><input type="password" size="40" maxlength="40" name="passwordRepeat"></td>
		</tr>
		<tr>
			<td></td><td>
			<input type = "submit" name = "submit" value = "Créer le compte"></td>
		</tr>
	</table>
	</form>
<?php
	htmlFooter();
?>