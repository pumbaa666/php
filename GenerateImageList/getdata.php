<?php
	session_start();
	//$_SESSION['key'] est créé au chargement de la page upload_multiple_js.php
	//et récupéré par flash lors du chargement de l'animation
	echo '&varsession=%26key%3D'.$_SESSION['key']; //&key=***
	
	//&varsession est la variable attendue par flash, le reste son contenu encodé pour une URL car passé en GET
?>
