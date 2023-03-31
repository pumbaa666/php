<?php
	if(!session_id())
		session_start();
	require_once("fonction.php");
	require_once("connectdb.php");
	isAuthorized();
	if(!isAdmin())
	{
		header("Location:error.php?type=autorisationAdmin");
		return;
	}
	htmlHeader();

	$orderBy = "PK_USER_ID";
	$sens = "ASC";
	if(isset($_GET['orderBy']))
	{
		$orderBy = $_GET['orderBy'];
		
		if(isset($_GET['sens']))
			$sens = mysql_real_escape_string($_GET['sens'], $db);
	}
		
	$str = "select u.PK_USER_ID, u.EMAIL,
			(
				select max(DATE) as DATE
				from LOGIN_IP
				where FK_USER_ID = u.PK_USER_ID
			)
			from USER u
			order by $orderBy $sens";
	$req = mysql_query($str);
	
	if($sens == "ASC")
		$sens = "DESC";
	else
		$sens = "ASC";
	
	echo "<table border = '1'>";
	echo "<tr>";
		echo "<td><b><a href = 'mainFrame.php?page=adminUser&orderBy=PK_USER_ID&sens=$sens'>Visa</a></b></td>";
		echo "<td><b>Login as</b></td>";
		echo "<td><b>Login as (sans droits admin)</b></td>";
		echo "<td><a href = 'mainFrame.php?page=adminUser&orderBy=EMAIL&sens=$sens'><b>EMail</a></b></td>";
		echo "<td><b>Supprimer</b></td>";
		echo "<td><b>Valider</b></td>";
		echo "<td><b>Set psw (password12)</b></td>";
		echo "<td><b><a href = 'mainFrame.php?page=adminUser&orderBy=LAST_CONNEXION&sens=$sens'>Dernière connexion</a></b></td>";
		echo "<td><b>Dernière IP</b></td>";
	echo "</tr>";
	
	echo "<form method = 'post' action='setNewPsw.php'>";
	while($resultat = mysql_fetch_array($req))
	{
		$user = $resultat['PK_USER_ID'];
		$mail = $resultat['EMAIL'];
		echo "<tr>";
			echo "<td>$user</td>";
			echo "<td><a href = 'loginAs.php?user=$user'>Login as</a></td>";
			echo "<td><a href = 'loginAs.php?user=$user&right=none'>Sans droits</a></td>";
			echo "<td><a href = '$mail'>$mail</a></td>";
			echo "<td><a href = 'deleteUser.php?user=$user'>Supprimer</a></td>";
			
			echo "<td>";
				$strValid = "select '1' from WAIT_CONFIRM where PK_FK_USER_ID = '$user'";
				$reqValid = mysql_query($strValid);
				$resultatValid = mysql_fetch_array($reqValid);
				if($resultatValid[0] != '')
					echo "<a href = 'validateAccount.php?user=$user'>Valider le compte</a>";
			echo "</td>";
			
			echo "<td>";
				echo "<input type = 'submit' name = 'newPsw' value = '".$user."' />";
			echo "</td>";
			
			$strIP = "select IP, DATE from LOGIN_IP where fk_user_id = '$user' order by date desc";
			$reqIP = mysql_query($strIP);
			$resultatIP = mysql_fetch_array($reqIP);
			echo "<td>".date('d F Y - H:i:s', strtotime($resultatIP['DATE']))."</td>";
			
			$lastIP = $resultatIP[0];
			echo "<td><a href = 'mainFrame.php?page=ip&user=$user'>$lastIP</a></td>";
		echo "</tr>";
	}
	echo "</table>";
	

?>
<?php
	htmlFooter();
?>