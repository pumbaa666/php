<?php
function formatDateTime($dateTime)
{
    $dateTime = split(" ",$dateTime);
    $date = $dateTime[0];
    $dateSplit = split("-", $date);
    $date = $dateSplit[2].".".$dateSplit[1].".".$dateSplit[0];
    $time = $dateTime[1];
    $timeSplit = split(":", $time);
    $time = $timeSplit[0]."h".$timeSplit[1];
    
    return $date." - ".$time;
}

function formatMessage($text)
{
    return str_replace("\'","'",$text);
}

function isAdmin()
{
    session_start();
    if($_SESSION['userId'] != 1)
        return FALSE;
    return TRUE;
}
?>