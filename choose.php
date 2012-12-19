	<?php 
	session_start();
		require_once('pclzip.lib.php');	


	/*Exportagram*/
	$clientid = "5e31a29af73348a1895f12e8089ffe60";
	$clientsecret = "3f18070c73974bc48b69aa608b65f44a";
	$website_url = "http://exportagram.com";
	$redirect_url = "http://exportagram.com/export/";
	

	$photoarray = array('readme.txt');
	$output = "";
	if(isset($_SESSION['instagramuser'])){
		getPhotos();
	}
	
	
	function getPhotos(){

		if($_SESSION['instagramuser']){
			$token = $_SESSION['instagramuser']['access_token'];
			$id = $_SESSION['instagramuser']['user']['id'];
			$ch = curl_init(); 
			$url = "https://api.instagram.com/v1/users/".$_SESSION['instagramuser']['user']['id']."/media/recent?count=1000&access_token=".$_SESSION['instagramuser']['access_token'];
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
			$result = create_zip($GLOBALS['photoarray'], 'zips/instagram-'.$_SESSION['instagramuser']['user']['id'].'.zip');
		}
	}
	
	function displayItems($json){
		
		foreach ( $json['data'] as $photo ){
			array_push($GLOBALS['photoarray'], 'images/'.$photo['id'].'.jpg');
			$GLOBALS['output'] .= "<img src".$photo['images']['thumbnail']['url']." /> ";
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
				$_SESSION['downloadurl'] = "";
			}
		}else{
			 $archive = new PclZip($destination);
			  $v_list = $archive->add($files);
			  if ($v_list == 0) {
				die("Error : ".$archive->errorInfo(true));
			  }
			  $_SESSION['downloadurl'] = $GLOBALS['website_url'].'/'.$destination;
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

<!doctype html>
<html>
<head>
	<title>Export Your Instagram Photos</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
	<script>
	
	</script>
</head>
<body>



	
<?php include('nav.php') ?>
	<div class="container">
		<div class="row">
			<div class="span7">
				

				<p>	All of your photos have been saved and zipped into one file. Use the download button below to grab your photos</p>
				<p><a href="<?php echo '/zips/instagram-'.$_SESSION['instagramuser']['user']['id'].'.zip' ?>" class="btn btn-primary btn-large"><i class="icon-circle-arrow-down icon-white"> </i> Download your photos</a></p>
				
			</div>
			<div class="span1"></div>
			<div class="span4">
				<div class="well">
				<h3>Delete your account?</h3>
				<p>If you are exporting your photos with the intention of dropping instagram so they cannot sell your photos to advertisers, please use the link below to submit you account removal request.</p>
				<p><a href="https://instagram.com/accounts/remove/request/" class="btn btn-small btn-danger">Account Removal Form</a></p>
			</div>
			<div class="span7"><?php echo $output ?></div>
		</div>
	</div>
<?php include('footer.php') ?>	
</body>
</html>