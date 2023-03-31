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
	
	/* Envoie le mail */
	$headers = 'MIME-Version: 1.0'."\n";
	$headers .='From: "No Reply"<no-reply@olympe-network.com>'."\n";
    $headers .='Reply-To: loic.correvon@elca.ch'."\n";
    $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
    $headers .='Content-Transfer-Encoding: 8bit';

	$title = stripslashes($_POST['title']);
	$message = stripslashes($_POST['message']);
	
	$str = "select EMAIL from USER";
	$req = mysql_query($str);
	htmlHeader();
	$mail = "";
	while($resultat = mysql_fetch_array($req))
		$mail .= $resultat['EMAIL'].";";

	if($mail != "")
	{
		echo "mail : $mail<br/><br/>";
		echo "title : $title<br/><br/>";
		echo "message : $message<br/><br/>";
		echo "headers : $headers<br/><br/>";
		return;
		
		$mail = substr($mail, 0, strlen($mail)-1);
		if(mail($mail, $title, $message, $headers))
			echo "<font color = 'green'>Mail envoyé à $mail<br/>";
		else
			echo "<font color = 'red'>Problème lors de l'envoie du mail à $mail<br/>";
		//echo "mail : $mail / strlen(mail) : ".strlen($mail)." / substr(mail, len-2, 1) : ".substr($mail, strlen($mail)-2, 1)."<br/>";
	}
	echo "<a href = 'mainFrame.php?page=mailEverybody'>Retour</a>";
	htmlFooter();
?>