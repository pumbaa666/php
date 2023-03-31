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
	$headers .='From: "No Reply"<loic.correvon@elca.ch>'."\n";
    $headers .='Reply-To: loic.correvon@elca.ch'."\n";
    $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
    $headers .='Content-Transfer-Encoding: 8bit';

	$title = "test2";
	$message = "teest2";
	$mailTo = "loic.correvon@elca.ch";
	
	mail($mailTo, $title, $message, $headers);
	htmlFooter();
?>