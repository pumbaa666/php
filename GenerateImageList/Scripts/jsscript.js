
//lance l'upload ds flash
function goUpload(vargetgoupload)
{
	document.getElementById('nasuploader').goUpload(vargetgoupload);
}

//fonction lanc�e par flash une fois l'up  d'un fichier fini
function Upload_File_Finished(nomfichier)
{
}  

//fonction lanc�e par flash une fois l'up de tous les fichiers fini
function Upload_Finished(param1, param2)
{
	document.getElementById('formParameters').submit();
}   

//ex�cut� � chaque ajout d'un fichier 
function Update_File(file)
{
}
   

