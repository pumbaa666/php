<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	isAuthorized();
	if(!isAdmin())
	{
		header("Location:error.php?type=autorisationAdmin");
		return;
	}
	
	htmlHeader();

	echo "<form method='post' action='peupler.php'>";
		$everyFriday = getEveryFridayOfYear(2008, 6, 13);
		foreach($everyFriday as $friday)
		{
			$date = date("d m Y", strtotime($friday));
			echo $date." : <input type = 'text' name = 'qui[]' value = '' /><br/>";
			echo "<input type = 'hidden' name = 'date[]' value = '$friday' />";
		}
		
		echo "<br/><input type = 'submit' value = 'Envoyer' name = 'Submit' />";
	echo "</form>";
?>
<?php
	htmlFooter();
?>