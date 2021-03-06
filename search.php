<?php include 'partials/preprocess.php'; ?>

<?php 
	$db = mysqli_connect("localhost", "hackathon", "mjpE544~", "hackathon");

	//load makes and models for autofills
	$makes  = array();
	$models = array();

	$query = 'SELECT DISTINCT make FROM recalls;';
	$result = mysqli_query($db, $query);

	while($row = mysqli_fetch_assoc($result)){
		array_push($makes, $row['make']);
	}

	$query = 'SELECT DISTINCT model FROM recalls;';
	$result = mysqli_query($db, $query);

	while($row = mysqli_fetch_assoc($result)){
		array_push($models, $row['model']);
	}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
    	<?php include 'partials/head.php'; ?>

    	<title>LemonAide</title>
  	</head>
  	<body>
	    <?php include 'partials/menu.php'; ?>
	    <div class="container">
	      	<div class="page-header">
	        	<h1>Search</h1>
	      	</div>
	      	<div class="content">
	        	<form action="results.php" method="post" class="form">
	        		<div class="col-xs-12 col-sm-4">
	        			<div class="form-group">
		        			<label for="year">Year</label>
		        			<input type="number" class="form-control" name="year">
		        		</div>
	        		</div>
	        		<div class="col-xs-12 col-sm-4">
	        			<div class="form-group">
		        			<label for="make">Make</label>
		        			<input type="text" id="makes" class="form-control" name="make">
		        		</div>
	        		</div>
	        		<div class="col-xs-12 col-sm-4">
	        			<div class="form-group">
		        			<label for="model">Model</label>
		        			<input type="text" id="models" class="form-control" name="model">
		        		</div>
	        		</div>
	        		<div class="col-xs-12 col-sm-12">
	        			<input type="submit" class="btn btn-primary btn-block" value="Search">
	        		</div>
	        	</form>
	      	</div>
	    </div>

    	<?php include 'partials/footer.php'; ?>
    	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

    	<script>
			$(function() {
				var makes = <?php echo json_encode($makes); ?>;
				$( "#makes" ).autocomplete({
					source: makes
				});
			});

			$(function() {
				var models = <?php echo json_encode($models); ?>;
				$( "#models" ).autocomplete({
					source: models
				});
			});
		</script>
 	</body>
</html>