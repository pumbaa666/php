<?php
/**
	Auteur : Pumbaa
	E-Mail : pumbaa@net2000.ch (n'hésitez pas à m'envoyer un email si vous avez des questions)
	
	Tuto GD : http://mtodorovic.developpez.com/php/gd/?page=sommaire
*/
define ("GIF_CODE", 1);
define ("JPG_CODE", 2);
define ("PNG_CODE", 3);

require_once("cookie.php");
saveCookie();

	$error = checkPostParameters();
	if($error != "")
	{
		header("Location:badParameters.php?message=$error");
		return;
	}

	// Récupération des paramètres
	$widthMax = $_POST['widthMax']; // Largeur maximale d'une image
	$heightMax = $_POST['heightMax']; // Hauteur maximale d'une image
	$nbImgByColumn = $_POST['nbImgByColumn']; // Nombre maximum d'images par colone
	
	$widthGap = $_POST['widthGap']; // Espacement horizontal entre chaque lignes
	$heightGap = $_POST['heightGap']; // Espacement vertical entre chaque lignes
	
	// La couleur est envoyée sous forme de string de 6 caractères, représentant les composantes R,V,B en hexa
	$red = hexdec(substr($_POST['color'], 0, 2)); // Composante rouge de l'image de fond
	$green = hexdec(substr($_POST['color'], 2, 2)); // Composante verte de l'image de fond
	$blue = hexdec(substr($_POST['color'], 4, 2)); // Composante bleue de l'image de fond
	
	$folder = "./uploads/".$_POST['folder']."/";

	$nbImages = 0;
	$dir = opendir($folder);
	while($f = readdir($dir))
	{
		$filePathName = $folder.$f;
		if(is_file($filePathName))
		{
			//echo "$nbImages - $filePathName<br>";
			$details = getimagesize($filePathName);
			if($details[2] < GIF_CODE || $details[2] > PNG_CODE) // Ne traite que les images (voir http://mtodorovic.developpez.com/php/gd/?page=page_3#LIII-3.1 )
				continue;
			$images[0][$nbImages] = $filePathName; // Sauve le nom de l'image
			$images[1][$nbImages] = $details[2]; // Sauve le type de l'image
			$nbImages++;
		}
	}
	
	//var_dump($images);
	//return; // TODO delete

	if($nbImages <= 0)
	{
		header("Location:badParameters.php?message=Aucun fichier d'image valide dans le dossier ".$folder);
		return;
	}
	
	// Calcul la taille de l'image finale
	if($nbImages < $nbImgByColumn)
		$widthTotal = $nbImages * $widthMax + ($nbImages-1) * $widthGap;
	else
		$widthTotal = $nbImgByColumn * $widthMax + ($nbImgByColumn-1) * $widthGap;
	
	$nbLine = ceil($nbImages / $nbImgByColumn);
	$heightTotal = $nbLine * $heightMax + ($nbLine-1) * $heightGap;
	
	$destination = imagecreatetruecolor($widthTotal,$heightTotal); // Création d'une image de fond
	$couleur = imagecolorallocate($destination, $red, $green, $blue); // Création de la couleur
	imagefill($destination,0,0,$couleur); // Remplissage de l'image de fond en couleur
	
	array_multisort($images[0], $images[1]);
	
	$wLeft = 0;
	$hTop = 0;
	for($count = 0; $count < $nbImages; $count++) // On ouvre l'image source (chaque vignette)
	{
		switch($images[1][$count]) // Sélectionne la bonne fonction pour ouvrir l'image
		{
			case  GIF_CODE: $source = imagecreatefromgif($images[0][$count]); break;
			case  JPG_CODE: $source = imagecreatefromjpeg($images[0][$count]); break;
			case  PNG_CODE: $source = imagecreatefrompng($images[0][$count]); break;
			default : die("Format d'image non pris en charge : ".$images[1][$count]." / ".$images[0][$count]);
		}
		if(!$source) // Si le fichier est corrompu
			continue;
		
		// Taille de l'image originale
		$w = imagesx($source);
		$h = imagesy($source);
		
		// Calcul de la bonne taille finale, pour ne pas changer le ratio largeur / hauteur
		if(isset($_POST['ratio']) && $_POST['ratio'] == 'on')
		{
			if($w > $h)
			{
				$ratioInit = $h / $w;
				$newWidth = $widthMax; // $newWidth prendra la taille maximale --> $widthMax ...
				$newHeight = $newWidth * $ratioInit; // et $newHeight sera adapté en conséquence, pour garder les proportions
			}
			else
			{
				$ratioInit = $w / $h;
				$newHeight = $heightMax;
				$newWidth = $newHeight * $ratioInit;
			}
		}
		else
		{
			$newWidth = $widthMax;
			$newHeight = $heightMax;
		}

		// Redimensionnement
		$sourceMini = imagecreatetruecolor($newWidth,$newHeight); // Création d'une image truecolor de la taille d'une vignette
		imagecopyresampled($sourceMini, $source, 0, 0, 0, 0, $newWidth, $newHeight, $w, $h); // Copie de la vignette dans l'image créée

		// Nouvelle taille
		$w = imagesx($sourceMini);
		$h = imagesy($sourceMini);
		
		// Centrage l'image
		$wAlign = ($widthMax - $w) / 2;
		$hAlign = ($heightMax - $h) / 2;
		
//		imagecopy($image_dest, $image_src, $dest_x, $dest_y, $src_x, $src_y, $src_largeur, $src_hauteur);
		imagecopy($destination, $sourceMini, ($widthMax + $widthGap) * $wLeft + $wAlign, ($heightMax + $heightGap) * $hTop + $hAlign, 0, 0, $w, $h); // Copie de la totalité de la vignette sur l'image finale
		if(($count+1) % $nbImgByColumn == 0) // "Retour à la ligne"
		{
			$wLeft = 0;
			$hTop++;
		}
		else
			$wLeft++;
			
		imagedestroy($source); // Libération de la mémoire
		imagedestroy($sourceMini); // Libération de la mémoire
	}

	
	if(isset($_POST['format']))
		$format = $_POST['format'];
	else
		$format = JPG_CODE; // jpeg par defaut
	
	if(isset($_POST['transparent']) && $_POST['transparent'] == 'transparent')
		imagecolortransparent($destination, $couleur); // Rendre l'image transparente.
	
	switch($format) // Affichage de l'image finale
	{
		case GIF_CODE : $type="gif"; break;
		case PNG_CODE : $type="png"; break;
		default : $type="jpeg"; break;
	}
	
	// Nom de l'image
	$resultPath = './results/';
	$imageName = $resultPath.'RESULT_'.$_SERVER['REMOTE_ADDR'].'_'.randomString(10).'.'.$type;
	if(isset($_POST['name']) && trim($_POST['name']) != '')
	{
		$requiredName = $resultPath.trim($_POST['name']).'.'.$type;
		if(file_exists($requiredName) === FALSE) // Ce nom n'existe pas, on peut créer l'image.
			$imageName = $requiredName;
		else
		{
			if(isset($_POST['addNumberName']))
				$imageName = $resultPath.trim($_POST['name']).'_'.randomString(10).'.'.$type;
		}
	}
	
	header("Content-type: image/$type"); //Indispensable au bon fonctionnement des fonctions GD
	drawImage($destination, $imageName, $type);
			
	header("Location:download.php?url=".$imageName, false); // Renvoie sur la page pour DL l'image finale
	// TODO limitation par IP

	imagedestroy($destination); // Libération de la mémoire

