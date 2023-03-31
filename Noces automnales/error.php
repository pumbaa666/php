<?php
session_start();
echo "<html><head><title>Noces Automnales</title></head><body>";
    if(isset($_GET['type']))
    {
        if($_GET['type'] == "autorisation")
        {
            echo "Vous n'êtes pas autorisé à afficher cette page. Loggez vous d'abord.<br>";
            echo "<a href = 'index.php'>Login</a>";
        }
        else if($_GET['type'] == "autorisationAdmin")
        {
            echo "Vous n'êtes pas autorisé à afficher cette page. Loggez vous d'abord en <b>administrateur</b>.<br>";
            echo "<a href = 'index.php'>Login</a>";
        }
    }
echo "</body></html>";