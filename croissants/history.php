<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();

	if(!isset($_GET['date']))
	{
		header("Location:mainFrame.php?page=liste&error=Choisissez une date pour voir l'historique");
		return;
	}
	$date = date("Y-m-d", strtotime($_GET['date']));
	$historyType = array(
		1 => "<font color = 'green'>S'est inscrit en tant que principal</font>", 
		2 => "<font color = 'lightgreen'>S'est inscrit en tant que sauveteur</font>", 
		-1 => "<font color = 'red'>S'est désinscrit en tant que principal</font>", 
		-2 => "<font color = 'pink'>S'est désinscrit en tant que sauveteur</font>"
	);
	
	require_once("connectdb.php");
	$str = "select FK_USER_ID, DATE_FROM_CHANGE, SUBSCRIBE from HISTORY where DATE = '$date' order by DATE_FROM_CHANGE";
	$req = mysql_query($str);
	
	echo "<center><b><font size = '5'>".$_GET['date']."</font></b></center><br/><br/>";

	
	echo "<table border = '1'>";
	while($resultat = mysql_fetch_array($req))
	{
		echo "<tr>";
		echo "<td>".$resultat['FK_USER_ID']."</td>";
		echo "<td>".$historyType[$resultat['SUBSCRIBE']]."</td>";
		echo "<td>".date("\l\e d.m.Y à H:i:s", strtotime($resultat['DATE_FROM_CHANGE']))."</td>";
		echo "</tr>";
	}
	echo "</table>";
	
?>
<?php
	htmlFooter();
?>