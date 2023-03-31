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

	if(!isset($_GET['user']))
	{
		header("Location:mainFrame.php?page=adminUser&error=Il faut le visa du user, naab");
		return;
	}
	
	$user = $_GET['user'];
	echo "<center><b><font size = '5'>$user</font></b></center><br/><br/>";
	
	require_once("connectdb.php");
	$strIP = "select date, ip from LOGIN_IP where fk_user_id = '$user' order by date";
	$reqIP = mysql_query($strIP);
	while($resultatIP = mysql_fetch_array($reqIP))
	{
		$date = date("d M Y à H:i:s", strtotime($resultatIP['date']));
		$ip = $resultatIP['ip'];
		echo "$date <b>$ip</b><br/>";
	}

?>
<?php
	htmlFooter();
?>