<!doctype html>
<html>
<head>
	<title>Export Your Instagram Photos</title>

	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	<style>
		.brand{font-family: 'Lobster', cursive;}
		.row{
				margin-top:75px;
		}
	</style>
</head>
<body>	
	<div class="navbar navbar-fixed-top">
	  <div class="navbar-inner">
		<div class="container">
		<a class="brand" href="#">Exportagram</a>
		<div class="pull-right">
			<ul class="nav">
				<li><a href="http://steveottenad.com">Steve Ottenad</a></li>
			</ul>
		</div>
	  </div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="span5">
				<?php if(isset($_SESSION['userjson']['user']['username'])){ ?>
					<p><?php echo $_SESSION['userjson']['user']['username'] ?></p>
				<?php } ?>
				<p>This is a downloader. Please click the button, then wait while we fetch all your photos and deliver you a zip file</p>
				<a class="btn btn-primary" href="https://api.instagram.com/oauth/authorize/?client_id=66cc00bdc3b3426994c777b70e239f79&redirect_uri=http://export.steveottenad.com/export/&response_type=code"><i class="icon-circle-arrow-down icon-white"> </i> Connect to Instagram</a>
		</div>
	</div>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>