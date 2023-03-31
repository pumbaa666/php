<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	require_once("connectdb.php");
	isAuthorized();
	
	$strUpdate = "";
	
	if(isset($_POST['nbDayBeforeMail']))
	{
		if(number_format($_POST['nbDayBeforeMail']) != $_POST['nbDayBeforeMail'] || number_format($_POST['nbDayBeforeMail']) < 0 || number_format($_POST['nbDayBeforeMail']) > 6)
		{
			header("Location:mainFrame.php?page=settings&error=Le nombre de jour avant de recevoir un email doit être un chiffre entre 0 et 6");
			return;
		}
		$strUpdate .= "nb_day_before_mail = '".mysql_real_escape_string($_POST['nbDayBeforeMail'], $db)."',";
	}

	if(isset($_POST['checkMailBefore']))
		$strUpdate .= "send_mail_before_date = '1',";
	else
		$strUpdate .= "send_mail_before_date = '0',";
		
	if(isset($_POST['nbDayToSee']))
	{
		if(number_format($_POST['nbDayToSee']) != $_POST['nbDayToSee'] || number_format($_POST['nbDayToSee']) < 1 || number_format($_POST['nbDayToSee']) > 365)
		{
			header("Location:mainFrame.php?page=settings&error=Le nombre de jour à afficher dans la liste doit être un chiffre compris entre 1 et 365");
			return;
		}
		$strUpdate .= "nb_day_to_see = '".mysql_real_escape_string($_POST['nbDayToSee'], $db)."',";
	}
		
	if(isset($_POST['checkEmailVisible']))
		$strUpdate .= "email_visible = '1',";
	else
		$strUpdate .= "email_visible = '0',";
		
	if(isset($_POST['pageAccueil']))
		$strUpdate .= "page_accueil = '".mysql_real_escape_string($_POST['pageAccueil'], $db)."',";


	if($strUpdate == "")
	{
		header("Location:mainFrame.php?page=settings?error=Aucuns paramètres");
		return;
	}
	
	$message = "message=Modification de préférences effectuées";
	
	$user = $_SESSION['userId'];
	$strUpdate = substr($strUpdate, 0, strlen($strUpdate)-1);
	$str = "update SETTINGS set ".$strUpdate." where PK_FK_USER_ID = '$user'";
	//echo "str = $str";
	$req = mysql_query($str);
	
	/* Modification du password */
	if(isFilled($_POST['password']))
	{
		if(!isFilled($_POST['repeatPassword']))
		{
			header("Location:mainFrame.php?page=settings&$message&error=Entrez la répétition du password");
			return;
		}
		if(!isFilled($_POST['oldPassword']))
		{
			header("Location:mainFrame.php?page=settings&$message&error=Entrez votre ancien password");
			return;
		}
		if($_POST['password'] != $_POST['repeatPassword'])
		{
			header("Location:mainFrame.php?page=settings&$message&error=La répétition du mot de passe ne correspond pas");
			return;
		}
		
		$str = "select PASSWORD from USER where PK_USER_ID = '$user'";
		$req = mysql_query($str);
		$resultat = mysql_fetch_array($req);
		if(trim($resultat['PASSWORD']) != md5($_POST['oldPassword']))
		{
			header("Location:mainFrame.php?page=settings&$message&error=Votre ancien mot de passe est faux");
			return;
		}

		/* Vérifie que le password est sécurisé */
		$result = checkPasswordSecurity($_POST['password']);
		if($result == "too_short")
		{
			header("Location:mainFrame.php?page=settings&error=Votre mot de passe doit contenir au moins 6 caractères");
			return;
		}
		if($result == "password1")
		{
			header("Location:mainFrame.php?page=settings&error=Votre mot de passe ne doit pas être password1");
			return;
		}
		
		/* Cette fois c'est bon ! */
		$psw = md5($_POST['password']);
		$str = "update USER set PASSWORD = '$psw' where PK_USER_ID = '$user'";
		$req = mysql_query($str);
		$message .= "<br/>Mot de passe changé";
		if($_SESSION['admin'] != '')
			$_SESSION['admin'] = $psw; // Update la session pour que l'admin ai tjs ces droits admin
	}
	
	/* Modification de l'email */
	if(isFilled($_POST['mail']) && isMailCorrect($_POST['mail']))
	{
		$strGetMail = "select EMAIL from USER where PK_USER_ID = '$user'";
		$reqGetMail = mysql_query($strGetMail);
		$resultatGetMail = mysql_fetch_array($reqGetMail);
		
		$mail = $_POST['mail'];
		if($mail != $resultatGetMail['EMAIL'])
		{
			$strUpdateMail = "update USER set EMAIL = '$mail' where PK_USER_ID = '$user'";
			$reqUpdateMail = mysql_query($strUpdateMail);
			$message .= "<br/>Adresse email changée";
		}
	}

	header("Location:mainFrame.php?page=settings&$message");
?>