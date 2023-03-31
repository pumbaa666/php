<?php
// Source : http://www.commentcamarche.net/faq/sujet-12255-warning-rmdir-directory-not-empty
$folder = "./uploads/";
$dir = opendir($folder);

while($f = readdir($dir))
	if(!in_array($f, array('.','..')))
		@rmdir_recursive($folder.$f);
closedir($dir);

function rmdir_recursive($dir)
{
	$dir_content = scandir($dir); //Liste le contenu du répertoire dans un tableau
	if($dir_content !== FALSE) //Est-ce bien un répertoire?
	{
		foreach ($dir_content as $entry) //Pour chaque entrée du répertoire
		{
			if(!in_array($entry, array('.','..'))) //Raccourcis symboliques sous Unix, on passe
			{
				$entry = $dir . '/' . $entry; //On retrouve le chemin par rapport au début
				
				if(!is_dir($entry)) //Cette entrée n'est pas un dossier: on l'efface
					unlink($entry);
				
				else //Cette entrée est un dossier, on recommence sur ce dossier
					rmdir_recursive($entry);
			}
		}
	}
	
	rmdir($dir); //On a bien effacé toutes les entrées du dossier, on peut à présent l'effacer
}
?>