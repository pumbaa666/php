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

	if(isset($_GET['user']))
	{
		$user = $_GET['user'];
		
		$str = "delete from USER where PK_USER_ID = '$user'";
		$req = mysql_query($str);
		$nb = mysql_affected_rows();
		if($nb == 0)
			$message = "Personne n'a été supprimé de la ";
		
		$str = "delete from SETTINGS where PK_FK_USER_ID = '$user'";
		$req = mysql_query($str);
		
		$str = "delete from WAIT_CONFIRM where PK_FK_USER_ID = '$user'";
		$req = mysql_query($str);
		
		header("Location:mainFrame.php?page=adminUser&message=$user a été supprimé");
	}
	else
		header("Location:mainFrame.php?page=adminUser&error=Entrez un VISA a supprimer");
?>
