<?php
@session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");
?>
<html><head><title>Noces Automnales</title></head><body>
<?php

    $la5 = 0;
    $dors5 = 0;
    $fondue5 = 0;
    $grillades5 = 0;
    $la13 = 0;
    $dors13 = 0;
    $fondue13 = 0;
    $grillades13 = 0;
    
    $nbParticipants = 0;
    $nbParticipants5 = 0;
    $nbParticipants13 = 0;
    $nbParticipantsBouffe5 = 0;
    $nbParticipantsBouffe13 = 0;

    echo "<table border = '1'>";
        echo "<tr>";
            echo "<td></td>";
            echo "<td colspan = '4'><center>Vendredi 5</td>";
            echo "<td colspan = '4'><center>Samedi 13</td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td><b>Prénom</td><td><b>Là</td><td><b>Dors</td><td><b>Fondue</td><td><b>Grillades</td><td><b>Là</td><td><b>Dors</td><td><b>Fondue</td><td><b>Grillades</td>";
        echo "</tr>";
    
    include("connectdb.php");
    $str = "select FK_USER_ID, LA5, DORS5, BOUFFE5, LA13, DORS13, BOUFFE13 from participation";
    $req = mysql_query($str);
    while($resultat = mysql_fetch_array($req))
    {
        $strUser = "select PRENOM from user where PK_USER_ID = '".$resultat['FK_USER_ID']."'";
        $reqUser = mysql_query($strUser);
        $resultatUser = mysql_fetch_array($reqUser);
        echo "<tr>";
            echo "<td>".$resultatUser['PRENOM']."</td>";
            echo "<td>".$resultat['LA5']."</td>";
            echo "<td>".$resultat['DORS5']."</td>";
        
        if($resultat['DORS5'] == "Oui")
            $dors5++;
        if($resultat['BOUFFE5'] == "fondue")
        {
            $fondue5++;
            $nbParticipantsBouffe5++;
            $mangeFondue5 = 'Oui';
            $mangeGrillade5 = 'Non';//<font color = "red">
        }
        else if($resultat['BOUFFE5'] == "grillades")
        {
            $grillades5++;
            $nbParticipantsBouffe5++;
            $mangeFondue5 = 'Non';
            $mangeGrillade5 = 'Oui';
        }

        if($resultat['LA5'] == "Oui")
            $la5++;
        else
        {
            $mangeFondue5 = '-';
            $mangeGrillade5 = '-';
        }
        echo "<td>$mangeFondue5</td>";
        echo "<td>$mangeGrillade5</td>";

        
        echo "<td>".$resultat['LA13']."</td>";
        echo "<td>".$resultat['DORS13']."</td>";

        if($resultat['DORS13'] == "Oui")
            $dors13++;
        if($resultat['BOUFFE13'] == "fondue")
        {
            $fondue13++;
            $nbParticipantsBouffe13++;
            $mangeFondue13 = 'Oui';
            $mangeGrillade13 = 'Non';
        }
        else if($resultat['BOUFFE13'] == "grillades")
        {
            $grillades13++;
            $nbParticipantsBouffe13++;
            $mangeFondue13 = 'Non';//<font color = "red">
            $mangeGrillade13 = 'Oui';
        }
        
        if($resultat['LA13'] == "Oui")
            $la13++;
        else
        {
            $mangeFondue13 = '-';
            $mangeGrillade13 = '-';
        }
        echo "<td>$mangeFondue13</td>";
        echo "<td>$mangeGrillade13</td>";

        
        echo "</tr>";
        
        $nbParticipants++;
    }

    $la5 = substr(($la5/$nbParticipants*100)."%", 0, 4);
    $dors5 = substr(($dors5/$nbParticipants*100)."%", 0, 4);
    $fondue5 = substr(($fondue5/$nbParticipantsBouffe5*100)."%", 0, 4);
    $grillades5 = substr(($grillades5/$nbParticipantsBouffe5*100)."%", 0, 4);
    $la13 = substr(($la13/$nbParticipants*100)."%", 0, 4);
    $dors13 = substr(($dors13/$nbParticipants*100)."%", 0, 4);
    $fondue13 = substr(($fondue13/$nbParticipantsBouffe13*100)."%", 0, 4);
    $grillades13 = substr(($grillades13/$nbParticipantsBouffe13*100)."%", 0, 4);

    echo "<tr>";
        echo "<td></td><td><b>$la5</td><td><b>$dors5</td><td><b>$fondue5</td><td><b>$grillades5</td><td><b>$la13</td><td><b>$dors13</td><td><b>$fondue13</td><td><b>$grillades13</td>";
    echo "</tr>";
    
    echo "</table>";
?>
</body></html>