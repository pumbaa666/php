<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	htmlHeader();

	echo "<b><center>";
	printJellyTime();
	echo "<font color = 'red' size = '5'>CROISSANTS-TIME !!!</font>";
	printJellyTime();
	echo "</center></b>";
	htmlFooter();
	
	function printJellyTime()
	{
		echo "<img src = './images/nana2.gif' width='330' height='180' border = '0' />";
	}
?>