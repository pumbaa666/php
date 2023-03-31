<?php
/**
 * Remplis les chiffre avec des $char à gauche pour que tout les nombres aient la même longueur
 */
function padding($strToPad, $nbMax, $char = "0")
{
	$len = strlen($nbMax);
	$diff = $len - strlen($strToPad);
	$padding = "";
	for($i = 0; $i <= $diff; $i++)
		$padding .= $char;
	return $padding . $strToPad;
}

/**
 * Remplace les caractères interdits dans les noms de fichier windows et autres
 */
function replace($str)
{
	$tab = array(
		'"' => '\'',
		'`' => '\'',
		'?' => '.',
		' : ' => ' - ',
		': ' => ' - ',
		' :' => ' - ',
		' / ' => ' - ',
		'/ ' => ' - ',
		' /' => ' - ',
		' \\ ' => ' - ',
		' \\' => ' - ',
		'\\ ' => ' - '
		);
	reset($tab);
	for($i = 0; $i < count($tab); $i++)
	{
		$index = key($tab);
		$str = str_replace($index, $tab[$index], $str);
		next($tab);
	}
	return $str;
}

/**
 * Met une majuscule au début et tout le reste en minuscule
 */
function lowercaseExceptFirst($string)
{
	return strtoupper(substr($string, 0, 1)) . strtolower(substr($string, 1));
}

/**
 * Send a GET requst using cURL
 * @param string $url to request
 * @param array $options for cURL
 * @return string
 */
function curlGet($url, $username = "", $password = "", array $options = array())
{   
    $defaults = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 0,
        CURLOPT_ENCODING => "gzip",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => "$username:$password",
        CURLOPT_TIMEOUT => 4
    );
   
    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch))
    {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    return $result;
} 
?>