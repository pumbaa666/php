<?php
@session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");

$tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";


echo "<html><head><title>Noces Automnales</title></head><body>";
    echo "<form action = 'vote.php' method = 'post'>";
        echo "<input type = 'checkbox' name = 'checkLa5'>Je serais là le vendredi 5<br />";
            echo "$tab<input type = 'checkbox' name = 'dodoLa5'>J'veux décuver chez toi<br />";
            echo "$tab<input type = 'radio' name = 'radio5' value = 'grillades' checked >Need grillades<br />";
            echo "$tab<input type = 'radio' name = 'radio5' value = 'fondue'>Need fondue<br />";
        
        echo "<input type = 'checkbox' name = 'checkLa13'>Je serais là le samedi 13<br />";
            echo "$tab<input type = 'checkbox' name = 'dodoLa13'>J'veux décuver chez toi<br />";
            echo "$tab<input type = 'radio' name = 'radio13' value = 'grillades'>Need grillades<br />";
            echo "$tab<input type = 'radio' name = 'radio13' value = 'fondue' checked >Need fondue<br />";
            
        echo "<input type = 'submit' value = 'Enregistrer'>";
    echo "</form>";
    echo "(au fait, tu peux modifier ton vote à tout moment en remplissant à nouveau le formulaire)<br><br>";
echo "</body></html>";
?>