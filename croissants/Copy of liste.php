<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();
	
	echo "Note : Pour vous inscrire un jour, cliquer sur S'inscrire dans la colone Principal. Vous pouvez aussi vous inscrire en tant que Sauveteur pour prendre la relève au cas où le camarade principal venait à ne pas pouvoir accomplir sa mission.<br/><br/>";
	
	$firstDate = "20080613";
	
	// Affiche le calendrier (pour sélectionner la date de départ de l'affichage des vendredis)
	if(isset($_GET['date']))
	{
		$dateStart = $_GET['date'];
		if(strtotime($dateStart) < strtotime($firstDate))
			echo "<br/><font color = 'red'>Les croissants n'ont pas commencé avant le ".date("d \du m Y", strtotime($firstDate)).", qu'est-ce que tu cherches, hein ?</font>";
	}
	else
		$dateStart = date("Ymd", time());
	require_once('calendrier/calendrier.php');
    echo "Afficher depuis";
	echo calendar($dateStart);
	echo "<br/>ou depuis <a href = 'mainFrame.php?page=liste&date=$firstDate'>L'an 1 de l'Ère-Croissant</a><br/>";

	// Date de départ
	$yearStart = substr($dateStart, 0, 4);
	$monthStart = substr($dateStart, 4, 2);
	$dayStart = substr($dateStart, 6, 2);

	$everyFriday = getEveryFridayOfYear($yearStart, $monthStart, $dayStart);

	if($everyFriday == false)
		return;
	
	// Affichage de la liste
	echo "<br/>";
	echo "<form method = 'POST' action = 'croissantsApportes.php'>";
		require_once("connectdb.php");
		
		// En-tête
		echo "<table border = '1'>";
		echo "<tr>";
			echo "<td><b>Date</b></td>";
			echo "<td><b>Principal</b></td>";
			echo "<td><b>Sauveteur</b></td>";
			echo "<td><b>Apportés par</b></td>";
			echo "<td><b>Remarque</b></td>";
		echo "</tr>";
		
		// Initialisation
		$subscribeLabel = "S'inscrire";
		if($_SESSION['userId'] == "ADM")
			$subscribeLabel = "";
			
		$user = $_SESSION['userId'];
		$strGetNbDayToSee = "select NB_DAY_TO_SEE from SETTINGS where PK_FK_USER_ID = '$user'";
		$reqGetNbDayToSee = mysql_query($strGetNbDayToSee);
		$resultatGetNbDayToSee = mysql_fetch_array($reqGetNbDayToSee);
		$resultatGetNbDayToSee = $resultatGetNbDayToSee['NB_DAY_TO_SEE'];
		
		$count = 0;
		foreach($everyFriday as $friday)
		{
			echo "<tr>";
			
			// Date
			echo "<td><a href = 'mainFrame.php?page=history&date=$friday'>$friday</a></td>";
			
			// Qui est inscrit en principal
			echo "<td align = 'center'>";
			$who = whoIsSubscribed($friday);
			if($who == "")
			{
				if(strtotime($friday) > time() || isAdmin())
					echo "<a href = 'subscribe.php?date=$friday'>$subscribeLabel</a>";
				else
					echo " - ";
			}
			else
			{
				echo "$who<br/>";
				if($who == $user)
					echo "<a href = 'unsubscribe.php?date=$friday'>Se désinscrire</a>";
			}
			echo "</td>";
			
			// Qui est inscrit en sauveteur
			$sauveteur = whoIsSubscribed($friday, 'FK_USER_ID_SAUVETEUR');
			echo "<td align = 'center'>";
			if($sauveteur == "")
			{
				if(strtotime($friday) > time())
					echo "<a href = 'subscribe.php?date=$friday&sauveteur=1'>$subscribeLabel</a>";
				else
					echo " - ";
			}
			else
			{
				echo "$sauveteur<br/>";
				if($sauveteur == $_SESSION['userId'])
					echo "<a href = 'unsubscribe.php?date=$friday&sauveteur=1'>Se désinscrire</a>";
			}
			echo "</td>";
			
			// Qui a vraiment apporté les croissants
			echo "<td align = 'center'>";
				if(strtotime($friday) < time()) // Un user peut avoir apporté des croissants que dans une date passée
				{
					$dateTest = date("Y-m-d", strtotime($friday));
					$strCroissantApportes = "select FK_USER_ID from CROISSANTS_APPORTES where DATE = '$dateTest'";
					$reqCroissantApportes = mysql_query($strCroissantApportes);
					$userApportCroissant = "";
					while($resultatCroissantApportes = mysql_fetch_array($reqCroissantApportes))
					{
						$userApportCroissant .= $resultatCroissantApportes['FK_USER_ID'].", ";
					}
					$userApportCroissant = substr($userApportCroissant, 0, strlen($userApportCroissant)-2);
					if(isAdmin())
					{
						echo "<input type = 'text' value = '' name = 'croissantApportes[]'/>";
						echo "<input type = 'hidden' value = '$dateTest' name = 'croissantDate[]'/>";
					}
					echo $userApportCroissant;
				}
			echo "</td>";
			
			// Remarque
			$remarque = whoIsSubscribed($friday, 'REMARQUE');
			echo "<td>$remarque</td>";
			
			echo "</tr>";

			if($count++ >= $resultatGetNbDayToSee)
				break;
		}
		if(isAdmin())
		{
			echo "<td></td><td></td><td></td><td><input type = 'submit' value = 'Insérer'/></td><td></td>";
		}
		echo "</table>";
	echo "</form>";

?>
<?php
	htmlFooter();
?>