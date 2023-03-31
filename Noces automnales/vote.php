<?php
session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");

$userId = $_SESSION['userId'];
$la5 = 'Non';
$dors5 = '-';
$bouffe5 = '-';
$la13 = 'Non';
$dors13 = '-';
$bouffe13 = '-';

if(isset($_POST['checkLa5']))
{
    $la5 = 'Oui';
    $bouffe5 = $_POST['radio5'];
    if(isset($_POST['dodoLa5']))
        $dors5 = 'Oui';
    else
        $dors5 = 'Non';
}
if(isset($_POST['checkLa13']))
{
    $la13 = 'Oui';
    $bouffe13 = $_POST['radio13'];
    if(isset($_POST['dodoLa13']))
        $dors13 = 'Oui';
    else
        $dors13 = 'Non';
}

include("connectdb.php");
$str = "select PK_PARTICIPATION_ID from participation where FK_USER_ID=$userId";
$req = mysql_query($str);
$resultat = mysql_fetch_array($req);
if(isset($resultat['PK_PARTICIPATION_ID']))
    $str = "update participation set LA5 = '$la5', DORS5 = '$dors5', BOUFFE5 = '$bouffe5', LA13 = '$la13', DORS13 = '$dors13', BOUFFE13 = '$bouffe13'
            where FK_USER_ID = $userId";
else
    $str = "insert into participation (FK_USER_ID, LA5, DORS5, BOUFFE5, LA13, DORS13, BOUFFE13) 
            values                    ('$userId', '$la5', '$dors5', '$bouffe5', '$la13', '$dors13', '$bouffe13')";

$req = mysql_query($str);

header("Location: pageDeVote.php");

?>
