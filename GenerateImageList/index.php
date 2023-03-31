<?php
session_start();
$_SESSION['key'] = rand (12345,56789);
require_once("cookie.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Générateur de mosaïque</title>
	<link rel="stylesheet" href="css/common.css" type="text/css" media="screen" />
	<script language="JavaScript" type="text/javascript" src="Scripts/jsscript.js"></script>
	<script language="JavaScript" type="text/javascript" src="Scripts/swfobject.js"></script>
	<style type="text/css"></style>
</head>

<body>
Ce script vous permet de réaliser un collage de plusieurs images en une seule.<br />
Concrétement vous choisissez un lot d'image, vous rentrez les paramètres qui vous conviennent et le script vous fournira une image contenant une mosaïque de ce que vous lui avez envoyé.<br /><br />

Ne vous souciez pas de la taille des images que vous envoyez, le script les redimensionnera automatiquement.<br /><br />

<font color = 'red'>/!\ Veuillez sauvegarder vos résultats ailleurs, je ne garantis par de pouvoir garder toutes les images ici /!\</font><br /><br />

<!-- Cette page nécessite d'avoir JavaScript activé et Flash installé.<br />
Si c'est impossible, passez par <a href = 'index_v1.php'>cette version</a><br /><br /> -->

		<a id='formParamShowDiv' onclick="showDiv('formParam')"><b>Afficher le formulaire</b></a>
		<div id='formParam'>
		<a onclick="hideDiv('formParam')"><b>Cacher le formulaire</b></a>
		<form method = 'post' action = './generate.php' id = 'formParameters' name = 'formParameters' enctype='multipart/form-data'>
		
		<?php
		echo "<table border = '0'>";
			echo "<tr>";
				echo "<td width = '20%'>Largeur max</td>";
				echo "<td><input name = 'widthMax' type = 'text' value = '".getCookieOrValue('widthMax', 120)."' size = '4' /></td>";
				echo "<td width = '5%'></td>";
				echo "<td width = '15%'>Hauteur max</td>";
				echo "<td width = '15%'><input name = 'heightMax' type = 'text' value = '".getCookieOrValue('heightMax', 140)."' size = '4' /></td>";
				echo "<td width = '35%'><input type = 'checkbox' name = 'ratio' checked />Respecter le rapport hauteur/largeur</td>";
			echo "</tr>";
			
			echo "<tr>";
				echo "<td>Nb images par colones</td>";
				echo "<td><input name = 'nbImgByColumn' type = 'text' value = '".getCookieOrValue('nbImgByColumn', 5)."' size = '4' /><td>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td></td>";
			echo "</tr>";
			
			echo "<tr>";
				echo "<td>Gap horizontal</td>";
				echo "<td><input name = 'widthGap' type = 'text' value = '".getCookieOrValue('widthGap', 5)."' size = '4' /></td>";
				echo "<td></td>";
				echo "<td>Gap vertical</td>";
				echo "<td><input name = 'heightGap' type = 'text' value = '".getCookieOrValue('heightGap', 5)."' size = '4' /></td>";
				echo "<td></td>";
			echo "</tr>";
			
			echo "<tr>";
				echo "<td>Couleur de fond :</td>";
				echo "<td>";
					echo "<input type='radio' name='transparent' value='couleur' checked />";
					echo "<script type='text/javascript' src='./Scripts/jscolor/jscolor.js'></script>";
					echo "<input name = 'color' class='color' value='".getCookieOrValue('color', 'FFFFFF')."' size = '6'>";
				echo "</td>";
				echo "<td></td>";
				
				$checked = "";
				if(getCookie('transparent') == "transparent")
					$checked = 'checked';
				echo "<td colspan='3'><input type='radio' name='transparent' value='transparent' $checked />Transparent (Ne fonctionne qu'avec PNG et GIF)</td>";
			echo "</tr>";
			
			echo "<tr>";
				echo "<td>Format de sortie</td>";
				echo "<td><select name = 'format' style='width:60px'>";
					$tabFormat = array('GIF', 'JPG', 'PNG');
					for($i = 0; $i < sizeof($tabFormat); $i++)
					{
						$optValue = $i+1;
						$checked = "";
						if(getCookie('format') == $optValue)
							$checked = 'selected="selected"';
						echo "<option value = '$optValue' $checked>".$tabFormat[$i]."</option>";
					}
				echo "</select></td>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td></td>";
			echo "</tr>";

			echo "<tr>";
				echo "<td>Nom désiré</td>";
				echo "<td><input type='text' name='name' /></td>";
				echo "<td></td>";
				echo "<td colspan='2'><input type='checkbox' name = 'addNumberName' checked />Ajouter un nombre si le nom est déjà pris</td>";
				echo "<td></td>";
			echo "</tr>";
			
			/*echo "<tr>";
				echo "<td>Ordre</td>";
				echo "<td><input type='radio' name='orderBy' value='alpha' checked />alphabetique</td>";
				echo "<td></td>";
				$checked = "";
				if(getCookie('orderBy') == "list")
					$checked = 'checked';
				echo "<td><input type='radio' name='orderBy' value='list' $checked />comme dans la liste</td>";
				echo "<td></td>";
			echo "</tr>";*/
			
			
		echo "</table>";
		echo "<input name = 'folder' type = 'hidden' />";
		echo "<input name = 'helpField' id = 'helpField' type = 'hidden' />";
		echo "<input name = 'formParamField' id = 'formParamField' type = 'hidden' />";
		echo "<br /><a href = './cookie.php?function=clear'>Valeur par défaut (Supprime les cookies)</a>";
		?>
		</form>
		</div><br />

		<a id='helpShowDiv' onclick="showDiv('help')"><b>Afficher l'aide</b></a>
		<div id="help">
			<a onclick="hideDiv('help')"><b>Cacher l'aide</b></a><br />
				- <b>Largeur max : </b>Largeur maximale de chaque vignette<br />
				- <b>Hauteur max : </b>Hauteur maximale de chaque vignette<br />
				- <b>Respecter le rapport hauteur/largeur : </b>L'image ne sera pas déformée. Si la case est décochée, chaque vignette aura la taille spécifiée par Largeur max et Hauteur max<br />
				- <b>Nb images par colones : </b>Assez clair ^^<br />
				- <b>Gap horizontal : </b>Espacement horizontal entre 2 images<br />
				- <b>Gap vertical : </b>Espacement vertical entre 2 images<br />
				- <b>Couleur de fond : </b>Les vignettes seront collées sur une image unie. Vous choisissez ici la couleur de fond de cette image en définissant les
 composantes Rouge, Verte, Bleue (entre 0 et 255)<br />
				- <b>Transparent :</b> Les vignettes seront collées sur un fond transparent. Ne fonctionne qu'avec PNG et GIF en format de sortie. Si JPG est choisi, le fond sera de la couleur choisie dans Couleur de fond<br />
				- <b>Format de sortie : </b>Format de l'image finale<br />
			Note : Toutes les tailles sont exprimées en pixel.<br /><br />
		</div>


	<form id="form_upload" name="form_upload" method="post" action=""><br />

		<div id="mon_flash">
			Pour uploader, vous devez telecharger <a href="http://www.adobe.com/go/getflashplayer_fr" onclick="window.open(this.href); return false;"><strong>le player flash</strong></a>
		</div>
		
		<script type="text/javascript">
			function generateFolderName()
			{
				var timestamp = new Date().getTime();
				
				<?php
					echo "var ip = '".$_SERVER['REMOTE_ADDR']."'";
				?>
				
				var rnd = randomString(5);
				
				return (ip+"_"+timestamp+"_"+rnd);
			}
			
			function randomString(nbChar)
			{
				var str = "";
				for(var i = 0; i < nbChar; i++)
					str = str+Math.floor(Math.random()*10);
				return str;
			}
		
			function showOrHideDiv(divName)
			{
				var div = document.getElementById(divName);
				if(div.style.display == 'none')
				{
					div.style.display = '';
					document.getElementById(divName+"Field").value = 'show';
					document.getElementById(divName+"ShowDiv").style.display = 'none';
				}
				else
				{
					div.style.display = 'none';
					document.getElementById(divName+"Field").value = 'hide';
					document.getElementById(divName+"ShowDiv").style.display = '';
				}
			}
			
			function showDiv(divName)
			{
				var div = document.getElementById(divName);
				div.style.display = '';
				document.getElementById(divName+"Field").value = 'show';
				document.getElementById(divName+"ShowDiv").style.display = 'none';
			}

			function hideDiv(divName)
			{
				var div = document.getElementById(divName);
				div.style.display = 'none';
				document.getElementById(divName+"Field").value = 'hide';
				document.getElementById(divName+"ShowDiv").style.display = '';
			}
			
			<?php
				if(isset($_COOKIE['formParamField']) && $_COOKIE['formParamField'] == 'hide') // Affiche par défaut les paramètres
					echo "hideDiv('formParam');";
				else
					echo "showDiv('formParam');";

				if(isset($_COOKIE['helpField']) && $_COOKIE['helpField'] == 'show') // Cache par défaut l'aide
					echo "showDiv('help');";
				else
					echo "hideDiv('help');";
			?>
			
			var folderName = generateFolderName();
			document.formParameters.folder.value = folderName;
			
			var so = new SWFObject("./applications/NasUploader15.swf", "nasuploader", "550", "400", "8");
			so.addParam ('FlashVars','varget=dossierup%3D'+folderName);
			so.write("mon_flash");
		</script>
		<br />
	</form>
	
	<br /><br /><b>Auteurs :</b><br />
	Formulaire + Générateur d'image : <a href = 'mailto:pumbaa@net2000.ch'>Pumbaa</a><br />
	Librairie de manipulation d'image : <a href = 'http://mtodorovic.developpez.com/php/gd/'>GD</a><br />
	Système d'upload de fichiers : <a href = 'http://www.nasuploader.com/'>NAS Uploader</a><br />
	Sélectionneur de couleur : <a href = 'http://jscolor.com/'>JS Color</a><br />
	<br /><a href = './code_source'>Code source</a>

</body>
</html>
