<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();
	
	require_once("testCal/calendrier.php");
	
	if(!isset($_GET['month']))
		$m = date("n");
	else
		$m = $_GET['month'];
		
	if(!isset($_GET['year']))
		$y = date("Y");
	else
		$y = $_GET['year'];
		
	$strBookedDays = "select fk_user_id, date from vendredi where date > SYSDATE() and fk_user_id is not null";
	$reqBookedDays = mysql_query($strBookedDays);
	
	while($restBookedDays = mysql_fetch_array($reqBookedDays))
	{
		$user = $restBookedDays['fk_user_id'];
		$date = $restBookedDays['date'];
		//$month = date("m", $date);
		//$year = date("Y", $date);
		$split = explode("-", $date);
		$year = $split[0];
		$month = $split[1] * 1;
		$day = $split[2];
		$arrayYear[$year][$month][] = $day;
		//echo "$user : $date | année = $year | mois = $month | day = $day <br>";
	}
	
	/*echo "</center><br> tableau : ";
	$maxI = count($arrayYear);
	for($i = 0; $i < $maxI; $i++)
	{
		$indexYear = key($arrayYear);
		echo "<br>".$indexYear;
		$arrayMonth = $arrayYear[$indexYear];
		$maxJ = count($arrayMonth);
		for($j = 0; $j < $maxJ; $j++)
		{
			$indexMonth = key($arrayMonth);
			echo "<br>____".$indexMonth;
			$arrayDay = $arrayMonth[$indexMonth];
			$maxK = count($arrayDay);
			for($k = 0; $k < $maxK; $k++)
			{
				$indexDay = key($arrayDay);
				$day = $arrayDay[$indexDay];
				echo "<br>________".$day;
				next($arrayDay);
			}
			next($arrayMonth);
		}
		next($arrayYear);
	}*/

	//echo "m = $m<br>y = $y";
	echo "<table border = '1' width = '100%'>";
		echo "<tr>";
			echo "<td>";
				echo calendrier($m, $y, $arrayYear);
			echo "</td>";
			echo "<td>";
				echo calendrier($m+1, $y, $arrayYear);
			echo "</td>";
			echo "<td>";
				echo calendrier($m+2, $y, $arrayYear);
			echo "</td>";
		echo "</tr>";
	
	echo "</table>";

?>
<?php
	htmlFooter();
?>