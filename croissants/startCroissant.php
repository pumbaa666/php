<?php
echo "En cours de réalisation<br/><a href = 'mainFrame.php?page=liste'>Retour</a>";
/*
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	require_once("connectdb.php");
	
	$user = $_SESSION['userId'];
	$date = date("Y-m-d");

	$str = "select PK_VENDREDI_ID, FK_USER_ID, FK_USER_ID_SAUVETEUR, IS_STARTED from VENDREDI where date = '$date'";
	//echo $str;return;
	$req = mysql_query($str);
	$resultat = mysql_fetch_array($req);
	
	if($resultat['IS_STARTED'] == '1')
	{
		header("Location:mainFrame.php?page=liste&error=L'opération croissant est déjà commencée, fonce à la cafète au lieu de trainer sur le ouaib !");
		return;
	}
	
	if($resultat['FK_USER_ID'] == $user || $resultat['FK_USER_ID_SAUVETEUR'] == $user)
	{
		$str = "select PK_VENDREDI_ID from VENDREDI where DATE = '$date'";
		$req = mysql_query($str);
		$resultat = mysql_fetch_array($req);
		$vendredi_id = $resultat['PK_VENDREDI_ID'];
	
		updateXMLFile($user, $vendredi_id, "Croissants-time", "http://127.0.0.1/croissants/croissantTime.php", "$user a démarré l''opération croissant. Tous à la cafèt !", $date);
		
		$str = "update VENDREDI set IS_STARTED = '1' where PK_VENDREDI_ID = '".$resultat['PK_VENDREDI_ID']."'";
		$req = mysql_query($str);
		$resultat = mysql_fetch_array($req);
		
		header("Location:mainFrame.php?page=liste&message=Opération croissant initialisée");
		return;
	}
	else
	{
		header("Location:mainFrame.php?page=liste&error=Tu n'est pas inscrit(e) aujourd'hui, tu ne peux pas lancer l'opération croissant !");
		return;
	}
*/
?>
