<?php
session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");

require_once("fonction.php");
if(isAdmin() === FALSE)
    header("Location: error.php?type=autorisationAdmin");
else
{
    $headers ='From: "Lo�c"<pumbaa@net2000.ch>'."\n";
    $headers .='Reply-To: pumbaa@net2000.ch'."\n";
    $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
    $headers .='Content-Transfer-Encoding: 8bit';
    

    include('connectdb.php');
    $str = "select login, password, email from user";
    $req = mysql_query($str);
    while($resultat = mysql_fetch_array($req))
    {
        $message = "Tcho.
        
        Je t'envoi ce petit mail juste pour te pr�venir qu'un forum est actuellement disponible, alors si tu as des remarques ou des questions, c'est l'endroit !
        Pour y acc�der : log-toi, va sur la page de vote (un nouveau lien est disponible tout en haut de la page d'accueil, en orange) puis clique sur Mini-forum.
        
        @+
        
        Lo�c";
        $email = $resultat['email'];
        if(mail($email, 'Noces automnales - 2', $message, $headers))
            echo "<br><br><font color = 'black'>$message<br>Le message a bien �t� envoy� � $email";
        else
            echo "<br><font color = 'red'>Le message n'a pu �tre envoy� � $email";
    }
    echo "<a href = 'mailForm.php'>Retour � l'envoi de mail</a>";
}
?> 