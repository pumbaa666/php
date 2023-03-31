<?php
session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");
if(!isset($_GET['id']))
    header("Location: forum.php");

$id = $_GET['id'];
echo "<html><head><title>Noces Automnales</title></head><body>";
    
    include('connectdb.php');
    // 1er message
    $str = "select fk_user_id, title, text, creation_date from forum where pk_forum_id=$id";
    $req = mysql_query($str);
    $resultat = mysql_fetch_array($req);
    if($resultat['fk_user_id'] == "")
        echo "<font color = 'red'>Ce message n'existe plus</font>";
    else
    {
        if(isset($_GET['error']))
            echo "<font color = 'red'>Erreur : ".$_GET['error']."</font><br>";
        require_once('fonction.php');
        echo "<table border = '1'>";
        $strUser = "select prenom from user where pk_user_id=".$resultat['fk_user_id'];
        $reqUser = mysql_query($strUser);
        $resultatUser = mysql_fetch_array($reqUser);
        $user = $resultatUser['prenom'];
        $date = formatDateTime($resultat['creation_date']);
    
        echo "<tr>";
            echo "<td></td><td colspan = '3'><b>".$resultat['title']."</b></td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td><u>$user</u></td><td>$date</td><td>".$resultat['text']."</td><td>Droits</td>";
        echo "</tr>";
    
        $str = "select pk_forum_id, fk_user_id, text, creation_date from forum where fk_forum_id=$id order by creation_date";
        $req = mysql_query($str);
        $nbReponse = 0;
    
        while($resultat = mysql_fetch_array($req))
        {
            $nbReponse++;
            $strUser = "select pk_user_id, prenom from user where pk_user_id=".$resultat['fk_user_id'];
            $reqUser = mysql_query($strUser);
            $resultatUser = mysql_fetch_array($reqUser);
            $dateTime = formatDateTime($resultat['creation_date']);
            $user = $resultatUser['prenom'];
            echo "<tr>";
                echo "<td>$user</td>";
                echo "<td>$dateTime</td>";
                echo "<td>".$resultat['text']."</td>";
                
                echo "<td>";
                    if(isAdmin() || $resultatUser['pk_user_id'] == $_SESSION['userId'])
                    {
                        echo "<a href = 'editMessage.php?id=".$resultat['pk_forum_id']."'><img src = './images/icon_edit.png'   border = '0' title = 'Modifier votre message'></a> ";
                        echo "<a href = 'deleteMessageQuestion.php?isReponse=Y&id=".$resultat['pk_forum_id']."'><img src = './images/icon_delete.png' border = '0' title = 'Supprimer votre message'></a> ";
                    }
                echo "</td>";
                
            echo "</tr>";
        }
        echo "</table>";
        
        // update de la table user_forum
        $str = "select pk_user_forum_id from user_forum where fk_user_id=".$_SESSION['userId']." and fk_forum_id = $id";
        $req = mysql_query($str);
        //echo "str = $str";
        $resultat = mysql_fetch_array($req);
        if($resultat['pk_user_forum_id'] != "")
            $str = "update user_forum set fk_user_id=".$_SESSION['userId'].", fk_forum_id = $id, nb_reponse = $nbReponse where pk_user_forum_id = ".$resultat['pk_user_forum_id'];
        else
            $str = "insert into user_forum (fk_user_id, fk_forum_id, nb_reponse) values (".$_SESSION['userId'].", $id, $nbReponse)";
        $req = mysql_query($str);
        //echo "str = $str";
    
        // formulaire pour une réponse
        echo "<br><br>Réponse<br>";
        echo "<form action = 'posterMessage.php' method = 'post'>";
            echo "<TEXTAREA rows='20' cols='60' name='corps'></TEXTAREA><br>";
            echo "<input type = 'hidden' name = 'fk_user_id' value = '".$_SESSION['userId']."'>";
            echo "<input type = 'hidden' name = 'fk_forum_id' value = '$id'>";
            echo "<input type = 'submit' value = 'Poster'>";
        echo "</form>";
    }
    echo "<br><br><a href = 'forum.php'>Retour</a>";
    
echo "</body></html>";
?>