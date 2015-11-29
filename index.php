<?php include 'partials/preprocess.php'; ?>

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
          $query = "SELECT * FROM user_vehicles WHERE deleted <> 1 and user_id=" . $_SESSION['id'] . ';';
          $result = mysqli_query($db, $query);

          if($result->num_rows != 0){
            // Print user vehicles
            while($row = mysqli_fetch_assoc($result)){
              $year  = $row['year'];
              $make  = $row['make'];
              $model = $row['model'];
              $id    = $row['id'];

            ?>
            
            <div class=".col-xs-12 .col-sm-12 .col-md-12">
              <ul class="list-group">
                <li class="list-group-item active">
                  <?php echo strtoupper($year) . ' ' . strtoupper($make) . ' ' . strtoupper($model); ?>
                  <span class="remove" data-id="<?php echo $id; ?>">Remove</span>
                </li>
                <?php 
                  // Find any recall matches for this vehicle
                  $query = 'SELECT recall_number, manufacturer_recall_number, category_en, units_affected, system_type_en, notification_type_en, comment_en FROM recalls WHERE year = ' . $year . ' and make = "' . $make . '" and model = "' . $model . '";';
                  $res = mysqli_query($db, $query);

                  while($r = mysqli_fetch_assoc($res)){
                    //Print results
                  ?>

                    <li class="list-group-item">
                      <ul class="list-group-internal">
                        <li class="list-group-item-internal"><span class="large-text"><?php echo $r['system_type_en']; ?></span> <span><?php echo $r['units_affected']; ?> Units Affected</span></li>
                        <li class="list-group-item-internal"><?php echo $r['comment_en']; ?></li>
                        <li class="list-group-item-internal small-text faux-footer">Recall Number: <?php echo $r['recall_number'] ?> | Manufacturer Recall Number: <?php if($r['manufacturer_recall_number'] == "null") echo 'not specified'; else echo $r['manufacturer_recall_number']; ?></li>                        
                      </ul>
                    </li>
                <?php
                  }
                ?>
              </ul>
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

    <script type="text/javascript">

      $('.remove').click(function(){

        var id = $(this).attr('data-id');
        var list = $(this).closest('.list-group');
        
        $.ajax({
          url : 'processes/remove_vehicle_remove.php',
          method : 'get',
          data : 'id=' + id
        })
        .done(function(data){
          if(data == 1){
            //failed
            //i dont know what to do if it fails
          } else {
            $(list).slideUp(1000);
          }
        })
        .fail(function(){

        });
      });

    </script>
  </body>
</html>
