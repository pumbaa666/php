<?php

//ce script est appelé par l'animation flash. Prenez note que si vous instanciez une session ici (session_start(); ) elle sera différente de celle de la session instanciée par l'utilisateur affichant la page HTML contenant NAS Uploader. Vous ne pouvez donc pas accéder à ses variables de session personnelles.
if(isset($_GET['dossierup']))
	$save_path = "./uploads/".$_GET['dossierup'].'/';
else
	return;
if (isset($_FILES["Filedata"]))
{
	if($_FILES["Filedata"]['error'] == 0)
	{
		$tabfile = explode('.',  $_FILES['Filedata']['name']);
		
		$dotPos = strrpos($_FILES['Filedata']['name'], ".");
		if($dotPos === false)
			$extfi = "";
		else
			$extfi = strtolower(substr($_FILES['Filedata']['name'], $dotPos));
		
		if(file_exists($save_path . $_FILES['Filedata']['name']))
			echo utf8_encode('Un fichier porte déjà ce nom dans ce dossier');
		elseif(!in_array($extfi, array('.jpg','.jpeg','.gif','.png')))
			echo utf8_encode('N\'envoyez que des fichier jpg, jpeg, gif ou png');
		else
		{
			@mkdir($save_path,0777); 
			@chmod($save_path,0777); 
			
			/*$imageOrderNumber = '';
			$newImageOrderNumber = '';
			if(isset($_COOKIE['imageOrderNumber']))
			{
				$imageOrderNumber = $_COOKIE['imageOrderNumber'];
				$newImageOrderNumber = $imageOrderNumber+1;
			}*/
			
			if(@move_uploaded_file($_FILES["Filedata"]["tmp_name"], $save_path.$_FILES["Filedata"]["name"]))
				echo utf8_encode('1');
			else
				echo utf8_encode('Erreur d\'écriture');

			//setcookie('imageOrderNumber', $newImageOrderNumber, 60); // 60 secondes
		}
	}
	else
	{
		switch ($_FILES["Filedata"]['error'])
		{
			case 1: echo 'Fichier trop volumineux'; break;
			case 2: echo 'Fichier trop volumineux'; break;
			case 3: echo 'Fichier incomplet'; break;
			case 4: echo 'Pas de fichier'; break;
			case 5: echo 'Erreur inconnue'; break;
			case 6: echo 'Erreur serveur'; break; //pas de dossier tmp
			case 7: echo utf8_encode('Erreur d\'écriture'); break;
			case 8: echo 'Extension incorrecte'; break;
			default: echo 'Erreur inconnue'; break;
		}
	}
}
else
	echo utf8_encode("Pas de fichiers envoyés");

echo utf8_encode('.');
?>
