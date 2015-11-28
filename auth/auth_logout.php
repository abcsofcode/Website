<?php 
	session_start();
	session_unset(); 
	session_destroy(); 

	$url = '/';
	header('Location: ' . $url);
	die();
?>