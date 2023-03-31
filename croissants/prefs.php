<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();
	
	echo "<form method = 'post' action = 'modifPrefs.php'>";
		echo "Recevoir un mail <input type = 'text' name = 'nbDayBeforeMail' value = '0' maxlength='1' size = '1'> jours avant d'apporter les croissants (0 pour ne rien recevoir)<br/>";
		echo "Afficher jusqu'à <input type = 'text' name = 'nbDayAfterLastSubscribed' value = '10' maxlength='2' size = '2'> jours  après le dernier inscrit</br>";
		echo "Adresse email visible <input type = 'checkbox' name = 'emailVisible'></br>";
		echo "Recevoir le mail de notification à l'heure des croissants</br>";
		
		echo "<input type = 'submit' value = 'Enregistrer'>";
	echo "</form>";

?>
<?php
	htmlFooter();
?>