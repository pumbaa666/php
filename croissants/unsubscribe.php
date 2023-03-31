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
	
	$historyType = -1;
	$sauveteur = "";
	if(isset($_GET['sauveteur']))
	{
		$sauveteur = "_SAUVETEUR";
		$historyType = -2;
	}
	
	/* Test si la date est dans le passé */
	if(strtotime($date) < time() && !isAdmin())
	{
		header("Location:mainFrame.php?page=liste&error=Impossible de se désinscrire d'une date passée");
		return;
	}

	
	/* Test si le vendredi est bien associé à la personne */
	require_once("connectdb.php");
	$user = $_SESSION['userId'];
	$date = date("Y-m-d", strtotime($date));

	$str = "select FK_USER_ID".$sauveteur." from VENDREDI where date = '$date'";
	$req = mysql_query($str);
	$result = mysql_fetch_array($req);

	if($result['FK_USER_ID'.$sauveteur] != $user)
	{
		header("Location:mainFrame.php?page=liste&error=Tu n'est pas inscrit ce vendredi");
		return;
	}
	else // On peut supprimer la personne
	{
		/* Notifie de la désinscription par RSS */
		$str = "select pk_vendredi_id from VENDREDI where date = '$date' and FK_USER_ID".$sauveteur." = '$user'";
		$req = mysql_query($str);
		$resultat = mysql_fetch_array($req);
		$vendredi_id = $resultat['pk_vendredi_id'];
		
		if($vendredi_id != '')
		{
			$datePoint = date("d.m.Y", strtotime($date));
			//updateXMLFile($user, $vendredi_id, "$user s\'est désinscrit !", "http://127.0.0.1/croissants/mainFrame.php?page=history&amp;date=$datePoint", "$user s\'est désinscrit pour le $datePoint", $date, 20); // TODO remettre et finir de coder
		}


		/* Désinscrit la personne */
		$str = "update VENDREDI set FK_USER_ID".$sauveteur." = '' where date = '$date'";
		$req = mysql_query($str);

		
		/* Enregistre dans l'historique */
		$timeNow = date("Y-m-d H:i:s", time());
		$strHistory = "insert into HISTORY (DATE, FK_USER_ID, DATE_FROM_CHANGE, SUBSCRIBE) values ('$date', '$user', '$timeNow', '$historyType')";
		$reqHistory = mysql_query($strHistory);
		
		header("Location:mainFrame.php?page=liste&message=Vous avez été désincrit");
	}
	
?>
