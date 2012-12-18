<!doctype html>
<html>
<head>
	<title>Export Your Instagram Photos</title>

	<link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	<style>
		.brand{font-family: 'Lobster', cursive;}
		.row{
				margin-top:75px;
		}
	</style>
</head>
<body>
	<?php 
	require_once('pclzip.lib.php');	
	
	
	$photoarray = array('readme.txt');
	$userid = "";
	
	/*Exportagram
	$clientid = "5e31a29af73348a1895f12e8089ffe60";
	$clientsecret = "3f18070c73974bc48b69aa608b65f44a";
	$website_url = "http://exportagram.com";
	$redirect_url = "http://exportagram.com/export/";
	*/
	
	/*Steveottenad*/
	$clientid = "66cc00bdc3b3426994c777b70e239f79";
	$clientsecret = "8b0c0600578f4402963c22f27a9baef1";
	$website_url = "http://export.steveottenad.com";
	$redirect_url = "http://export.steveottenad.com/export/";
		
	$downloadurl = "";
	$username = "";
	if ( isset( $_GET['code'] )){
		$code = $_GET['code'];
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'https://api.instagram.com/oauth/access_token',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'client_id' => $clientid,
				'client_secret' => $clientsecret,
				'grant_type' => 'authorization_code',
				'redirect_uri' => $redirect_url,
				'code' => $code
			)
		));
		$resp = curl_exec($curl);
		curl_close($curl);
		
		$json = json_decode( $resp, true );
		$token = "";
		$id = 0;
		
		if( isset($json['access_token'])){
			//echo($json['user']['username']."<br/><br/>");
			$GLOBALS['username'] = $json['user']['username'];
			$token = $json['access_token'];
			$id = $json['user']['id'];
			$GLOBALS['userid'] = $json['user']['id'];
			$ch = curl_init(); 
			$url = "https://api.instagram.com/v1/users/".$id."/media/recent?count=1000&access_token=".$token;
			curl_setopt($ch, CURLOPT_URL, $url); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch); 
			curl_close($ch);  
			$results = json_decode( $output, true );
			
		
			if(isset($results['data'])){
				displayItems($results);
				moreData($results);
			}
		}

	}
	
	function moreData($json){
		if( isset($json['pagination']['next_url'])){
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $json['pagination']['next_url']); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch); 
			curl_close($ch);  
			$results = json_decode( $output, true );
			if(isset($results['data'])){
				displayItems($results);
				moreData($results);
			}
		}else{
			//We have no more pages, so make the zip.
			$result = create_zip($GLOBALS['photoarray'], 'zips/instagram-'.$GLOBALS['userid'].'.zip');
		}
	}
	
	function displayItems($json){
		
		foreach ( $json['data'] as $photo ){
			array_push($GLOBALS['photoarray'], 'images/'.$photo['id'].'.jpg');
			save_image($photo['images']['standard_resolution']['url'], 'images/'.$photo['id'].'.jpg' );		
		}
	}
	
	/* creates a compressed zip file */
	function create_zip($files = array(),$destination = '',$overwrite = false) {
		if( file_exists($destination)){
			$path = realpath($destination);
			$readable = is_readable($path);
			if($readable){
				unlink($path);
			}
		}else{
			 $archive = new PclZip($destination);
			  $v_list = $archive->add($files);
			  if ($v_list == 0) {
				die("Error : ".$archive->errorInfo(true));
			  }
			  $GLOBALS['downloadurl'] = $website_url.'/'.$destination;
		}
	}
	
	function save_image($img,$fullpath){
		if (!file_exists($fullpath)){
			$ch = curl_init ($img);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			$rawdata=curl_exec($ch);
			curl_close ($ch);
			if(file_exists($fullpath)){
				unlink($fullpath);
			}
			$fp = fopen($fullpath,'x');
			fwrite($fp, $rawdata);
			fclose($fp); 
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
				<p>
					<?php echo $username ?><br />
					<a href="<?php echo $GLOBALS['downloadurl'] ?>" class="btn btn-primary"><i class="icon-circle-arrow-down icon-white"> </i> Download your photos</a>
				</p>
			</div>
		</div>

	</div>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>