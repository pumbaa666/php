<?php
	require_once("fonction.php");

	$date = date("Y-m-d", time());
	
	require_once("connectdb.php");
	$strUser = "select PK_USER_ID from USER";
	$reqUser = mysql_query($strUser);
	$nbSecondPerDay = 24*60*60;
	while($resultatUser = mysql_fetch_array($reqUser)) // Parcours tout les user
	{
		$user = $resultatUser['PK_USER_ID'];
		
		$strWantMail = "select SEND_MAIL_BEFORE_DATE from SETTINGS where pk_fk_user_id = '$user'";
		$reqWantMail = mysql_query($strWantMail);
		$resultatWantMail = mysql_fetch_array($reqWantMail);
		if($resultatWantMail['SEND_MAIL_BEFORE_DATE'] == 0) // L'utilisateur ne d�sire pas recevoir le mail de notification
			continue;
		
		$strDateUser = "select DATE from VENDREDI where FK_USER_ID = '$user' and DATE >= '$date' order by DATE";
		$reqDateUser = mysql_query($strDateUser);
		$resultatDateUser = mysql_fetch_array($reqDateUser);
		$nextDate = $resultatDateUser['DATE']; // S�lectionne la prochaine date (dans le futur uniquement) o� ce user c'est inscrit pour apporter les croissant

		if($nextDate != "") // Si le user est inscrit dans le futur
		{
			$strNbDay = "select NB_DAY_BEFORE_MAIL from SETTINGS where PK_FK_USER_ID = '$user'";
			$reqNbDay = mysql_query($strNbDay);
			$resultatNbDay = mysql_fetch_array($reqNbDay);
			$nbDayBefore = $resultatNbDay['NB_DAY_BEFORE_MAIL']; // Nombre de jour � l'avance o� il aimerait �tre pr�venu
			$nextDate = strtotime($nextDate);
			$nextDateBdd = date("Y-m-d", $nextDate);
			
			
			$year = date("Y", $nextDate);
			$month = date("m", $nextDate);
			$day = date("d", $nextDate);
			$timestamp = mktime(0, 0, 0, $month, $day, $year); // Cr�e un timestamp de la date prochaine d'inscription du user
			if($timestamp - $nbDayBefore*$nbSecondPerDay <= time()) // Calcule si le user doit �tre pr�venu
			{
				$strLastAlert = "select '1' from ALERT_MAIL where FK_USER_ID = '$user' and DATE_CROISSANT = '$nextDateBdd'";
				$reqLastAlert = mysql_query($strLastAlert);
				$resultatLastAlert = mysql_fetch_array($reqLastAlert);
				$lastAlert = $resultatLastAlert[0];

				if($lastAlert == '') //Si le user n'a pas �t� d�j� pr�venu
				{
					$headers = 'MIME-Version: 1.0'."\n";
					$headers .='From: "No Reply"<no-reply@olympe-network.com>'."\n";
				    $headers .='Reply-To: loic.correvon@elca.ch'."\n";
				    $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
				    $headers .='Content-Transfer-Encoding: 8bit';
					
					$strMail = "select EMAIL from USER where PK_USER_ID = '$user'";
					$reqMail = mysql_query($strMail);
					$resultatMail = mysql_fetch_array($reqMail);
					$mail = $resultatMail['EMAIL'];

					$message = "Salut, n'oublie pas d'apporter les croissants le ".date("d \d\u m Y", $nextDate)."

Lo�c Correvon

P.S. Si tu ne veux plus recevoir de mail d'avertissement, rends toi dans le menu 'Pr�f�rences' et d�coche 'Recevoir un mail...'
Et si tu veux �tre pr�venu plus t�t ou plus tard, change le chiffre sur la m�me ligne.

Ceci est un mail automatique.

http://pumbaa.olympe-network.com/croissants/";
					if(mail($mail, 'Alerte croissants !', $message, $headers))
					{
						// inserer check dans nouvelle table
						$strInsertCheck = "insert into ALERT_MAIL (fk_user_id, date_croissant) values ('$user', '$nextDateBdd')";
						$reqInsertCheck = mysql_query($strInsertCheck);
						
						// Si il y a un probl�me lors de l'ex�cution de la requ�te
						if(!$reqInsertCheck)
						{
							// Insert un log dans la bdd
							$error = mysql_error();
							$strErrorInsert = "insert into ALERT_MAIL_ERROR (DATE, ERROR) values ('$nextDate', '$error')";
							$reqErrorInsert = mysql_query($strErrorInsert);
						}
						
						// Envoie une notification � l'admin
						$message = "Une alerte a �t� envoy�e � $user : 

------------------------------

".$message;
						
						mail('loic.correvon@elca.ch', 'Alerte croissants !', $message, $headers);
					}
					else
					{
						$strErrorInsert = "insert into ALERT_MAIL_ERROR (DATE, ERROR) values ('$nextDate', 'Impossible d'envoyer un mail � $mail<br/><br/>Message : <br/>$message')";
						$reqErrorInsert = mysql_query($strErrorInsert);
					}
				}
			}
		}
	}
?>
