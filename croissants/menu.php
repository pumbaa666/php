<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
?>
<html><head><title>Pas de pitié pour les croissants !!!</title>
<link rel="alternate" type="application/rss+xml" href="./rss.xml" title="Votre titre">
</head><body>


<a href = "mainFrame.php?page=accueil">Accueil</a> 
<a href = "mainFrame.php?page=news">News</a> 
<a href = "mainFrame.php?page=liste">Liste</a> 
<a href = "mainFrame.php?page=settings">Préférences</a> 
<a href = "mainFrame.php?page=statistiques">Statistiques</a>
<?php
	if(isAdmin())
	{
		echo "<a href = 'mainFrame.php?page=todo'>TODO</a> ";
		echo "<a href = 'mainFrame.php?page=mailEverybody'>Mail collectif</a> ";
		echo "<a href = 'mainFrame.php?page=adminUser'>Administration</a> ";
		echo "<a href = 'mainFrame.php?page=alertMail'>Alerte mail</a> ";
		//echo "<a href = 'QoQa/index.php'>Qoqa</a> ";
		//echo "<a href = 'mainFrame.php?page=peuplage'>Peuplage</a> ";
		//echo "<a href = 'mainFrame.php?page=insertSettings'>Insert Settings</a> ";
	}
?>
<a href = "logout.php">Logout</a> 
<a type="application/rss+xml" href="./rss.xml"><img src = "./images/rss.gif" border = "0" /></a> 
<?php
	htmlFooter();
?>