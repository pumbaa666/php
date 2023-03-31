<?php
/* Formating */
function formatDateTime($dateTime)
{
    $dateTime = split(" ",$dateTime);
    $date = $dateTime[0];
    $dateSplit = split("-", $date);
    $date = $dateSplit[2].".".$dateSplit[1].".".$dateSplit[0];
    $time = $dateTime[1];
    $timeSplit = split(":", $time);
    $time = $timeSplit[0]."h".$timeSplit[1];
    
    return $date." - ".$time;
}

function formatMessage($text)
{
    return str_replace("\'","'",$text);
}

function printUserWithStatLink($user)
{
	echo "<a href = 'mainFrame.php?page=statUser&user=$user'>$user</a>";
}

function substrDigit($string, $nbDigit)
{
	$index = strpos($string, ".");
	if($index === FALSE)
		return $string;
	
	return substr($string, 0, $index+$nbDigit+1);
}

/* Security */
function checkPasswordSecurity($psw)
{
	if(strlen($psw) < 6)
		return "too_short";
	if($psw == "password1")
		return "password1";
	return "";
}

function isMailCorrect($mail)
{
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $mail);
}

function isAdmin()
{
	if(!session_id())
		session_start();
    if(isset($_SESSION['admin']))
	    if($_SESSION['admin'] != '')
		{
			require_once('connectdb.php');
			$str = "select PASSWORD from USER where PK_USER_ID = 'ADM'";
			$req = mysql_query($str);
			$resultat = mysql_fetch_array($req);
			//echo "<br>'".$resultat['PASSWORD'] ."'<br>". $_SESSION['admin']."<br>";
			if(trim($resultat['PASSWORD']) == $_SESSION['admin'])
				return TRUE;
		}
    return FALSE;
}

function isAuthorized($autoTry = 0)
{
	if(!isset($_SESSION['userId']))
	{
		if($autoTry == 0)
		{
		    header("Location: login.php?autoLogin=1&autoTry=1");
			return;
		}
	    header("Location: error.php?type=autorisation");
		return;
	}
}

function getUnauthorizedChar()
{
	return array('"', '\'', '<', '>', '&', ';', '´', '`', '|', '¦');
}

function checkField($field, $size, $unauthorizedChar)
{
	$debug = false;
	
	if($debug)
		echo "field : $field / check length<br/>";
	if(strlen($field) > $size)
		return FALSE;
			
	if($debug)
		echo "check char<br/>";
	foreach ($unauthorizedChar as $badChar)
	{
		if($debug)
			echo "badChar = ".$badChar." / ".stripos($field, $badChar)."<br/>";
		if(stripos($field, $badChar) !== false)
			return FALSE;
	}
		
	return TRUE;
}

function isFilled($field)
{
	if(isset($field))
		if($field != '')
			return TRUE;
	return FALSE;
}

/* HTML / XML */
function htmlHeader()
{
	echo "<html><head><title>Pas de pitié pour les croissants !!!</title><link rel='stylesheet' type='text/css' href='testCal/styles.css'></head><body>";
}

function htmlFooter()
{
	echo "</body></html>";
}

function destroyCookies()
{
	setcookie('user_visa', "");
	setcookie('user_password', "");
}

function rssHeader()
{
	return 
"<?xml version=\"1.0\" encoding=\"windows-1252\"?>
<rss version=\"2.0\">
<channel>
	<title>Croissants-ELCA</title>     
	<link>http://127.0.0.1/croissants/mainFrame.php?page=liste</link>
	<language>fr</language>
	<webMaster>loic.correvon@elca.ch</webMaster>
	<pubDate>21.08.2009</pubDate>
	<description>Tenez-vous informé des évolutions du planning des croissants</description>\n\n
";
}

function updateXMLFile($user, $vendredi_id, $title, $link, $description, $date, $nbOldNews = 20)
{
	insertIntoBDD($user, $vendredi_id, $title, $link, $description, $date);
	$fp = fopen("rss.xml","w");
	fwrite($fp, rssHeader());
	createXMLFile($fp, $nbOldNews);
	fwrite($fp, rssFooter()); 
	fclose($fp);
}

