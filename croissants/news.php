<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	htmlHeader();

	echo "<table border = '1' width = '100%'>";
		echo "<tr>";
			echo "<td width = '10%'>ADM</td>";
			echo "<td width = '75%'>Merci</td>";
			echo "<td width = '15%'>12.01.2008</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan = '3'>Merci � tous de vous �tre inscrits si rapidement.<br/>N'h�sitez pas � me contacter pour me signaler un bug ou pour proposer une am�lioration<br/><br/>Lo�c</td>";
		echo "</tr>";
	echo "</table>";
?>
<?php
	htmlFooter();
?>