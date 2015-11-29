<?php 

	$id = $_GET['id'];

	$db = mysqli_connect("localhost", "hackathon", "mjpE544~", "hackathon");

	if (!$db) {
		echo mysqli_connect_error();
		return 1;
	}

	$query = 'UPDATE user_vehicles SET deleted = 1 WHERE id = ' . $id . ';';
	$result = mysqli_query($db, $query);

	return 0;

?>