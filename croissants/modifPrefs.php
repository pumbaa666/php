<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();

	include("chantier.php");
?>
<?php
	htmlFooter();
?>