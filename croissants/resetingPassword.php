<?php
	require_once("fonction.php");

	if(!isset($_POST['mail']))
	{
		header("Location:resetPassword.php?error=Il faut indiquer votre adresse email");
		return;
	}
	$mail = $_POST['mail'];
	$newPassword = randomString(6);
	$hash = md5($newPassword);
	
	$headers = 'MIME-Version: 1.0'."\n";
	$headers .='From: "Loïc Correvon"<loic.correvon@elca.ch>'."\n";
    $headers .='Reply-To: loic.correvon@elca.ch'."\n";
    $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
    $headers .='Content-Transfer-Encoding: 8bit';

	require_once("connectdb.php");
	$str = "select PK_USER_ID from USER where EMAIL = '$mail'";
	$req = mysql_query($str);
	$resultat = mysql_fetch_array($req);
	$user = $resultat[0];
	$message = "Le nouveau mot de passe du compte $user est maintenant : $newPassword
	
	http://pumbaa.olympe-network.com/croissants/";
	
	htmlHeader();
	if(mail($mail, 'Nouveau mot de passe', $message, $headers))
	{
		$str2 = "update USER set password = '".$hash."' where pk_user_id = '".$user."'";
		//echo "<br>str = $str<br>";
		//echo "<br>hash = $hash<br>";
		$req2 = mysql_query($str2);
		//echo "<br>req = $req<br>";
		//echo "mysql_res ".mysql_result($req);
		echo "<br><br><font color = 'black'>Un email vous a été envoyé à $mail avec votre nouveau mot de passe<br/><a href = 'index.php'>Retour au login</a>";
	}
	else
		echo "<br><font color = 'red'>Problème lors de l'envoie du mail avec le nouveau mot de passe, contactez l'admin à loic.correvon@elca.ch";

?>
<?php
	htmlFooter();
?>