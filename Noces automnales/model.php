<?php
session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");
?>
<html><head><title>Noces Automnales</title></head><body>

</body></html>