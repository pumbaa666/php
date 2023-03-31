<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();
	
	require_once("connectdb.php");
	$user = $_SESSION['userId'];
	$str = "select nb_day_before_mail, nb_day_to_see, send_mail_before_date, email_visible, receive_notification, page_accueil from SETTINGS where pk_fk_user_id = '$user'";
	$req = mysql_query($str);
	$resultat = mysql_fetch_array($req);
	
	echo "<form method = 'post' action = 'modifSettings.php'>";
		if($resultat['send_mail_before_date'] != "0")
			$checked = "checked";
		else
			$checked = "";
		echo "<input type = 'checkbox' name = 'checkMailBefore' $checked /> Recevoir un mail <input type = 'text' name = 'nbDayBeforeMail' value = '".$resultat['nb_day_before_mail']."' maxlength='1' size = '1' /> jours avant d'apporter les croissants (0 pour le jour même)<br/>";
		
		echo "Afficher maximum <input type = 'text' name = 'nbDayToSee' value = '".$resultat['nb_day_to_see']."' maxlength='2' size = '2' /> jours dans la liste des inscrits<br />";

		if($resultat['email_visible'] != "0")
			$checked = "checked";
		else
			$checked = "";
		echo "<input type = 'checkbox' name = 'checkEmailVisible' $checked /> Adresse email visible</font><br />";

		if($resultat['receive_notification'] != "0")
			$checked = "checked";
		else
			$checked = "";
		
		echo "Page d'accueil du site ";
		$tabPageAccueil = array(
			"accueil" => "Accueil", 
			"liste" => "Liste", 
			"news" => "News", 
			"settings" => "Préferences"
		);
		echo "<select name = 'pageAccueil'>";
			$str = "";
			foreach($tabPageAccueil as $pageReal => $pageAccueil)
			{
				//$str .= "<br>pageReal : $pageReal / pageAccueil : $pageAccueil / resultat[page] : ".$resultat['page_accueil']."<br>";
				if($pageReal == $resultat['page_accueil'])
					$selected = "selected";
				else
					$selected = "";
				echo "<option value='$pageReal' $selected>$pageAccueil</option>";
			}
		echo "</select><br/>";
		
		$str = "select EMAIL from USER where PK_USER_ID = '$user'";
		$req = mysql_query($str);
		$resultat = mysql_fetch_array($req);
		$mail = $resultat['EMAIL'];
		echo "<br/>Email : <input type = 'text' name = 'mail' value = '$mail' /><br/>";
		
		echo "<br/><table border = '0'>";
			echo "<tr>";
				echo "<td>Changer de mot de passe</td><td><input type = 'password' name = 'password'></td>";
			echo "<tr>";
			echo "</tr>";
				echo "<td>Répeter le mot de passe</td><td><input type = 'password' name = 'repeatPassword'></td>";
			echo "<tr>";
			echo "</tr>";
				echo "<td>Entrer l'ancien mot de passe</td><td><input type = 'password' name = 'oldPassword'></td>";
			echo "</tr>";
		echo "</table>";
		
		echo "<br/><br/><input type = 'submit' value = 'Enregistrer' />";
	echo "</form>";

?>
<?php
	htmlFooter();
?>