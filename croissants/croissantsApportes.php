<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	if(!isAdmin())
	{
		header("Location:error.php?type=autorisationAdmin");
		return;
	}

	$croissantApportes = $_POST['croissantApportes'];
	$croissantDate = $_POST['croissantDate'];
	
	$i = 0;
	$nbAdded = 0;
	$error = "";
	while(isset($croissantApportes[$i]))
	{
		if($croissantApportes[$i] != "")
		{
			$user = $croissantApportes[$i];
			$strInsert = "insert into CROISSANTS_APPORTES (FK_USER_ID, DATE) values ('".$user."', '".$croissantDate[$i]."')";
			$reqInsert = mysql_query($strInsert);
			$newError = mysql_error();
			if($newError != "")
			{
				$error .= "<br/>".$newError;
				$nbAdded--;
			}
			$nbAdded++;
			
			/* Delete l'enregistrement d'ALERT_MAIL pour que le user recoive la prochaine alerte */
			$strDeleteAlert = "delete from ALERT_MAIL where FK_USER_ID='$user'";
			$reqDeleteAlert = mysql_query($strDeleteAlert);
		}
		$i++;
	}
	
	$message = "$nbAdded user ajoutés";
	header("Location:mainFrame.php?page=liste&message=$message&error=$error");
?>
