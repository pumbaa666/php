<?php
$style = 'text';
if(isset($_GET['style']))
	$style = $_GET['style'];
	
$path = '';
if(isset($_GET['path']))
	$path = $_GET['path'];

$maxDepth = '';
if(isset($_GET['maxDepth']))
	$maxDepth = $_GET['maxDepth'];

$get = "?path=".$path."&maxDepth=".$maxDepth;

if($style == 'text')
{
	header("Location:printText.php".$get);
	return;
}
if($style == 'html')
{
	header("Location:printHTML.php".$get);
	return;
}
?>