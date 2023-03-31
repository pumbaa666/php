<?php
if($_FILES['Filedata']['name'] && ($log = fopen('./upload.log', 'a') ) )
{
	$file = $_FILES['Filedata']['tmp_name'];
	$error = false;

	/**
	* THESE ERROR CHECKS ARE JUST EXAMPLES HOW TO USE THE REPONSE HEADERS
	* TO SEND THE STATUS OF THE UPLOAD, change them!
	*
	*/

	if (!is_uploaded_file($file) || ($_FILES['Filedata']['size'] > 50 * 1024 * 1024) )
	{
		$error = '400 Bad Request';
	}

	fputs($log, ($error ? 'FAILED' : 'SUCCESS') . ' - ' . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ": {$_FILES[Filedata][name]} - {$_FILES[Filedata][size]} byte \n" );
	fclose($log);

	if ($error)
	{
		/**
		* ERROR DURING UPLOAD, one of the validators failed
		*
		* see FancyUpload.js - onError for header handling
		*/
		header('HTTP/1.0 ' . $error);
		die('Error ' . $error);
	}
	else
	{
		$finaldir = './upload_'.$_SERVER['REMOTE_ADDR']."/";
		@mkdir($finaldir,0777); 
		@chmod($finaldir,0777); 
		move_uploaded_file($file, $finaldir.$_FILES['Filedata']['name']);
	}
}
?>