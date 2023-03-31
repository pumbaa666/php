<?php
include_once("anidbForm.php");
include_once("fonctions.js");
include_once("fonctions.php");

/*$resTest = curlGet("http://anidb.net/perl-bin/animedb.pl?adb.search=$animeTitle&show=anime");
echo "restTest<br>".$resTest;return;*/

// On commence par le titre de l'anime
$url = "http://anidb.net/perl-bin/animedb.pl?show=anime&aid=$animeID";
$read = curlGet($url);

$pattern = "/title.*::AniDB.net:: Anime - .*::/";
$regexResult = preg_match_all($pattern, $read, $out);
if($regexResult === FALSE)
	echo "Erreur avec preg_match_all : titre";
else
{
	for($i = 0; $i < $regexResult; $i++)
	{
		$startDelimiter = "AniDB.net:: Anime - ";
		$startDelimiterLen = strlen($startDelimiter);
		$start = strpos($out[0][$i], $startDelimiter) + $startDelimiterLen;
		$stop = strpos($out[0][$i], "::", $start + 1);
		$len = $stop-$start-1;
		$title = substr($out[0][$i], $start, $len);
		
		// On met tout ça dans un textarea pour pouvoir sélectionner le texte facilement
		echo "<form name = 'titleForm'>";
			echo "<textarea name='titleTextAream' rows='1' cols='80' onFocus=\"highlightAll('titleForm.titleTextAream')\">".replace($title)."</textarea>";
		echo "</form>";
	}
}

// Les titres des épisodes
$pattern = "/label title=.*/";
$regexResult = preg_match_all($pattern, $read, $out);
if($regexResult === FALSE)
	echo "Erreur avec preg_match_all : épisodes";
else
{
	$nbMax = count($regexResult); // Nb d'épisode, utile pour le padding
	$strFinal = ""; // Text final affiché dans la textarea
	for($i = 0; $i < $regexResult; $i++) // Parcour tout les titres
	{
		$episodeNumber = $startNumber + $i;
		
		// Si c'est un song, on zap
		if(strpos($out[0][$i], "animedb.pl?show=song") > -1)
			continue;
		
		// Récupération du nom
		$start = strpos($out[0][$i], ">");
		$title = substr($out[0][$i], $start+1);
		$paddedNumber = padding($episodeNumber, $nbMax);
		$strFinal .= "$paddedNumber" . $separator . lowercaseExceptFirst(replace($title)) . $apres . "\n";
	}
	
	// Textarea, idem
	echo "<form name = 'episodeForm'>";
		echo "<textarea name='episodeTextAream' rows='100' cols='80' onFocus=\"highlightAll('episodeForm.episodeTextAream')\">".$strFinal."</textarea>";
	echo "</form>";

}

?>