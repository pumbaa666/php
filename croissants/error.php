<?php
	session_start();
	require_once("fonction.php");
	htmlHeader();
	if(isset($_GET['type']))
	{
		if($_GET['type'] == "autorisation")
		{
			echo "Vous n'�tes pas autoris� � afficher cette page. Loggez vous d'abord.<br/>";
			echo "<a href = 'index.php'>Login</a>";
		}
		else if($_GET['type'] == "autorisationAdmin")
		{
			echo "Vous n'�tes pas autoris� � afficher cette page. Loggez vous d'abord en <b>administrateur</b>.<br>";
			echo "<a href = 'index.php'>Login</a>";
		}
		else if($_GET['type'] == "character")
		{
			echo "Erreur, les caract�res suivants sont interdits ";
			if(isset($_GET['field']))
				echo "dans le ".$_GET['field'];
			echo " : ";
			$unauthorizedChar = getUnauthorizedChar();
			foreach($unauthorizedChar as $badChar)
				echo "$badChar ";
			if(isset($_GET['maxLength']))
				echo "<br/>La taille maximale est de ".$_GET['maxLength']." carat�res";
			if(isset($_GET['redirect']))
				echo "<br/><a href = '$_GET[redirect]'>retour</a>";
		}
		else if($_GET['type'] == "visa")
		{
			echo "Votre visa doit contenir 3 caract�res";
			if(isset($_GET['redirect']))
				echo "<br/><a href = '$_GET[redirect]'>retour</a>";
		}
		else if($_GET['type'] == "duplicateUserDB")
		{
			echo "Ce visa est d�j� utilis�";
			if(isset($_GET['redirect']))
				echo "<br/><a href = '$_GET[redirect]'>retour</a>";
		}
		else if($_GET['type'] == "errorDB")
		{
			echo "Une erreur inconnue est apparue dans la base de donn�e, contactez l'administrateur loic.correvon@elca.ch";
			if(isset($_GET['redirect']))
				echo "<br/><a href = '$_GET[redirect]'>retour</a>";
		}
		else if($_GET['type'] == "email")
		{
			echo "Votre email doit �tre de la forme user@fournisseur.ext";
			if(isset($_GET['redirect']))
				echo "<br/><a href = '$_GET[redirect]'>retour</a>";
		}
		else if($_GET['type'] == "passwordMatch")
		{
			echo "La r�p�tition du password ne correspond pas.";
			if(isset($_GET['redirect']))
				echo "<br/><a href = '$_GET[redirect]'>retour</a>";
		}
		else if($_GET['type'] == "passwordLength")
		{
			echo "Le password doit contenir au moins 6 caract�res";
			if(isset($_GET['redirect']))
				echo "<br/><a href = '$_GET[redirect]'>retour</a>";
		}
		else if($_GET['type'] == "password1")
		{
			echo "Le mot de passe ne doit pas �tre password1";
			if(isset($_GET['redirect']))
				echo "<br/><a href = '$_GET[redirect]'>retour</a>";
		}
		else if($_GET['type'] == "passwordLenght")
		{
			echo "Le mot de passe doit faire au moins 6 caract�res";
			if(isset($_GET['redirect']))
				echo "<br/><a href = '$_GET[redirect]'>retour</a>";
		}
	}
	else
	{
		echo "Erreur inconnue<br/>";
		echo "<a href = 'index.php'>Login</a>";
	}
	htmlFooter();
?>