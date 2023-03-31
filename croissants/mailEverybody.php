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
	
	echo "<form method = 'post' action = 'mailingEverybody.php'>";
		echo "Titre : <input type = 'text' name = 'title' maxlength='80' size = '40' value = 'Croissants : '/><br/>";
		echo "<textarea name='message' rows = '10' cols = '40'>Loïc

http://pumbaa.olympe-network.com/croissants/
</textarea><br/>";
		echo "<input type = 'submit' value = 'Envoyer' name = 'submit' />";
	echo "</form>";

?>
<?php
	htmlFooter();
?>