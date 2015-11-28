<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'partials/head.php'; ?>

    <title>LemonAide</title>
  </head>
  <body>
    <?php 

      $db = mysqli_connect("localhost", "hackathon", "mjpE544~", "hackathon");

      if (!$db) {
        echo mysqli_connect_error();
        exit;
      }
    ?>

  	<?php include 'partials/menu.php'; ?>
    <div class="container">
    	<div class="page-header">
    		<h1>Your Vehicles</h1>
    	</div>
    	<div class="content">
    		<?php 
          // Get user vehicles
          $query = "SELECT * FROM user_vehicles WHERE user_id=" . $_SESSION['id'] . ';';
          $result = mysqli_query($db, $query);

          if($result->num_rows != 0){
            // Print user vehicles
            while($row = mysqli_fetch_assoc($result)){
            ?>
            
            <div class=".col-xs-12 .col-sm-12 .col-md-12">
              year: <?php echo $row['year']; ?>
              make: <?php echo $row['make']; ?>
              model: <?php echo $row['model']; ?>
            </div>
          <?php
            }
          } else {
            echo '<div class=".col-xs-12 .col-sm-12 .col-md-12">
              You need to add a vehicle
            </div>';
          }
          ?>
    	</div>
    </div>

    <?php include 'partials/footer.php'; ?>
  </body>
</html>
