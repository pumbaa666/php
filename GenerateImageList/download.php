<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Générateur de mosaïque - Image finale</title>
	<link rel="stylesheet" href="css/common.css" type="text/css" media="screen" />
	<style type="text/css"></style>
</head>

<body>
<?php
	if(!isset($_GET['url']))
		echo "Erreur, l'url de l'image n'est pas fournie";
	else
	{
		echo "<center><font size = '5'>Votre image est disponible <a href = '".$_GET['url']."'>ici</a></font><br /><br />";
		echo "<img src = '".$_GET['url']."' />";
		require_once("purge.php");
	}
	echo "<br /><br /><a href = 'index.php'>Retour</a></center>";
?>
</body>
</html>