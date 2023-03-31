<?php
session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");
?>
<html><head><title>Noces Automnales</title></head><body background='./images/arc-en-ciel_grey1.jpg'>

<center>
<a href = 'pageDeVote.php' size = '4'><font color = 'orange'><b>(Go direct à la page de vote)</a><br>
<b><u><font color = 'red' face = 'Comic Sans MS' size = '6'>Hey mon ami, t’aime ça les noces ?</u></b></center><br>
<font size = '4' color = 'black'>
Des chips, de la sauce à chips, de l’alcool et des chichas ?<br>
Hey bien tu peux être content, on a une bonne nouvelle pour toi si tu aimes les noces !<br>
<font color = 'red' size = '5'>DEUX NOCES <font color = 'black' size = '4'>sont prévues chez Loïc (à Bouseland)
<i><font size = '5'>le vendredi 5 et le samedi 13 septembre !!</i><font size = '4'> Et tu sais pas quoi ??? T’es invité !!!!<br><br>

<font color = 'red' size = '4'><b>C’est impossible ce que tu dis avec ta bouche !</b><font size = '4' color = 'black'> (que tu te dis).<br>
<font color = 'red' size = '4'><b>Arrête de me bullshiter Loïc là.</b><br>
<font color = 'black'>Je comprend parfaitement ton skepticizem !<br>
Pour te prouver que ca vaut la peine que tu participes à notre soirée, voici une démonstration, <b><u>watch bien la diffrence :</b></u><br>

Ici, des personnes saines (ou pas), qui s’ennuient :<br>
<img src = "./images/ennui1.jpg">
<img src = "./images/ennui2.jpg">
<img src = "./images/ennui3.jpg">
<img src = "./images/ennui4.jpg"><br><br>
Ici, des personnes pompettes qui s’amusent :<br>
<img src = "./images/amuse1.jpg">
<img src = "./images/amuse2.jpg">
<img src = "./images/amuse3.jpg">
<img src = "./images/amuse4.jpg">
<EMBED SRC="http://membres.lycos.fr/lesitedukevin/Coralie_copain.wmv" loop="false" autostart="false" WIDTH=640 HEIGHT=480>
<EMBED SRC="./videos/amuse1.mpg" loop="false" autostart="false" WIDTH=640 HEIGHT=480><br>

<br><font size = '6'>Le vois-tu comme c’est beautiful ??<font size = '4'>

<center>
    <table border = '0'>
        <tr>
            <td><img src = './images/coralie_super.jpg'><br></td>
            <td valign = 'top'><i>(OUIIIIII SUPER !!!!!!)</td>
        </tr>
    
    </table>
</center>

<br>Le plus <font color = 'red'>extrordinary<font color = 'black'>, c’est que si tu participes à notre fête, tu peux apporter pas une ! Pas deux ! Mais bien autant de bouteilles d’alcool que tu veux !
Tu vas même pouvoir rencontrer tes amis et leur en donner!
Avec tout ça tu pourras t’amuser partout dans la maison, dans la cuisine, dans le salon, dans les toilettes !
Héhé hééééé c’est pas beautiful ça ?<br>
<a href = 'pageDeVote.php'><font color = 'orange' size = '5'><b>Enregistre-toi tout de suite</a><font color = 'black' size = '4'></b> pour participer à ces noces et valide celles auxquelles tu veux participer. Tu as même la possibilité de rester dormir, et tu peux voter pour choisir le menu !
Alors qu’est-ce qu’on dit ?<br>

<br><br>
<center>
    <table border = '0'>
        <tr>
            <td><img src = './images/kim_merci.jpg'><br></td>
            <td valign = 'top'><i>Merci, Loïc, merci....</td>
        </tr>
    
    </table>
</center>




</body></html>