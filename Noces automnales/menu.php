<?php
@session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");
    
    echo "<html><head><title>Noces Automnales</title></head><body>";
    echo "<br><a href = 'forum.php'>Mini-forum</a>";
    echo "<br><a href = 'pageDeVote.php'>Heu, remontre les votes stp, j'ai oubli�</a>";
    echo "<br><a href = 'accueil.php'>Retour � l'accueil (avec les photos d�biles, l�...)</a>";
    echo "<br><a href = 'index.php'>Retour au login (j'vois pas trop pourquoi, mais admettons)</a>";
    echo "<br><a href = 'logout.php'>Se d�logger</a>";
?>
