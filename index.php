<!doctype html>
<html>
<head>
	<title>Export/Download Your Instagram Photos | Exportagram </title>
	<meta name="description" content="Export and Download all of your Instagram photos to your computer. Backup your Instagram account before deleting it." />
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>

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
	
<?php include('nav.php') ?>
	
	<div class="container">
		<div class="row">
			<div class="span5">
				<?php if(isset($_SESSION['instagramuser'])){ ?>
					<h4>Hello: <?php echo $_SESSION['instagramuser']['user']['username'] ?></h4>
				<?php } ?>
				<p>This is a downloader. Please click the button, then wait while we fetch all your photos and deliver you a zip file</p>
				<ol>
					<li>Connect exportagram to your instagram account</li>
					<li>Download your all your photos in a zip file</li>
				</ol>
				<?php if(!isset($_SESSION['instagramuser'])){ ?>
					<a class="btn btn-primary" href="https://api.instagram.com/oauth/authorize/?client_id=5e31a29af73348a1895f12e8089ffe60&redirect_uri=http://exportagram.com/&response_type=code"><i class="icon-circle-arrow-down icon-white"> </i> Connect to Instagram</a>
					
				<?php }else{ ?>
					<a class="btn btn-primary btn-large" id="export" href="/export/"><i class="icon-circle-arrow-down icon-white"> </i> Export your Images</a>
					<a class="hidden btn btn-primary btn-large" id="loading"><img src="img/ajax-loader.gif" /> Loading Images...</a>
				<?php } ?>
			</div>
		</div>
	</div>
<?php include('footer.php') ?>	
</body>
</html>