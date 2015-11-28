<?php 

	session_start();

	$db = mysqli_connect("localhost", "hackathon", "mjpE544~", "hackathon");

	if (!$db) {
	 	echo mysqli_connect_error();
	 	exit;
	}

	$make  = $_POST['make'];
	$model = $_POST['model'];
	$year  = $_POST['year'];

	$query = 'INSERT INTO user_vehicles (user_id, year, make, model) VALUES (' . $_SESSION['id'] . ', ' . $year . ', "' . $make .'", "' . $model . '");';

	mysqli_query($db, $query);

	$url = '/';
	header('Location: ' . $url);
	die();

?>