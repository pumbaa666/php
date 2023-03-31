<?php
	session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();

	echo "<table border = '0' width = '100%' height = '100%'>";
		echo "<tr height = '10%'>";
			echo "<td colspan='3' width = '100%' align = 'center'>"; // Menu du haut, centré
				include("menu.php");
			echo "</td>";
		echo "</tr>";
		
		echo "<tr height = '75%' valign = 'top'>";
			echo "<td width = '0%'></td>";  // Menu de droite
			
			echo "<td colspan='2' width = '100%'>"; // Page principale
				if(isset($_GET['error']))
					echo "<font color = 'red'>".$_GET['error']."</font><br/>";
				if(isset($_GET['message']))
					echo "<font color = 'green'>".$_GET['message']."</font><br/>";
				if(isset($_GET['page']))
					include($_GET['page'].".php");
			echo "</td>";
		echo "</tr>";
		
		$admin = "";
		if(isAdmin())
			$admin = " (admin)";
		echo "<tr height = '100%'>"; // Pied de page
			echo "<td></td>";
			echo "<td align = 'left' valign = 'bottom'>";
				if(date('w', time()) == 5)
				{
					//echo "<a href = 'startCroissant.php'>"; // TODO remettre et finir de coder
					include("croissantTime.php");
					//echo "</a><br/><br/>";
				}

			echo "</td>";
			echo "<td width = '10%' align = 'right' valign = 'bottom'><font size = '2'>Loggé en tant que ".$_SESSION['userId']."$admin</font></td>";
		echo "</tr>";
	echo "</table>";
	htmlFooter();
?>