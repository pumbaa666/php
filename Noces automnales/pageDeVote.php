<?php
session_start();
if(!isset($_SESSION['userId']))
    header("Location: error.php?type=autorisation");
?>
<html><head><title>Noces Automnales</title></head><body background = './images/willy-grey.jpg'>
<?php
    include('instruction.php');
    include('voteForm.php');
    include('voirVote.php');
    include('menu.php');
?>
</body></html>