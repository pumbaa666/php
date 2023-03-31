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
		$_SESSION['userId'] = $user;
		if(isset($_GET['right']))
			$_SESSION['admin'] = '';
		header("Location:mainFrame.php?page=liste&message=Vous êtes loggé en tant que $user");
	}
	else
		header("Location:mainFrame.php?page=loginAs&message=Entrez un VISA pour vous logger");
?>
