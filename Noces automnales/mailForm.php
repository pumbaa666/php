<?php
session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");
if($_SESSION['userId'] != 1)
    header("Location: error.php?type=autorisationAdmin");
?>
<html><head><title>Noces Automnales</title></head><body>
    
</body></html>