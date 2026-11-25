<?php
	session_start();
	session_destroy();	
	$url = "http://" . $_SERVER['SERVER_NAME'] . "/index.php";
	echo "<SCRIPT>window.location='$url';</SCRIPT>"; 
?>