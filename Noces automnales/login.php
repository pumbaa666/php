<?php
session_start();

$login = $_POST['login'];
$password = $_POST['password'];
$error = "?";

if($login == "")
    $error .= "login=1&";
if($password == "")
    $error .= "password=1";

if($error != "?")
    header("Location: index.php$error");
else
{
    include("connectdb.php");
    $str = "select PK_USER_ID, PASSWORD from user where LOGIN='$login'";
    $req = mysql_query($str);
    $resultat = mysql_fetch_array($req);
    //echo "str = ".$str;
    if($resultat['PASSWORD'] == $password)
    {
        $_SESSION['userId'] = $resultat['PK_USER_ID'];
        header("Location: accueil.php");
    }
    else
    {
        $str = "select count(LOGIN) as EXIST from user where LOGIN='".strtolower($login)."'";
        $req = mysql_query($str);
        $resultat = mysql_fetch_array($req);
        if($resultat['EXIST'] == 1)
            header("Location: index.php?password=2");
        else
            header("Location: index.php?login=2");
    }
}

?>