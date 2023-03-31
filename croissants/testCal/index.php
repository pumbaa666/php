<HTML>
<HEAD>
<TITLE></TITLE>
<!-- N'OUBLIEZ PAS DE METTRE LE LIEN AVEC LA FEUILLE DE STYLE COMME CI DESSOUS:  -->
<LINK rel="stylesheet" type="text/css" href="styles.css">
</HEAD>
<BODY>
<?php
require_once("calendrier.php");
echo "<a href = 'index.php'>index</a><br/>";
calendrier(date("n"),date("Y"));

//Si tu souhaites mettre un calendrier avec affichage d'un mois en particulier, tu peux faire comme ci-dessous par ex. pour mars 2007: ( sans les //)
//calendrier("3","2007");
?>
</BODY>
</HTML>