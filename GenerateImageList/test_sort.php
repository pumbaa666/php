<?php
$ar = array(
       array("a", "e", "b", "d", "c"),
       array( 1,   2,   3,   4,   5)
      );
array_multisort($ar[0], $ar[1]);

for($i = 0; $i < 5; $i++)
	echo $ar[0][$i].", ".$ar[1][$i]."<br>";
?>