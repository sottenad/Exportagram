<!doctype html>
<html>
<head>
	<title>Export Your Instagram Photos</title>

	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	<style>
		.brand{
			font-family: 'Lobster', cursive;
		}
		.row{
				margin-top:75px;
		}
		.hidden{
			display:none;
		}
	</style>
</head>
<body>	
	<?php 
		session_start();
		//session_destroy();
		
		/*Exportagram*/
		$clientid = "5e31a29af73348a1895f12e8089ffe60";
		$clientsecret = "3f18070c73974bc48b69aa608b65f44a";
		$website_url = "http://exportagram.com";
		$redirect_url = "http://exportagram.com/";
		
		
		/*Steveottenad
		$clientid = "66cc00bdc3b3426994c777b70e239f79";
		$clientsecret = "8b0c0600578f4402963c22f27a9baef1";
		$website_url = "http://export.steveottenad.com";
		$redirect_url = "http://export.steveottenad.com/";
		*/
		if ( isset( $_GET['code']) && !isset($_SESSION['instagramuser']) ){
			getToken();
		}
		function getToken(){
			$code = $_GET['code'];
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'https://api.instagram.com/oauth/access_token',
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'client_id' => $GLOBALS['clientid'],
					'client_secret' => $GLOBALS['clientsecret'],
					'grant_type' => 'authorization_code',
					'redirect_uri' => $GLOBALS['redirect_url'],
					'code' => $code
				)
			));
			$resp = curl_exec($curl);
			curl_close($curl);
			
			$json = json_decode( $resp, true );
			$token = "";
			$id = 0;
			if( isset($json['access_token'])){
				$_SESSION['instagramuser'] = $json;		
				
			}
		}
	?>
	<div class="navbar navbar-fixed-top">
	  <div class="navbar-inner">
		<div class="container">
		<a class="brand" href="/">Exportagram</a>
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
				<?php if(isset($_SESSION['instagramuser'])){ ?>
					<p>hello: <?php echo $_SESSION['instagramuser']['user']['username'] ?></p>
				<?php } ?>
				<p>This is a downloader. Please click the button, then wait while we fetch all your photos and deliver you a zip file</p>
				<p>First, connect exportagram to your instagram account, then export</p>
				<?php if(!isset($_SESSION['instagramuser'])){ ?>
					<a class="btn btn-primary" href="https://api.instagram.com/oauth/authorize/?client_id=5e31a29af73348a1895f12e8089ffe60&redirect_uri=http://exportagram.com/&response_type=code"><i class="icon-circle-arrow-down icon-white"> </i> Connect to Instagram</a>
					
				<?php }else{ ?>
					<a class="btn btn-primary btn-large" id="export" href="/export/"><i class="icon-circle-arrow-down icon-white"> </i> Export your Images</a>
					<a class="hidden btn btn-primary btn-large" id="loading"><img src="img/ajax-loader.gif" /> Loading Images...</a>
				<?php } ?>
		</div>
	</div>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
		$(function(){
			$('#export').click(function(){
				$(this).hide();
				$('#loading').show();
			});
		});
	</script>
	<script type="text/javascript">

	var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-37157813-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</body>
</html>