<?php
// Affiche le message d'erreur si il y en a un
if(isset($_GET['error']))
	echo "<font color = 'red'>".$_GET['error']."</font><br/>";
$header = "";
	
// Récupère le animeTitle
$animeTitle = "";
if(isset($_GET['animeTitle']))
	$animeTitle = $_GET['animeTitle'];
elseif(isset($_POST['animeTitle']))
	$animeTitle = $_POST['animeTitle'];

// Récupère le apres
$apres = "";
if(isset($_GET['apres']))
	$apres = $_GET['apres'];
elseif(isset($_POST['apres']))
	$apres = $_POST['apres'];

// Récupère le separator
$separator = " - ";
if(isset($_GET['separator']))
	$separator = $_GET['separator'];
elseif(isset($_POST['separator']))
	$separator = $_POST['separator'];

// Récupère le animeID
if(isset($_GET['animeID']))
	$animeID = $_GET['animeID'];
else
{
	$animeID = "1";
	if(isset($_POST['animeID']))
		$animeID = $_POST['animeID'];
	if(!is_numeric($animeID))
		$header = "Le numéro d'anime doit être numérique";
}

// Récupère le startNumber
if(isset($_GET['startNumber']))
	$startNumber = $_GET['startNumber'];
else
{
	$startNumber = "1";
	if(isset($_POST['startNumber']))
		$startNumber = $_POST['startNumber'];
	if(!is_numeric($startNumber))
		$header = "Le numéro de commencement doit être numérique";
}

if($header != "")
{
	header("Location:anidbForm.php?animeID=$animeID&startNumber=$startNumber&separator=$separator&animeTitle=$animeTitle&apres=apres&error=".$header);
	return;
}

// Menu
echo "<a href = '.'>Retour</a>";

// Formulaire
echo "<form action = 'result.php' method = 'post'>";
	//echo "Titre du manga : <input type = 'text' name = 'animeTitle' value = '$animeTitle'/> ou ";
	echo "ID : <input type = 'text' name = 'animeID' value = '$animeID'/><br/>";
	echo "Commencer à : <input type = 'text' name = 'startNumber' value = '$startNumber'/><br/>";
	echo "Entre n° et titre : <input type = 'text' name = 'separator' value = '$separator'/><br/>";
	echo "Après : <input type = 'text' name = 'apres' value = '$apres'/><br/>";
	echo "<input type = 'submit' value = 'Parser les titres' name = 'do.search'/>";
echo "</form>";
?>