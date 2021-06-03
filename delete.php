<?php 
include "config.php";
$sql = "TRUNCATE ClickGame";
$mysqli->query($sql);
header("LOCATION: MidTermAnswer.php");
?>