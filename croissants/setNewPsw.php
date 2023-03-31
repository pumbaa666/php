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
	htmlHeader();

	if(!isset($_POST['newPsw']))
	{
		header("location:mainFrame.php?page=adminUser&error=Choisissez un utilisateur");
		return;
	}
	
	$user = $_POST['newPsw'];
	$str = "update USER set password = '".md5('password12')."' where pk_user_id = '".$user."'";
	$req = mysql_query($str);

	header("location:mainFrame.php?page=adminUser&message=Le password de l'utilisateur ".$user." a été changé");
	return;
?>
<?php
	htmlFooter();
?>