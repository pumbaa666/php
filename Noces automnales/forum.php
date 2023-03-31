<?php
session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");

echo "<html><head><title>Noces Automnales</title></head><body>";
    
    require_once('fonction.php');
    if(isset($_GET['error']))
        echo "<font color = 'red'>Erreur : ".formatMessage($_GET['error'])."</font><br>";
    if(isset($_GET['message']))
        echo "<font color = 'blue'>Info : ".formatMessage($_GET['message'])."</font><br>";
    echo "<table border = '1'>";
    echo "<tr>";
        echo "<td colspan='2'><b>Auteur</td>";
        echo "<td colspan='1'><b>Titre</td>";
        echo "<td colspan='2'><b>Dernière réponse</td>";
        echo "<td colspan='1'><b>Droits</td>";
    echo "</tr>";
    
    include('connectdb.php');
    $str = "select pk_forum_id, fk_forum_id, fk_user_id, title, creation_date, last_message_date, last_user from forum where fk_forum_id=0 order by creation_date";
    $req = mysql_query($str);
    while($resultat = mysql_fetch_array($req))
    {
        $lastUser = $resultat['last_user'];
        $lastDate = $resultat['last_message_date'];
        $id = $resultat['pk_forum_id'];
        $title = "<a href = 'messageForum.php?id=$id'>".$resultat['title']."</a>";
        $dateTime = formatDateTime($resultat['creation_date']);

        // Info sur l'auteur du sujet
        $strUser = "select pk_user_id, login, prenom, nom from user where pk_user_id=".$resultat['fk_user_id'];
        $reqUser = mysql_query($strUser);
        $resultatUser = mysql_fetch_array($reqUser);
        $user = $resultatUser['prenom'];
        $nom = $resultatUser['nom'];
        $prenom = $resultatUser['prenom'];
        
        // Nombre de réponse
        $strCount = "select count(pk_forum_id) as nbReponse from forum where fk_forum_id=".$resultat['pk_forum_id'];
        $reqCount = mysql_query($strCount);
        $resultatCount = mysql_fetch_array($reqCount);
        $nbReponse = $resultatCount['nbReponse'];

        // Réponses non-lues
        $strNew = "select nb_reponse from user_forum where fk_user_id=".$_SESSION['userId']." and fk_forum_id=".$resultat['pk_forum_id'];
        $reqNew = mysql_query($strNew);
        $resultatNew = mysql_fetch_array($reqNew);
        $new = "";
        if($resultatNew['nb_reponse'] < $nbReponse)
            $new = "<font color = 'red'>";

        // Affichage, enfin
        echo "<tr>";
            echo "<td title='$prenom $nom'>$user</td>";
            echo "<td>$dateTime</td>";
            echo "<td>$title $new($nbReponse)</td>";
            echo "<td>$lastUser</td>";
            echo "<td>$lastDate</td>";
            echo "<td>";
                if(isAdmin() || $resultatUser['pk_user_id'] == $_SESSION['userId'])
                {
                    echo "<a href = 'editMessage.php?id=".  $resultat['pk_forum_id']."'><img src = './images/icon_edit.png'   border = '0' title = 'Modifier votre message'></a> ";
                    echo "<a href = 'deleteMessageQuestion.php?id=".$resultat['pk_forum_id']."'><img src = './images/icon_delete.png' border = '0' title = 'Supprimer votre message'></a> ";
                }
            echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // formulaire pour un nouveau message
    $cols = 60;
    echo "<br><br>Nouveau message<br>";
    echo "<form action = 'posterMessage.php' method = 'post'>";
        echo "<input type = 'text' name = 'title' value = 'titre' size='$cols' maxlength='80'><br>";
        echo "<TEXTAREA rows='20' cols='$cols' name='corps'></TEXTAREA><br>";
        echo "<input type = 'hidden' name = 'fk_forum_id' value = '0'>";
        echo "<input type = 'submit' value = 'Poster'>";
    echo "</form>";


    include('menu.php');

echo "</body></html>";
?>