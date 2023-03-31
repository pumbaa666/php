<?php
//FONCTION PRINCIPALE DU CALENDRIER
function calendrier($month, $year, $bookedDays)
{
	include("config.php");
	$result = "";

	// Calcul du nombre de jours dans chaque mois en prenant compte des années bisextiles. les tableaux PHP commençant à 0 et non à 1, le premier mois est un mois "factice" qui contient 31 pour faire fonctionner $dayOfPreviousMonth si on est en janvier
	$nbrjour = array(31, 31, $year % 4 == 0 ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

	// On cherche grâce à cette fonction à quel jour de la semaine correspond le 1er du mois 
	$firstDayOfMonth = jddayofweek(cal_to_jd(CAL_GREGORIAN, $month, 1, $year), 0);
	if($firstDayOfMonth == 0)
		$firstDayOfMonth = 7;

	//Préparation du tableau avec le nom du mois et la liste des jours de la semaine
	$result .= "<table border='1' bordercolor='#FFFFFF'><tr>"
		."<td class='nom_mois' colspan='7'>$months[$month] $year</td><td class='fleches'></td>"
		."</tr><tr class='noms_jours'>";
		
	for($j = 1; $j <= 7; $j++)
		$result .= "<td>".$days[$j]."</td>";
	
	$result .= "</tr><tr>";

	$day = 1; //Cette variable est celle qui va afficher les jours de la semaine
	$dayOfPreviousMonth = $nbrjour[$month-1] - $firstDayOfMonth+2; //Celle-ci sert à afficher les jours du mois précédent qui apparaissent
	$dayOfNextMonth = 1; //Et celle-ci les jours du mois suivant

	//Et c'est parti pour la boucle for qui va créer l'affichage de notre calendrier !
	$i = 1;
	while(true)
	{
		if($i < $firstDayOfMonth) // Tant que la variable i ne correspond pas au premier jour du mois, on fait des cellules de tableau avec les derniers jours du mois précédent
		{
			$result .= "<td class='cases_vides'>$dayOfPreviousMonth</td>";
			$dayOfPreviousMonth++;
		}
		else
		{
			$style = "jours";
			if($day == date("d") && $month == date("n")) //Si la variable $day correspond à la date d'aujourd'hui, la case est d'une couleur différente
				$style = "aujourdhui";

			$url = "";
			
			$user = "";
			if(isset($bookedDays[$year][$month]))
				if($bookedDays[$year][$month][0] == $day)
				{
					$user = "<br/>LCO";
					$style = "booked";
				}
				//$user = $bookedDays[$year][$month][$day];
			
			$result .= "<td class='".$style."'><a href = '".$url."' style='display: block; height: 90%; width: 100%;'>".$day.$user."</a></td>";
			$day++; //On passe au lendemain ^^
		
			/*Si la variable $day est plus élevée que le nombre de jours du mois,  c'est que c'est la fin du mois! 
			    On remplit les cases vides avec les premiers jours des mois suivants
			    Hop on ferme le tableau, 
			    et on met la variable $i à 41 pour sortir de la boucle */
			if($day > ($nbrjour[$month]))
			{
				while($i % 7 != 0)
				{
					$result .= "<td class='cases_vides'>$dayOfNextMonth</td>";
					$i++;
					$dayOfNextMonth++;
				}
				$result .= "</tr></table>";
				break;
			}
		}
	
		// Si la variable i correspond à un dimanche (multiple de 7), on passe à la ligne suivante dans le tableau
		if($i % 7 == 0)
			$result .= "</tr><tr>";
		$i++;
	}
	
	return $result;
}

//FONCTION POUR AFFICHER LE MOIS SUIVANT
/*function nextMonth($month, $year)
{
	$month++; //mois suivant, donc on incrémente de 1
	if($month == 13) //si le mois et 13 ça joue pas! cela veut dire qu'il faut augmenter l'année de 1 et repasser le mois à 1
	{
		$year++;
		$month = 1;
	}
	return '<a href="'.$_SERVER['PHP_SELF']."?month=$month&year=$year'> &raquo; </a>";
}

//FONCTION POUR AFFICHER LE MOIS PRECEDENT
function previousMonth($month, $year)
{
	$month--;
	if($month == 0)
	{
		$year--;
		$month = 12;
	}
	return '<a href="'.$_SERVER['PHP_SELF']."?month=$month&year=$year'> &laquo; </a>";
}*/
?>