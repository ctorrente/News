<?php
	$conn = @mysqli_connect('localhost', 'root', 'sS201211235') or die('Unable to connect to the database');
	@mysqli_select_db($conn, 'dcs_site') or die('Unable to load Database');
?>
