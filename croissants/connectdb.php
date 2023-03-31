<?php
	/* LOCAL */
    $db = mysql_connect('localhost','root','') or die ("erreur de connexion");
    mysql_select_db('croissants',$db) or die ("erreur de connexion à la base");

	/* ONLINE */
    //$db = mysql_connect('sql.olympe-network.com','23051_pumbaa','bubunoob3') or die ("eRReur de connexion");
    //mysql_select_db('23051_pumbaa',$db) or die ("erreur de connexion à la base");

?>
