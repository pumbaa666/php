<?php
// Affiche le message d'erreur si il y en a un
if(isset($_GET['error']))
	echo "<font color = 'red'>".$_GET['error']."</font><br/>";
$header = "";
	
// R�cup�re le animeTitle
$animeTitle = "";
if(isset($_GET['animeTitle']))
	$animeTitle = $_GET['animeTitle'];
elseif(isset($_POST['animeTitle']))
	$animeTitle = $_POST['animeTitle'];

// R�cup�re le apres
$apres = "";
if(isset($_GET['apres']))
	$apres = $_GET['apres'];
elseif(isset($_POST['apres']))
	$apres = $_POST['apres'];

// R�cup�re le separator
$separator = " - ";
if(isset($_GET['separator']))
	$separator = $_GET['separator'];
elseif(isset($_POST['separator']))
	$separator = $_POST['separator'];

// R�cup�re le animeID
if(isset($_GET['animeID']))
	$animeID = $_GET['animeID'];
else
{
	$animeID = "1";
	if(isset($_POST['animeID']))
		$animeID = $_POST['animeID'];
	if(!is_numeric($animeID))
		$header = "Le num�ro d'anime doit �tre num�rique";
}

// R�cup�re le startNumber
if(isset($_GET['startNumber']))
	$startNumber = $_GET['startNumber'];
else
{
	$startNumber = "1";
	if(isset($_POST['startNumber']))
		$startNumber = $_POST['startNumber'];
	if(!is_numeric($startNumber))
		$header = "Le num�ro de commencement doit �tre num�rique";
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
	echo "Commencer � : <input type = 'text' name = 'startNumber' value = '$startNumber'/><br/>";
	echo "Entre n� et titre : <input type = 'text' name = 'separator' value = '$separator'/><br/>";
	echo "Apr�s : <input type = 'text' name = 'apres' value = '$apres'/><br/>";
	echo "<input type = 'submit' value = 'Parser les titres' name = 'do.search'/>";
echo "</form>";
?>