function drawImage($destination, $name, $type)
{
	$type = strtolower($type);
	if($type == "gif")
		imagegif($destination, $name);
	elseif($type == "png")
		imagepng($destination, $name);
	elseif($type == "jpg" || $type == "jpeg")
		imagejpeg($destination, $name);
	else
		echo "Type d'image inconnu";
}
	
function randomString($nbChar)
{
	$str = "";
	for($i = 0; $i < $nbChar; $i++)
		$str = $str.rand(0,9);
	return $str;
}

function checkPostParameters()
{
	$tabValue = array(
						"widthMax" => array("Largeur max", 1, 500) ,
						"heightMax" => array("Hauteur max", 1, 500) ,
						"nbImgByColumn" => array("Nombre d'image par colone", 1, 25) ,
						"widthGap" => array("Gap horizontal", 0, 50) ,
						"heightGap" => array("Gap vertical", 0, 50) ,
						"color" => array("Couleur", 0, 16777215)
					 );
	$errorMsg = "";
	
	foreach($tabValue as $key => $value)
	{
		$humanName = $value[0];
		$minValue = $value[1];
		$maxValue = $value[2];

		if(!isset($_POST[$key]))
			$errorMsg = $errorMsg."Il faut donner une valeur pour $humanName<br />";
		elseif(!myIsNumerique($_POST[$key]))
			$errorMsg = $errorMsg."$humanName doit être un nombre<br />";
		elseif(lower($_POST[$key], $minValue))
			$errorMsg = $errorMsg."$humanName doit être plus grand ou égal à $minValue<br />";
		elseif(greater($_POST[$key], $maxValue))
			$errorMsg = $errorMsg."$humanName doit être plus petit ou égal à $maxValue<br />";
	}
	if(!isset($_POST['folder']))
		$errorMsg = $errorMsg."Il faut donner une valeur pour Le chemin du dossier d'upload<br />";
	
	return $errorMsg;
}

function myIsNumerique($string)
{
	return is_numeric($string) || is_numeric('0x'.$string);
}

function greater($a, $b)
{
	if(is_numeric($a))
		$var1 = $a;
	elseif(is_numeric('0x'.$a))
		$var1 = hexdec($a);
	else
		return FALSE;

	if(is_numeric($b))
		$var2 = $b;
	elseif(is_numeric('0x'.$b))
		$var2 = hexdec($b);
	else
		return FALSE;

	return $var1 > $var2;
}

function lower($a, $b)
{
	if(is_numeric($a))
		$var1 = $a;
	elseif(is_numeric('0x'.$a))
		$var1 = hexdec($a);
	else
		return FALSE;

	if(is_numeric($b))
		$var2 = $b;
	elseif(is_numeric('0x'.$b))
		$var2 = hexdec($b);
	else
		return FALSE;

	return $var1 < $var2;
}
?>