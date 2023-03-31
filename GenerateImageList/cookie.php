<?php

$cookies = array("widthMax", "heightMax", "nbImgByColumn", "widthGap", "heightGap", "format", "transparent", "color", "helpField", "formParamField", "orderBy");

/**
Sauve tout les paramètres passé par POST dans des cookie.
Durée : 30 jours
*/
function saveCookie()
{
	global $cookies;

	$duration = time()+60*60*24*30;
	foreach($cookies as $cookie)
		setcookie($cookie, $_POST[$cookie], $duration);
}

function deleteCookies()
{
	global $cookies;
	
	$duration = time()-3600;
	foreach($cookies as $cookie)
	{
		setcookie($cookie, false, $duration);
		unset($_COOKIE[$cookie]);
	}
}

/**
Retourne le cookie si il existe.
Sinon retourne une chaine vide.
*/
function getCookie($cookieName)
{
	if(isset($_COOKIE[$cookieName]))
		return $_COOKIE[$cookieName];
	return "";
}

/**
Retourne le cookie si il existe.
Sinon retourne $defaultValue
*/
function getCookieOrValue($cookieName, $defaultValue)
{
	$return = getCookie($cookieName);
	if($return == "")
		return $defaultValue;
	return $return;
}

/**
Supprime les cookies sur la variable 'function' est passée en GET et vaut 'clear'
*/
if(isset($_GET['function']) && $_GET['function'] == 'clear')
{
	deleteCookies();
	header("Location:index.php");
}
?>