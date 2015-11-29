<?php include 'partials/preprocess.php'; ?>

<?php 

	$year  = $_POST['year'];
	$make  = $_POST['make'];
	$model = $_POST['model'];

	//Generate query
	$thing = array('WHERE', 'and');
	if($year){
		$queryYear = $thing[0] . ' year = ' . $year . ' ';
		if($thing[0] == 'WHERE'){
			unset($thing[0]);
			$thing = array_values($thing);
		}
	}

	if($make){
		$queryMake = $thing[0] . ' make = "' . $make . '" ';
		if($thing[0] == 'WHERE'){
			unset($thing[0]);
		}
	}

	if($model){
		$queryModel = $thing[0] . ' model = "' . $model . '" ';
		if($thing[0] == 'WHERE'){
			unset($thing[0]);
		}
	}

	$query = 'SELECT recall_number, year, manufacturer_recall_number, category_en, make, model, units_affected, system_type_en, notification_type_en, comment_en FROM recalls ' . $queryYear . $queryMake . $queryModel . ';';
	$db = mysqli_connect("localhost", "hackathon", "mjpE544~", "hackathon");
	
	$result = mysqli_query($db, $query);
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
	        	<h1>Results</h1>
	      	</div>
	      	<div class="content">
	      		<div class=".col-xs-12 .col-sm-12 .col-md-12">
		              	
	        	<?php 
	        		if($result->num_rows == 0){
	        	?>	
	        		<li class="list-group-item">
	        			No results found.
	        		</li>

	        	<?php		
	        		} else {
	        			while($row = mysqli_fetch_assoc($result)){	
					?>
						<ul class="list-group">
							<li class="list-group-item">
		                        <ul class="list-group-internal">
		                        	<li class="list-group-item-internal"><span class="large-text"><?php echo $row['system_type_en']; ?></span> <span><?php echo $row['units_affected']; ?> Units Affected</span><div style="float: right"><?php echo $row['category_en']; ?></div></li>
		                            <li class="list-group-item-internal"><?php echo $row['comment_en']; ?></li>
		                            <li class="list-group-item-internal small-text faux-footer">Recall Number: <?php echo $row['recall_number'] ?> | Manufacturer Recall Number: <?php if($row['manufacturer_recall_number'] == "null") echo 'not specified'; else echo $row['manufacturer_recall_number']; ?></li>                        
		                      	</ul>
	                        </li>
                        </ul>
				<?php
						}
					}
	        	?>
	      	</div>
	    </div>

    	<?php include 'partials/footer.php'; ?>
 	</body>
</html>