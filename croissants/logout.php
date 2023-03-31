<?php
session_start();
require_once("fonction.php");
destroyCookies();
session_destroy();
header("Location: index.php");
?>
