<?php
session_start();
if(isset($_GET['autoLogin']))
{
	$login = $_COOKIE['user_visa'];
	//echo "<br>autoLogin : login = $login";
	$password = $_COOKIE['user_password'];
}
else
{
	$login = $_POST['login'];
	//echo "<br>autoLogin else : login = $login";
	$password = md5($_POST['password']);
}
$error = "?error=";

if($login == "")
    $error .= "Veuillez entrer un login";
if($password == "")
    $error .= "<br/>Veuillez entrer un password";
require_once("fonction.php");

if($error != "?error=")
{
	if(isset($_GET['autoTry']))
		isAuthorized(1);
	
	destroyCookies();
    header("Location: index.php$error");
}
else
{
    include("connectdb.php");
	//echo "<br>before mysql_escape_string : login = $login";
	$login = mysql_real_escape_string($login, $db);
	//echo "<br>mysql_escape_string : login = $login";
    $str = "select PK_USER_ID, PASSWORD from USER where upper(PK_USER_ID)=upper('$login')";
	//echo "<br>$str<br>";
    $req = mysql_query($str);
	$resultat = mysql_fetch_array($req);
	//echo mysql_error();

    if(trim($resultat['PASSWORD']) == trim($password))
    {
		if(isset($_POST['loginAuto']) && $_POST['loginAuto'] == "on")
		{
			$cookie_ttl = time() + 60*60*24*365;
			setcookie('user_visa', $login, $cookie_ttl);
			setcookie('user_password', $password, $cookie_ttl);
		}
		$user = $resultat['PK_USER_ID'];
		$date = date("Y-m-d H:i:s",time());
        $_SESSION['userId'] = $user;
		if($user == "ADM")
			$_SESSION['admin'] = $password;
		else
			$_SESSION['admin'] = '';
		
		//$str = "update USER set LAST_CONNEXION = '$date' where PK_USER_ID = '$user'";
		//$req = mysql_query($str);
		
		$strPageAccueil = "select PAGE_ACCUEIL from SETTINGS where PK_FK_USER_ID = '$user'";
		$reqPageAccueil = mysql_query($strPageAccueil);
		$resultatPageAccueil = mysql_fetch_array($reqPageAccueil);
		$page = $resultatPageAccueil['PAGE_ACCUEIL'];
		
		/* IP */
		$ip = $_SERVER["REMOTE_ADDR"];
		$strIP = "insert into LOGIN_IP (fk_user_id, ip, date) values ('$user', '$ip', '$date')";
		$reqIP = mysql_query($strIP);
		
		if($page == '')
			$page = 'Accueil';
        header("Location: mainFrame.php?page=$page");
    }
    else
    {
        $str = "select count(PK_USER_ID) as EXIST from USER where upper(PK_USER_ID)=upper('$login')";
        $req = mysql_query($str);
        $resultat = mysql_fetch_array($req);
		destroyCookies();
        if($resultat['EXIST'] == 1)
            header("Location: index.php?error=Le password est incorrect&user=".$login);
        else
            header("Location: index.php?error=Cet utilisateur n'existe pas");
    }
}

?>