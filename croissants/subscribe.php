<?php
	session_start();
	require_once("fonction.php");
	isAuthorized();
	
	if(!isset($_GET['date']))
	{
		header("Location:mainFrame.php?page=liste&error=La date n'est pas indiquée (n00b)");
		return;
	}
	$date = $_GET['date'];
	
	/* Test si la date est bien un vendredi */
	$day = date("w", strtotime($date));
	if($day != 5)
	{
		header("Location:mainFrame.php?page=liste&error=La date indiquée n'est pas un vendredi (Mais t'auras le droit de manger un croissant quand même, hein)");
		return;
	}
		
	/* Test si la date est dans le passé */
	if(strtotime($date) < time() && !isAdmin())
	{
		header("Location:mainFrame.php?page=liste&error=La date indiquée appartient au passé, test le futur, ça ira mieux !");
		return;
	}
		
	/* Test si il faut enregistrer en tant que principal ou sauveteur */
	$historyType = 1;
	$sauveteur = "";
	if(isset($_GET['sauveteur']))
	{
		$sauveteur = "_SAUVETEUR";
		$historyType = 2;
	}
	
	/* Test si le vendredi est bien libre */
	if(!isFridayFree($date, "FK_USER_ID".$sauveteur))
	{
		header("Location:mainFrame.php?page=liste&error=Ce vendredi n'est pas libre");
		return;
	}
	
	/* Tout est bon, on peut enregistrer dans la BDD */
	require_once("connectdb.php");
	$user = $_SESSION['userId'];
	$date = date("Y-m-d", strtotime($date));
	
	/* Test si l'entrée existe déjà */
	$str = "select DATE from VENDREDI where DATE = '$date'";
	$req = mysql_query($str);
	$resultat = mysql_fetch_array($req);
	if($resultat['DATE'] != '')
		$str = "update VENDREDI set FK_USER_ID".$sauveteur." = '$user' where date = '$date'";
	else
		$str = "insert into VENDREDI (date, FK_USER_ID".$sauveteur.") values ('$date', '$user')";
	$req = mysql_query($str);
	
	/* Met le flux RSS à jour */
	$str = "select pk_vendredi_id from VENDREDI where date = '$date' and FK_USER_ID".$sauveteur." = '$user'";
	//echo $str;return;
	$req = mysql_query($str);
	$resultat = mysql_fetch_array($req);
	$vendredi_id = $resultat['pk_vendredi_id'];
	
	$datePoint = date("d.m.Y", strtotime($date));

	//updateXMLFile($user, $vendredi_id, "$user va apporter les croissants", "http://127.0.0.1/croissants/mainFrame.php?page=history&amp;date=$datePoint", "$user s\'est inscrit pour apporter les croissants le $datePoint", $date, 20); // TODO remettre et finir de coder
	
	/* Enregistre dans l'historique */
	$timeNow = date("Y-m-d H:i:s", time());
	$strHistory = "insert into HISTORY (DATE, FK_USER_ID, DATE_FROM_CHANGE, SUBSCRIBE) values ('$date', '$user', '$timeNow', '$historyType')";
	$reqHistory = mysql_query($strHistory);
	
	header("Location:mainFrame.php?page=liste&message=Merci de vous être inscrit");
?>
