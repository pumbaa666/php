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
	$dir_content = scandir($dir); //Liste le contenu du r�pertoire dans un tableau
	if($dir_content !== FALSE) //Est-ce bien un r�pertoire?
	{
		foreach ($dir_content as $entry) //Pour chaque entr�e du r�pertoire
		{
			if(!in_array($entry, array('.','..'))) //Raccourcis symboliques sous Unix, on passe
			{
				$entry = $dir . '/' . $entry; //On retrouve le chemin par rapport au d�but
				
				if(!is_dir($entry)) //Cette entr�e n'est pas un dossier: on l'efface
					unlink($entry);
				
				else //Cette entr�e est un dossier, on recommence sur ce dossier
					rmdir_recursive($entry);
			}
		}
	}
	
	rmdir($dir); //On a bien effac� toutes les entr�es du dossier, on peut � pr�sent l'effacer
}
?>