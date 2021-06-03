<?php 
	include "config.php";
	$clickCounter = $_POST['clickCounter'];
	$errorCounter = $_POST['errorCounter'];
	$playtime = $_POST['playtime'];

	$sql = "INSERT INTO `ClickGame` (`id`, `clickCount`, `errorCount`, `gameTime`) VALUES (NULL, '$clickCounter', '$errorCounter', '$playtime')";
	$mysqli->query($sql);

	$parsed = date_parse($playtime);
    $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
?>
<tr>
	<td><?php echo $mysqli->insert_id;?></td>
	<td><?php echo $clickCounter;?></td>
	<td><?php echo $errorCounter;?></td>
	<td><?php echo $seconds;?></td>
</tr>
<?php exit;?>