<?php
	if(!session_id())
		session_start();
	require_once("connectdb.php");
	require_once("fonction.php");
	htmlHeader();
	
	$mail = '';
	if(isset($_SESSION['userId']))
	{
		$user = $_SESSION['userId'];
		$str = "select EMAIL from USER where PK_USER_ID = '$user'";
		$req = mysql_query($str);
		$resultat = mysql_fetch_array($req);
		$mail = $resultat['EMAIL'];
	}
	
	echo "<font color = 'red'>! Ceci va vous renvoyer un nouveau password !</font><br/>";
	echo "<form method = 'post' action = 'resetingPassword.php'>";
	echo "Entrez votre adresse email : <input type = 'text' name = 'mail' value = '$mail' size = '40' maxlength = '40'/>";
	echo "<input type = 'submit' value = 'Envoyer le mot de passe' />";
	echo "</form>";

	htmlFooter();
?>