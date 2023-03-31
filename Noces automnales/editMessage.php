<?php
session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");
else
{
    if(!isset($_GET['id']))
        header("Location: forum.php?error=Faut entrer un numéro de message, et <b>arrêter d'essayer de bidouiller :-@</b>");
    else
    {
        echo "pas encore implémenté";
        /*$id = $_GET['id'];
        include("connectdb.php");
        $str = "select fk_user_id from forum where pk_forum_id=$id";
        $req = mysql_query($str);
        $resultat = mysql_fetch_array($req);
        if($resultat['fk_user_id'] != $_SESSION['userId'])
            header("Location: forum.php?error=Tu n'as pas le droit d'effacer ce message, <b>arrête d'essayer de bidouiller :-@</b>");
        else
        {
            $strDelete = "delete from forum where fk_forum_id=$id or pk_forum_id=$id";
            $reqDelete = mysql_query($strDelete);
            header("Location: forum.php?message=Message supprimé");
        }*/
    }
}
?>
