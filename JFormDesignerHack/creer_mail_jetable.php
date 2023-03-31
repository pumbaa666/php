<html><head><title>Crée un mail jetable</title></head>

<body>

<?php
/* Pour activer CURL : 
	- Double clique sur l'icone easyPHP dans le systray
	- Menu --> Configuration --> PHP
	- Décommenter la ligne ";extension=php_curl.dll" (enlever le ;)
	- Redémarrer easyPHP (F5)
 */
 
/* Configuration */
$email = "loic.correvon@gmail.com"; // Votre adresse email réelle
$time = 3600; // Temps en secondes que va durer l'adresse jetable (Valeures possibles : 3600 (1h), 86400 (1 jour), 604800 (1 semaine), 2592000 (1mois)
$user_agent = "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)";

/* Ne modifiez pas plus loin */

	/* Crée une adresse email jetable */
	$url = 'http://www.jetable.org/fr/confirm';
	$params = "email=$email&time=$time";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

	$result=curl_exec($ch);
	curl_close($ch);
	
	if(strpos($result, "Trop d'alias") > 0)
	{
		echo "T'as un peu abusé, changes d'adresse mail";
		return;
	}
	
	preg_match('`[a-z0-9]*@jetable\.[a-z0-9]*`', $result, $mailJetable);
	$mailJetable = $mailJetable[0];
	
	$time = time();
	mkdir("results/results_$time");
	
	echo "résultat dans results/results_$time<br>Mail jetable : $mailJetable<br>";
	
	$ok = "KO";
	if(strpos($result, "Confirmation") > 0)
		$ok = "ok";

	$jetableResult = fopen("results/results_$time/jetableResult_$ok.html", "w");
	fputs($jetableResult, $result);
	fclose($jetableResult);
	
	/* Demande une clé à JFormDesigner */
	$firstName = randomString(10);
	$lastName = randomString(10);
	$company = randomString(10);
	$url = 'http://www.formdev.com/eval/';
	$params = "first_name=$firstName&last_name=$lastName&company=$company&country=Australia&email=$mailJetable&submit=Request License Key";
	echo "JForm params : $params";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

	$result=curl_exec($ch);
	curl_close($ch);
	
	$ok = "KO";
	if(strpos($result, "It has been sent to your e-mail address <b>$mailJetable</b>.") > 0)
		$ok = "ok";
	
	$jformResult = fopen("results/results_$time/jformResult_$ok.html", "w");
	fputs($jformResult, $result);
	fclose($jformResult);

	function randomString($nbChar)
	{
		if($nbChar <= 0)
			return "null";
			
		$charToRandom = array();
		for($i = 65; $i <= 90; $i++) // Lettres MAJ
			$charToRandom[] = chr($i);
		for($i = 97; $i <= 122; $i++) // Lettres MIN
			$charToRandom[] = chr($i);
		$nbCharToRandom = count($charToRandom);
	
		$result = "";
		for($i = 0; $i < $nbChar; $i++)
			$result .= $charToRandom[rand(0, $nbCharToRandom-1)];
		return $result;
	}
?>
</body>
</html>