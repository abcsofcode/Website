<!DOCTYPE html>
<html lang="en">
	<head>
    	<?php include 'partials/head.php'; ?>
    	<title>Add Vehicle</title>
  	</head>
  	<body>
	    <?php include 'partials/menu.php'; ?>
	    <div class="container">
	      	<div class="page-header">
	        	<h1>Add Vehicle</h1>
	      	</div>
	      	<div class="content">
	        	<form action="processes/add_vehicle_add.php" method="post" class="form">
	        		<div class="col-xs-12 col-sm-4">
	        			<div class="form-group">
	        				<label for="year">Year</label>
	        				<input type="number" class="form-control" name="year">
	        			</div>
	        		</div>
	        		<div class="col-xs-12 col-sm-4">
	        			<div class="form-group">
	        				<label for="make">Make</label>
	        				<input type="text" class="form-control" name="make">
	        			</div>
	        		</div>
	        		<div class="col-xs-12 col-sm-4">
	        			<div class="form-group">
	        				<label for="model">Model</label>
	        				<input type="text" class="form-control" name="model">
	        			</div>
	        		</div>
	        		<div class="col-xs-12 col-sm-12 col-md-12">
	        			<input type="submit" class="btn btn-primary btn-block" value="ADD">
	        		</div>
	        	</form>
	      	</div>
	    </div>

    	<?php include 'partials/footer.php'; ?>
 	</body>
</html>