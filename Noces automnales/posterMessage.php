<?php
session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");
    
    $fk_forum_id = $_POST['fk_forum_id'];
    $fk_user_id = $_SESSION['userId'];
    $corps = $_POST['corps'];
    if(isset($_POST['title']))
    {
        $title = $_POST['title'];
        if($title == "")
            header("Location: forum.php?error=Mettez un titre");
        else
            writeDb($title, $corps, $fk_user_id, $fk_forum_id);
    }
    else
    {
        if($fk_forum_id != 0)
            writeDb("", $corps, $fk_user_id, $fk_forum_id);
        else
            header("Location: forum.php?error=Une erreur est survenue");
    }
    
    function replaceAllURL($text)
    {
        //$i = 0;
        $first = 0;
        while(true)        
        {   
            $first = strpos($text, "[url=", $first);
            //echo "<br>-------------------- first = $first";
            if($first === FALSE)
                break;
            //echo "<br><br>text avant : $text";
            $text = replaceURL($text, $first);
            //echo "<br>text après : $text";
            $first += 5;
            //$i++;
            //if($i > 100)
               // break;
        }
        return $text;
    }

    function replaceURL($text, $startOffset = 0)
    {
        //echo "<br><br>text = $text";
        
        $firstStart = strpos($text, "[url=", $startOffset);
        //echo "<br>firstStart = $firstStart";
        if($firstStart === FALSE)
            return $text;
            
        $firstEnd = strpos($text, "[/url]", $firstStart+5);
        //echo "<br>firstEnd = $firstEnd";
        if($firstEnd === FALSE)
            return $text;

        $firstEndBracket = strpos($text, "]", $firstStart);
        //echo "<br>firstEndBracket = $firstEndBracket";
        if($firstEndBracket == $firstEnd+5) // Il a trouvé le ']' de '[/url]' et rien avant --> malformé
            return $text;

        $header = "http://";
        $url = substr($text, $firstStart+5, $firstEndBracket-$firstStart-5);
        if(strpos($url, "https://") !== FALSE)
        {
            $header = "https://";
            $url = str_replace("https://", "", $url);
        }
        else
            $url = str_replace($header, "", $url);
        $link = "<a href = ''".$header.$url."''>".substr($text, $firstEndBracket+1, $firstEnd-$firstEndBracket-1)."</a>";
        //echo "<br>url = $link";
        $result = substr_replace($text, $link, $firstStart, $firstEnd+5-$firstStart+1);
        //echo "<br>result = $result";
        
        
        return $result;
    }
    
    function writeDb($title, $corps, $fk_user_id, $fk_forum_id)
    {
        if(trim($corps) == "")
        {
            $tabDebile = array(
                "Je suis un imbécile alors je n''écris rien dans le corps du message {:-€",
                "Toujours plus loin, toujours plus CHIANT. J''ai rien à dire mais je le fais savoir quand même",
                "Je suis un <br><img src = ''http://files.myopera.com/Nibu/albums/162927/idiot.gif''>",
                "Bouffon et fier de l''être <br><img src = ''http://www.blagoticone.com/smiley/fou/vil-jocker.gif''>",
                "Et merde, la photo de moi à la naissance a été divulgée<br><img src = ''http://specials.bjorkish.net/imissyou/idiot.gif''>",
                "Qui à dit que je ne servait à rien ? Je suis là pour faire chier le monde !!!",
                "GNNNNNNNNNNNNNNNNNN... <b>con</b>...sti...péé....<br><img src = ''http://www.aquaplusservice.ch/images/brunop.jpg''>",
                "Gâteauuuuuuuuuuu<br><img src = ''http://www.aquaplusservice.ch/images/valp.jpg''>"
                );
            $nbDebile = count($tabDebile);
            $index = rand(0, $nbDebile-1);
            $corps = "<i>".$tabDebile[$index]."</i>";
            //echo "nbDebile = $nbDebile / index = $index / txt = ".$tabDebile[$i];
        }
        else
        {
            $corps = str_replace("<", "&lt;", $corps);
            $corps = str_replace(">", "&gt;", $corps);
            $corps = str_replace("\n", "<br>", $corps);
            $corps = replaceAllURL($corps);
            
            //$motif='`\[url](.*?)\[/url]`si';
            //$sortie='<a href="$1" target="_blank">$1</a>';
            //$corps = preg_replace($motif,$sortie,$corps); 
            
            //echo "<br><br>Corps = ".$corps;
        }
        $date = date("Y-m-d H:i:s");
        
        include('connectdb.php');
    
        $strDate = "select prenom, last_post from user where pk_user_id = '$fk_user_id'";
        $reqDate = mysql_query($strDate);
        $resultat = mysql_fetch_array($reqDate);
        $lastPost = $resultat['last_post'];
        $user = $resultat['prenom'];
        
        $nbSecondeMini = 10;
        $difDate = strtotime($date) - strtotime($lastPost);
        if(intval($difDate) < intval($nbSecondeMini))
        {
            if($fk_forum_id == 0)
                $location = "forum.php?";
            else
                $location = "messageForum.php?id=".$fk_forum_id."&";
            header("Location: ".$location."error=Veuillez attendre $nbSecondeMini secondes avant de poster à nouveau");
        }
        else
        {
            $str = "insert into forum (fk_forum_id, fk_user_id, title, text, creation_date) values ('$fk_forum_id', '$fk_user_id', '$title', '$corps', '$date')";
            $req = mysql_query($str);
            
            $strUser = "update user set last_post = '$date' where pk_user_id = '$fk_user_id'";
            $reqUser = mysql_query($strUser);
            
            $strLast = "update forum set last_message_date = '$date', last_user='$user' where pk_forum_id = '$fk_forum_id'";
            $reqLast = mysql_query($strLast);
            
            $id = $fk_forum_id;
            if($id == 0)
            {
                $strPk = "select pk_forum_id from forum where fk_user_id=$fk_user_id and title='$title' and creation_date='$date'";
                $reqPk = mysql_query($strPk);
                $resultatPk = mysql_fetch_array($reqPk);
                $id = $resultatPk['pk_forum_id'];
                //echo "strPk = $strPk<br>id=$id";
            }
            header("Location: messageForum.php?id=".$id);
        }
    }
?>