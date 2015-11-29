<?php include 'partials/preprocess.php'; ?>

<!DOCTYPE html>
<html lang="en">
	<head>
    	<?php include 'partials/head.php'; ?>

    	<title>LemonAide</title>
  	</head>
  	<body>
	    <?php //include 'partials/menu.php'; ?>
	    <div class="container">
	      	<div class="page-header">
	        	<h1>Login</h1>
	      	</div>
	      	<div class="content">
	        	<form class="form" method="post" action="auth/auth_login.php">
	        		<div class="col-md-6 col-md-offset-3">
	        			<div class="form-group">
	        				<label for="username">Username</label>
	        				<input type="text" name="username" class="form-control">
	        			</div>
	        			<div class="form-group">
	        				<label for="password">Password</label>
	        				<input type="password" name="password" class="form-control">
	        			</div>
	        			<input type="submit" class="btn btn-primary btn-block">
	        		</div>
	        	</form>
	      	</div>
	    </div>

    	<?php include 'partials/footer.php'; ?>
 	</body>
</html>