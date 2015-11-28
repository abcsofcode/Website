<?php 

	$db = mysqli_connect("localhost", "hackathon", "mjpE544~", "hackathon");

	if (!$link) {
	 	echo mysqli_connect_error();
	}

	$username = $_POST['username'];
	$password = $_POST['password'];

	$query = 'SELECT * FROM users WHERE username = "' . $username . '" AND password="' . $password . '";';
	$result = mysqli_query($db, $query);

	if (empty($result)) {
		echo 'No login';
	} else {
		$row = mysqli_fetch_assoc($result);
	  	session_start();
		$_SESSION['username'] = $row['username'];
		$_SESSION['email']    = $row['email'];
		$_SESSION['id']       = $row['id'];
		
		$url = '/';
		header('Location: ' . $url);
		die();
	}
?>