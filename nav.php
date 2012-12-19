	<div class="navbar navbar-fixed-top">
	  <div class="navbar-inner">
		<div class="container">
		<a class="brand" href="/">Exportagram</a>
		<?php if(isset($_SESSION['instagramuser'])){ ?>
		<div class="pull-right">
			<ul class="nav">
				<li>
					<a><img src="<?php echo $_SESSION['instagramuser']['user']['profile_picture'] ?>" height="22" width="22" /> <?php echo $_SESSION['instagramuser']['user']['username'] ?></a>
				</li>
			</ul>
		</div>
		<?php } ?>
	  </div>
	</div>
	</div>