function insertIntoBDD($user, $vendredi_id, $title, $link, $description, $date)
{
	$dateRss = date("Y-m-d H:i:s");
	$str = "insert into RSS (fk_user_id, fk_vendredi_id, title, link, description, date)
			values ('$user', '$vendredi_id', '$title', '$link', '$description', '$dateRss')";

	//$req = mysql_query($str);
	//$resultat = mysql_fetch_array($req);
}

function createXMLFile($fp, $nbOldNews)
{
	/* Recrée le fichier. Lis les $nbOldNews dernières inscriptions */	
	$str = "select * from RSS order by date DESC limit 0,$nbOldNews";
	$req = mysql_query($str);
	while($resultat = mysql_fetch_array($req))
	{
		fwrite($fp, "\t<item>\n");
		fwrite($fp, "\t\t<title>".$resultat['title']."</title>\n");
		fwrite($fp, "\t\t<link>".$resultat['link']."</link>\n");
		fwrite($fp, "\t\t<description>".$resultat['description']."</description>\n");
		fwrite($fp, "\t\t<pubDate>".$resultat['date']."</title>\n");
		fwrite($fp, "\t</item>\n\n");
	}
}

function rssFooter()
{
	return
"
</channel>
</rss>
";
}

/* Date */
function getEveryFridayOfYear($year, $monthStart=1, $dayStart=1)
{
	//echo $month." ".$day;
	//return false;
	$fridayOfYear = array();
	$index = 0;
	for($month = $monthStart; $month <= 12; $month++) // Parcours chaque mois
	{
		$fridayOfMonth = getEveryFridayOfMonth($year, $month, $dayStart); // Retourne tout les vendredi du mois
		foreach($fridayOfMonth as $friday) // Ajoute tout les vendredi du mois à la liste complète
		{
			$fridayOfYear[$index] = $friday;
			$index++;
		}
		$dayStart = 1; // Le jour de départ n'est valable que le 1er mois
	}
	
	return $fridayOfYear;
}

function getEveryFridayOfMonth($year, $month, $day=1)
{
	$month = mktime(0, 0, 0, $month, $day, $year);

	$fridayOfMonth = array();
	$nbResult = 0;
	
	$days_in_month = date('t', $month);
	$mY = date('m-Y', $month);
	for ($j=$day; $j<=$days_in_month; $j++)
	{
		$day = strtotime("$j-$mY", $month);
		if(date('w', $day) == 5)
		{
			$fridayOfMonth[$nbResult] = date('d.m.Y', $day);
			$nbResult++;
		}
	}
	
	return $fridayOfMonth;
}

function isFridayFree($friday, $field='FK_USER_ID')
{
	return whoIsSubscribed($friday, $field) == "";
}

function whoIsSubscribed($friday, $field='FK_USER_ID')
{
	require_once("connectdb.php");
	
	$dbDate = date("Y-m-d", strtotime($friday));
	$str = "select $field from VENDREDI where DATE = '$dbDate'";
	$req = mysql_query($str);
	$result = mysql_fetch_array($req);
	return $result[0];
}

/* Divers */
GLOBAL $charToRandom;
$charToRandom = array();
for($i = 48; $i <= 57; $i++) // Chiffres
	$charToRandom[] = chr($i);
for($i = 65; $i <= 90; $i++) // Lettres MAJ
	$charToRandom[] = chr($i);
for($i = 97; $i <= 122; $i++) // Lettres MIN
	$charToRandom[] = chr($i);

GLOBAL $nbCharToRandom;
$nbCharToRandom = count($charToRandom);

function randomString($nbChar)
{
	if($nbChar <= 0)
		return null;
		
	$result = "";
	for($i = 0; $i < $nbChar; $i++)
		$result .= $GLOBALS['charToRandom'][rand(0, $GLOBALS['nbCharToRandom']-1)];
	return $result;
}

?>