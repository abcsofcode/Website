<?php 

	$db = mysqli_connect("localhost", "hackathon", "mjpE544~", "hackathon");

	if (!$db) {
	 	echo mysqli_connect_error();
	 	exit;
	}

	$username = $_POST['username'];
	$password = $_POST['password'];

	$query = 'SELECT * FROM users WHERE username = "' . $username . '" AND password="' . $password . '";';
	$result = mysqli_query($db, $query);

	if ($result->num_rows == 0) {
		$url = '../login.php';
		header('Location: ' . $url);
		die();
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