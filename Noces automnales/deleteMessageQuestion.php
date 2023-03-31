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
        $id = $_GET['id'];
        $isReponse = 'N';
        if(isset($_GET['isReponse']) && $_GET['isReponse'] == 'Y')
            $isReponse = 'Y';
        if(isset($_GET['reponse']))
        {
            if($_GET['reponse'] == 'Y')
            {
                include("connectdb.php");
                $str = "select fk_user_id from forum where pk_forum_id=$id";
                $req = mysql_query($str);
                $resultat = mysql_fetch_array($req);
                require_once("fonction.php");
                if(!isAdmin() && $resultat['fk_user_id'] != $_SESSION['userId'])
                    header("Location: forum.php?error=Tu n'as pas le droit d'effacer ce message, <b>arrête d'essayer de bidouiller :-@</b>");
                else
                {
                    $strDelete = "delete from forum where fk_forum_id=$id or pk_forum_id=$id";
                    $reqDelete = mysql_query($strDelete);
                    if($isReponse)
                    {
                        $strRedirect = "select pk_forum_id from forum where fk_forum_id=$id group by pk_forum_id";
                        $reqRedirect = mysql_query($strRedirect);
                        $resultatRedirect = mysql_fetch_array($reqRedirect);
                        //echo $strRedirect;
                        header("Location: messageForum.php?id=".$resultatRedirect['pk_forum_id']."&message=Message supprimé");
                    }
                    else
                        header("Location: forum.php?message=Message supprimé");
                }
            }
        }
        else
        {
            echo "<center>Voulez-vous vraiment <font color = 'red'>supprimer</font> ce sujet ?<br>";
            echo "<a href = 'deleteMessageQuestion.php?id=$id&reponse=Y&isReponse=$isReponse'>Oui</a> - <a href = 'forum.php'>Non</a></center>";
            require_once("messageForum.php");
        }
    }
}
?>
