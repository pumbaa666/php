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

	require_once("connectdb.php");
	$i = -1;
	foreach($_POST['qui'] as $qui)
	{
		$i++;
		if($qui == "")
			continue;
		$date = date("Y-m-d", strtotime($_POST['date'][$i]));
		$str = "insert into VENDREDI (date, fk_user_id) values ('$date', '$qui')";
		$req = mysql_query($str);
		echo $str."<br/>";
	}
	
	echo "<br/><a href = 'mainFrame.php?page=liste&message=Base peuplée'>Retour à la liste</a>";
	//header("Location:mainFrame.php?page=liste&message=Base peuplée");
?>
<?php
	htmlFooter();
?>