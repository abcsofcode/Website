<?php 
	//anything before page content

	session_start();

	//Pages that require logins
	$requireLogin = array(
		'/',
		'/index.php', 
		'/logout.php', 
		'/add_vehicle.php'
	);

	if(in_array($_SERVER['REQUEST_URI'], $requireLogin) && !isset($_SESSION['username'])){
		$url = 'login.php';
		header('Location: ' . $url);
		die();
	}
?>
