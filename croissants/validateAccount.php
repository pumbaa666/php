<?php
	session_start();
	require_once("fonction.php");
	
	/* Validation manuelle par l'admin */
	$code = '';
	if(isAdmin())
	{
		if(isset($_GET['user']))
		{
			$user = $_GET['user'];
			$str = "select CODE from WAIT_CONFIRM where PK_FK_USER_ID = '$user'";
			//echo $str;return;
			$req = mysql_query($str);
			$resultat = mysql_fetch_array($req);
			$code = $resultat['CODE'];
			$redirectToAdminPage = true;
			$message = "Le compte de $user a été validé ";
		}
	}

	//echo $code;return;
	if($code == '' && !isset($_GET['code']))
	{
		header("Location:index.php?error=C'est pas bien de bidouiller les URL !");
		return;
	}
	if($code == '')
		$code = $_GET['code'];
	
	/* Préviens d'une attaque par injection SQL dans le $code */
	if(!checkField($code, 10, getUnauthorizedChar()))
	{
		header("Location:index.php?error=C'est pas bien de bidouiller les URL !");
		return;
	}
	
	require_once("connectdb.php");
	/* Recherche le visa et le password de l'utilisateur correspondant au code donné */
	$str = "select PK_FK_USER_ID, PASSWORD from WAIT_CONFIRM where CODE='$code'";
	//echo $str;return;
	$req = mysql_query($str);
	$result = mysql_fetch_array($req);
	if($result['PK_FK_USER_ID'] == "")
	{
		header('Location:index.php?error=Erreur avec le code de validation');
		return;
	}
	
	/* Insert le hash du password dans la table USER */
	$user = $result['PK_FK_USER_ID'];
	$password = $result['PASSWORD'];
	$str = "update USER set PASSWORD = '$password' where PK_USER_ID='$user'";
	$req = mysql_query($str);
	
	/* Supprime l'attente de validation de la table WAIT_CONFIRM */
	$str = "delete from WAIT_CONFIRM where CODE='$code'";
	$req = mysql_query($str);
	
	if(isset($redirectToAdminPage))
	{
		header("Location:mainFrame.php?page=adminUser&message=$message");
		return;
	}
	
	htmlHeader();
	echo "Votre compte a été validé, vous pouvez aller <a href = 'index.php?user=$user'>vous logger</a>";
?>
<?php
	htmlFooter();
?>