<?php
	session_start();
	require_once("fonction.php");

	/* Initialisation des test de champs */
	$unauthorizedChar = getUnauthorizedChar();
	$fieldsToCheck = array("visa", "nom", "prenom", "mail");
	$maxLength = 40;

	/* Création d'une url contenant les informations rentrées par l'utilisateur (utilisée en cas d'erreur, pour pas qu'il ai à tout retaper) */
	$userValue = "";
	foreach($fieldsToCheck as $fieldValue)
		$userValue = $userValue.$fieldValue."=".$_POST[$fieldValue]."&";
	$userValue = substr($userValue, 0, strlen($userValue) - 1);
	
	/* Test des champs */
	/* Test si le visa est correct */
	if(strlen($_POST['visa']) != 3)
	{
		header("Location: error.php?type=visa&redirect=createAccount.php?".urlencode($userValue));
		return;
	}
		
	/* Test si l'email est correct */
	$mail = $_POST['mail'];
	if(!isMailCorrect($mail))
	{
		header("Location: error.php?type=email&redirect=createAccount.php?".urlencode($userValue));
		return;
	}
		
	/* Vérifie qu'il n'y a pas de caractères interdis */
	foreach($fieldsToCheck as $field)
		if(checkField($_POST[$field], $maxLength, $unauthorizedChar) != true)
		{
			header("Location: error.php?type=character&maxLength=$maxLength&field=$field&redirect=createAccount.php?".urlencode($userValue));
			return;
		}
	
	/* Test des password */
	if($_POST["password"] != $_POST["passwordRepeat"])
	{
		header("Location: error.php?type=passwordMatch&redirect=createAccount.php?".urlencode($userValue));
		return;
	}
	$result = checkPasswordSecurity($_POST['password']);
	if($result == "too_short")
	{
		header("Location: error.php?type=passwordLenght&redirect=createAccount.php?".urlencode($userValue));
		return;
	}
	if($result == "password1")
	{
		header("Location: error.php?type=password1&redirect=createAccount.php?".urlencode($userValue));
		return;
	}
		
	/* Tout est bon (dans le cochon) */
	require_once("connectdb.php");
	/* Crée le user */
	$user = mysql_real_escape_string(strtoupper($_POST['visa']));
	$prenom = mysql_real_escape_string($_POST['prenom']);
	$nom = mysql_real_escape_string($_POST['nom']);
	$date = date("Y-m-d h:i:s", time());
	$str = "insert into USER (PK_USER_ID, FIRSTNAME, LASTNAME, EMAIL, PASSWORD, LAST_CONNEXION) values ('$user', '$prenom', '$nom', '$mail', '', '$date')"; // enregistre un password vide, voir Validation du compte
	$req = mysql_query($str);
	if(!$req)
	{
		$error = mysql_error();
		echo $error;return;
		if(substr($error, 0, 9) == "Duplicata")
		{
			header("Location: error.php?type=duplicateUserDB&redirect=createAccount.php?".urlencode($userValue));
			return;
		}
		else
		{
			// TODO logger les erreurs
			header("Location: error.php?type=errorDB&redirect=createAccount.php?".urlencode($userValue));
			return;
		}
	}
	
	/* Crée  une entrée  pour la validation du compte */
	$code = randomString(10);
	$date = date("Y-m-d", time());
	$str = "insert into WAIT_CONFIRM (PK_FK_USER_ID, PASSWORD, CODE, WAIT_SINCE) values ('$user', '".md5($_POST['password'])."', '$code', '$date')";
	$req = mysql_query($str);
	if(!$req)
	{
		$error = mysql_error();
		if(substr($error, 0, 9) == "Duplicata")
			die($_POST["visa"]." ce compte a déjà été crée, il vous faut le valider");
		else
			die("Problème lors de la requête : ".$error);
	}
	
	/* Crée  une entrée  pour les paramètres */
	$str = "insert into SETTINGS (pk_fk_user_id, nb_day_before_mail, send_mail_before_date, nb_day_to_see, email_visible, receive_notification, page_accueil) values ('$user', '1', '1', '10', '0', '0', 'liste')";
	$req = mysql_query($str);

	/* Envoie le mail de confirmation */
	$headers = 'MIME-Version: 1.0'."\n";
	$headers .='From: "Loïc Correvon"<loic.correvon@elca.ch>'."\n";
    $headers .='Reply-To: loic.correvon@elca.ch'."\n";
    $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
    $headers .='Content-Transfer-Encoding: 8bit';

	$message = "Votre compte a été créé, cliquez sur http://pumbaa.olympe-network.com/croissants/validateAccount.php?code=$code pour l'activer.";
	htmlHeader();
	if(mail($mail, 'Validation du compte croissant', $message, $headers))
	{
		echo "<br/><br/><font color = 'black'>Votre compte a été créé $user, un email vous a été envoyé avec un lien, cliquez dessus pour activer votre compte.<br/>";
		echo "<font color = 'red'>Si vous ne voyez pas le mail d'activation dans votre mail-box dans 30 minutes, vérifiez votre boite de spam</font><br/>";
		echo "<a href = 'index.php?user=$user'>Retour au login</a>";
		$message = "$user s'est inscrit sur le site de croissants avec l'email : $mail";
		mail('loic.correvon@elca.ch', 'Validation d\'un compte croissant', $message, $headers);
	}
	else
		echo "<br/><font color = 'red'>Problème lors de l'envoie du mail de confirmation, contactez l'admin à loic.correvon@elca.ch";

	
?>
<?php
	htmlFooter();
?>