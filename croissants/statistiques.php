<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();
	
	require_once("connectdb.php");
	$strUser = "select FK_USER_ID from CROISSANTS_APPORTES group by FK_USER_ID";
	$reqUser = mysql_query($strUser);
	
	/* Remplissage du tableau interne */
	$i = 0;
	while($resultatUser = mysql_fetch_array($reqUser))
	{
		$user = $resultatUser['FK_USER_ID'];
		if($user == "-")
			continue;
		$tab['visa'][$i] = strtoupper($user);
		$tab['lastBring'][$i]  = date("Ymd", strtotime(lastBring($user)));
		$tab['nbBring'][$i]  = nbBring($user);
		$tab['since'][$i]  = date("Ymd", strtotime(participeDepuis($user)));
		$tab['idle'][$i]  = idleMoyen($user);
		
		$i++;
	}
	
	/* Tri */
	$sens = SORT_ASC;
	if(isset($_GET['order']))
		$order = $_GET['order'];
	else
		$order = 'idle';
		
		/* Sens du tri */
		if(isset($_GET['sens']))
		{
			if($_GET['sens'] == 3)
				$sens = SORT_DESC;
			else
				$sens = SORT_ASC;
		}
		
		switch($order)
		{
			case 'visa' : 
				array_multisort($tab['visa'], SORT_STRING, $sens,
								$tab['lastBring'], SORT_NUMERIC, $sens,
								$tab['nbBring'], SORT_NUMERIC, $sens,
								$tab['since'], SORT_NUMERIC, $sens,
								$tab['idle'], SORT_NUMERIC, $sens);
			break;
			
			case 'lastBring' : 
				array_multisort($tab['lastBring'], SORT_NUMERIC, $sens,
								$tab['visa'], SORT_STRING, $sens,
								$tab['nbBring'], SORT_NUMERIC, $sens,
								$tab['since'], SORT_NUMERIC, $sens,
								$tab['idle'], SORT_NUMERIC, $sens);
			break;
			
			case 'nbBring' : 
				array_multisort($tab['nbBring'], SORT_NUMERIC, $sens,
								$tab['visa'], SORT_STRING, $sens,
								$tab['lastBring'], SORT_NUMERIC, $sens,
								$tab['since'], SORT_NUMERIC, $sens,
								$tab['idle'], SORT_NUMERIC, $sens);
			break;
			
			case 'since' : 
				array_multisort($tab['since'], SORT_NUMERIC, $sens,
								$tab['visa'], SORT_STRING, $sens,
								$tab['lastBring'], SORT_NUMERIC, $sens,
								$tab['nbBring'], SORT_NUMERIC, $sens,
								$tab['idle'], SORT_NUMERIC, $sens);
			break;
			
			case 'idle' : 
				array_multisort($tab['idle'], SORT_NUMERIC, $sens,
								$tab['visa'], SORT_STRING, $sens,
								$tab['lastBring'], SORT_NUMERIC, $sens,
								$tab['nbBring'], SORT_NUMERIC, $sens,
								$tab['since'], SORT_NUMERIC, $sens);
			break;
		}
	
	/* En-tête du tableau */
	if($sens == SORT_ASC)
	{
		$sens = SORT_DESC;
		$nonSens = SORT_ASC;
	}
	else
	{
		$sens = SORT_ASC;
		$nonSens = SORT_DESC;
	}
	
	$tabHeader = array(
		"visa" => "Visa",
		"idle" => "<font color = 'red'>Idle moyen</font>",
		"nbBring" => "Nombre d'apport",
		"lastBring" => "Dernier apport",
		"since" => "Participe depuis",
		);
		
	echo "<table border = '1'>";
		echo "<tr>";
			foreach ($tabHeader as $tri => $text)
			{
				echo "<td><b><a href = 'mainFrame.php?page=statistiques&order=$tri&sens=";
				
				/* Ne change pas le sens du tri si on trie par un autre critère que lors du précédent tri */
				if($tri == $order)
					echo $sens;
				else
					echo $nonSens;
					
				echo "'>$text</a></b></td>";
			}
		echo "</tr>";

	for($j = 0; $j < $i; $j++)
	{
		echo "<tr>";
			echo "<td><a href = 'mainFrame.php?page=statUser&user=".$tab['visa'][$j] ."'>".$tab['visa'][$j] ."</a></td>";
			
			if($tab['idle'][$j] != " - ")
				$idleWeek = " jours</font> (toutes les ".substrDigit($tab['idle'][$j] / 7 , -1) ." semaines)";
			else
				$idleWeek = "";
			echo "<td><font color = 'red'>".$tab['idle'][$j]."$idleWeek</td>";
			
			echo "<td>".$tab['nbBring'][$j] ."</td>";
			
			if($tab['lastBring'][$j] == '19700101')
			{
				$lastBring = "-";
				$nbDay = "";
				$participeDepuis = "-";
			}
			else
			{
				$lastBring = date("d M Y", strtotime($tab['lastBring'][$j]));
				$nbDay = "(".substrDigit(abs(nbDaysBetween(date("d-m-Y", time()), date("d-m-Y", strtotime($tab['lastBring'][$j])))), -1)." jours)";
				$participeDepuis = date("d M Y", strtotime($tab['since'][$j] ));
			}
			echo "<td>".$lastBring." $nbDay"."</td>";
			echo "<td>".$participeDepuis."</td>";
		echo "</tr>";
	}
	
	
	echo "</table>";
	
	
	htmlFooter();

	/* Date du dernier apport de croissant */
	function lastBring($user)
	{
		$strLastBring = "	select max(DATE) as lastBring
							from CROISSANTS_APPORTES
							where FK_USER_ID = '$user'
							and DATE < DATE_ADD(NOW(), INTERVAL 1 DAY)
							group by FK_USER_ID";
							
		$reqLastBring = mysql_query($strLastBring);
		$resultLastBring = mysql_fetch_array($reqLastBring);
		$lastBring = $resultLastBring['lastBring'];
		
		$lastBring = date("d-m-Y", strtotime($lastBring)); 
		$today = date("d-m-Y", time());
		
		$nbJours = substrDigit(nbDaysBetween($lastBring, $today),-1);
		$nbSemaines = substrDigit($nbJours/7, 2);
		$nbMois = substrDigit($nbJours/30, 2);
		
		//return date("d M Y", strtotime($lastBring));
		return $lastBring;
	}
	
	/* Nombre de fois qu'on a apporté les croissants */
	function nbBring($user)
	{
		$strNbCroissants = "select count(*) as nb from CROISSANTS_APPORTES where FK_USER_ID = '$user'";
		$reqNbCroissants = mysql_query($strNbCroissants);
		$resultatNbCroissant = mysql_fetch_array($reqNbCroissants);
		$nbCroissant = $resultatNbCroissant['nb'];

		return $nbCroissant;
	}
	
	/* Depuis quand on participe à l'opération croissants*/
	function participeDepuis($user)
	{		
		$nbBring = nbBring($user);
		if($nbBring == 0)
			return " - ";

		$strFirstBring = "	select min(DATE) as firstDate
							from CROISSANTS_APPORTES
							where FK_USER_ID = '$user'
							group by FK_USER_ID";
							
		$reqFirstBring = mysql_query($strFirstBring);
		$resultFirstBring = mysql_fetch_array($reqFirstBring);
		$firstBring = $resultFirstBring['firstDate'];
		
		return $firstBring;
		//echo "<br/>Apporte les croissants depuis le : ".date("d M Y", strtotime($firstBring));
	}
	
	/* Temps moyen entre 2 apports */
	function idleMoyen($user)
	{
		$nbBring = nbBring($user);
		if($nbBring == 0)
			return " - ";
		$avg = abs(nbDaysBetween(date("d-m-Y", time()), date("d-m-Y", strtotime(participeDepuis($user)))) / $nbBring);
		
		return substrDigit($avg, -1);
	}
	
	/* Nombre de jours entre 2 dates */
	function nbDaysBetween($date1, $date2)
	{
		list($jour1, $mois1, $annee1) = explode('-', $date1); 
		list($jour2, $mois2, $annee2) = explode('-', $date2);

		$timestamp1 = mktime(0,0,0,$mois1,$jour1,$annee1); 
		$timestamp2 = mktime(0,0,0,$mois2,$jour2,$annee2);
		
		return ($timestamp2 - $timestamp1)/86400;
	}
?>