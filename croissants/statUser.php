<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();
	
	if(!isset($_GET['user']))
	{
		header("Location:mainFrame.php?page=statistiques&error=Veuillez spécifier un utilisateur");
		return;
	}
	$user = $_GET['user'];
	echo "<center><b><font size = '5'>$user</font></b></center><br/><br/>";
	
	require_once("connectdb.php");
	/* Date du dernier apport de croissant */
	$strLastBring = "	select max(DATE) as lastBring
						from CROISSANTS_APPORTES
						where FK_USER_ID = '$user'
						and DATE < NOW()
						group by FK_USER_ID";
						
	$reqLastBring = mysql_query($strLastBring);
	$resultLastBring = mysql_fetch_array($reqLastBring);
	$lastBring = $resultLastBring['lastBring'];
	
	$lastBring = date("d-m-Y", strtotime($lastBring)); 
	$today = date("d-m-Y", time());
	
	$nbJours = substrDigit(nbDaysBetween($lastBring, $today),-1);
	$nbSemaines = substrDigit($nbJours/7, 2);
	$nbMois = substrDigit($nbJours/30, 2);
	
	echo "Dernier apport de croissant : ".date("d M Y", strtotime($lastBring));
	echo " (soit $nbJours jours, soit $nbSemaines semaines, soit $nbMois mois)";
	
	/* Nombre de fois qu'on a apporté les croissants */
	$strNbCroissants = "select count(*) as nb from CROISSANTS_APPORTES where FK_USER_ID = '$user'";
	$reqNbCroissants = mysql_query($strNbCroissants);
	$resultatNbCroissant = mysql_fetch_array($reqNbCroissants);
	$nbCroissant = $resultatNbCroissant['nb'];

	echo "<br/>Nombre de fois qu'il a apporté les croissants : $nbCroissant";
	
	/* Depuis quand on participe à l'opération croissants*/
	$strFirstBring = "	select min(DATE) as firstDate
						from CROISSANTS_APPORTES
						where FK_USER_ID = '$user'
						group by FK_USER_ID";
						
	$reqFirstBring = mysql_query($strFirstBring);
	$resultFirstBring = mysql_fetch_array($reqFirstBring);
	$firstBring = $resultFirstBring['firstDate'];
	echo "<br/>Apporte les croissants depuis le : ".date("d M Y", strtotime($firstBring));
	
	/* Temps moyen entre 2 apports */
	$avg = abs(nbDaysBetween($lastBring, date("d-m-Y", strtotime($firstBring))) / $nbCroissant);
	echo "<br/>Temps moyen entre 2 apports : ".substr($avg, 0, 3)." jours";
	
	
	echo "<br/><br/><font color = 'red'>En construction</font>";

	function nbDaysBetween($date1, $date2)
	{
		list($jour1, $mois1, $annee1) = explode('-', $date1); 
		list($jour2, $mois2, $annee2) = explode('-', $date2);

		$timestamp1 = mktime(0,0,0,$mois1,$jour1,$annee1); 
		$timestamp2 = mktime(0,0,0,$mois2,$jour2,$annee2);
		
		return ($timestamp2 - $timestamp1)/86400;
	}
?>
<?php
	htmlFooter();
?>