<?php
	require_once("fonction.php");

	$date = date("Y-m-d", time());
	
	require_once("connectdb.php");
	
	$headers = 'MIME-Version: 1.0'."\n";
	$headers .='From: "No Reply"<no-reply@olympe-network.com>'."\n";
	$headers .='Reply-To: loic.correvon@elca.ch'."\n";
	$headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
	$headers .='Content-Transfer-Encoding: 8bit';

	$strUser = "select u.pk_user_id, u.email, v.date
				from USER u
				inner join SETTINGS s on s.pk_fk_user_id = u.pk_user_id
				inner join VENDREDI v on v.fk_user_id = u.pk_user_id
				left join ALERT_MAIL a on a.fk_user_id = u.pk_user_id
				where s.send_mail_before_date = 1
				and DATE_SUB(v.date, INTERVAL (s.nb_day_before_mail) DAY) <= NOW()
				and v.date > NOW()
				and a.date_croissant is null";
	/*$strUser = "select u.pk_user_id, u.email, v.date
					from USER u
					inner join SETTINGS s on s.pk_fk_user_id = u.pk_user_id
					inner join VENDREDI v on v.fk_user_id = u.pk_user_id
					left join ALERT_MAIL a on a.fk_user_id = u.pk_user_id
					where s.send_mail_before_date = 1
					and DATE_SUB(v.date, INTERVAL (s.nb_day_before_mail) DAY) > NOW()
					and a.date_croissant is null
					order by v.date
					LIMIT 0 , 1";*/
	$reqUser = mysql_query($strUser);
	while($resultatUser = mysql_fetch_array($reqUser))
	{
		$user = $resultatUser['pk_user_id'];
		$mail = $resultatUser['email'];
		$nextDate = $resultatUser['date'];

		$message = "Salut, n'oublie pas d'apporter les croissants le ".date("d \d\u m Y", strtotime($nextDate))."

Loïc Correvon

P.S. Si tu ne veux plus recevoir de mail d'avertissement, rends toi dans le menu 'Préférences' et décoche 'Recevoir un mail...'
Et si tu veux être prévenu plus tôt ou plus tard, change le chiffre sur la même ligne.

Ceci est un mail automatique.

http://croissants.o-n.fr/

http://pumbaa.olympe-network.com/croissants/";

		if(mail($mail, 'Alerte croissants !', $message, $headers))
		{
			// inserer check dans nouvelle table
			$strInsertCheck = "insert into ALERT_MAIL (fk_user_id, date_croissant) values ('$user', '$nextDate')";
			$reqInsertCheck = mysql_query($strInsertCheck);
			
			// Si il y a un problème lors de l'exécution de la requête
			if(!$reqInsertCheck)
			{
				// Insert un log dans la bdd
				$error = mysql_error();
				$strErrorInsert = "insert into ALERT_MAIL_ERROR (FK_USER_ID, DATE_CROISSANT, ERROR) values ('now()', 'impossible d'envoyer un mail à ".$user." Error = ".$error".)";
				$reqErrorInsert = mysql_query($strErrorInsert);
				$message = "impossible d'envoyer un mail à ".$user;
			}
			
			// Envoie une notification à l'admin
			$message = "Une alerte a été envoyée à $user ($mail) : 

------------------------------

".$message;
			
			mail('loic.correvon@elca.ch', 'Alerte croissants !', $message, $headers);
		}
		/*else
		{
			$strErrorInsert = "insert into ALERT_MAIL_ERROR (pk_alert_mail_error_id, DATE, ERROR) values ('$user', '$nextDate', 'Impossible d'envoyer un mail à $mail<br/><br/>Message : <br/>$message')";
			$reqErrorInsert = mysql_query($strErrorInsert);
		}*/
	}
?>
