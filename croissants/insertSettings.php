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
	$str = "select 'insert into SETTINGS (pk_fk_user_id, nb_day_before_mail, send_mail_before_date, nb_day_to_see, email_visible, receive_notification) values (''pk_user_id'', 1, 1, 10, 0, 1)' from USER";
	$req = mysql_query($str);
	while($resultat = mysql_fetch_array($req))
	{
		echo $resultat[0]."<br/>";
	}

?>
<?php
	htmlFooter();
?>