<!DOCTYPE html>
<?php
	include 'connect.php';
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	
		<title>HIMYM Forum</title>
	
		<!-- Bootstrap -->
		<link href="css/bootstrap.css" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	
		<div class="container">
			<div class="row">
				<center><img src="images/pic12.jpg" alt="Image cannot be displayed."/>
					<img src="images/pic13.jpg" alt="Image cannot be displayed."/>
					<img src="images/pic14.jpg" alt="Image cannot be displayed."/>
					<img src="images/pic15.jpg" alt="Image cannot be displayed."/>
					<img src="images/pic16.jpg" alt="Image cannot be displayed."/>
					<img src="images/pic17.jpg" alt="Image cannot be displayed."/></center>
			</div>	
		</div>
	</head>
  
	<body>
	
	<?php
		if ($_SESSION['signed_in'] && ($_SESSION['user_level'] == 1 || $_SESSION['user_level'] == 2))	{	//Admin or mod is signed in
	?>

	<div class="bs-docs-section clearfix">
		<div class="row">
			<div class="col-lg-12">          
				<div class="bs-example">
					<div class="navbar navbar-default">                
						<div id="menu" class="navbar-collapse collapse navbar-responsive-collapse ">
							<ul class="nav navbar-nav">
								<li class="active"><a class="navbar-brand" href="index.php">Home</a></li>
								<li><a href="categories.php">Forum</a></li>
								<li><a href="usermang.php">Control Panel</a></li>
								<li><a href="userprofile.php">User Profile</a></li>
								<li><a href="contactus.php">Contact Us</a></li>
								<li><a href="signout.php">Sign Out</a></li>										
								<li>
									<form class="form-inline" method="post" action="search.php">
										 <!--<input style="margin-top:12px" class="form-control" name="search" placeholder="Search"/>	
										 <button class="btn btn-default" type="button">Search</button>-->
										 <input style="margin-top:6px" type="text" class="form-control"name="search" placeholder="Search"/>
										 <input style="margin-top:6px"class="btn btn-default" type="submit"></input>	
									</form>	
								</li>	
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php
		}
		else if ($_SESSION['signed_in'] && $_SESSION['user_level'] == 0)	{	// Reg user is signed in
	?>

	<div class="bs-docs-section clearfix">
        <div class="row">
          <div class="col-lg-12">          
            <div class="bs-example">
              <div class="navbar navbar-default">                
                 <div id="menu" class="navbar-collapse collapse navbar-responsive-collapse ">
					<ul class="nav navbar-nav">
						<li class="active"><a class="navbar-brand" href="index.php">Home</a></li>
						<li><a href="categories.php">Forum</a></li>
						<li><a href="userprofile.php">User Profile</a></li>
						<li><a href="contactus.php">Contact Us</a></li>
						<li><a href="signout.php">Sign Out</a></li>
					</ul>
					<form class="form-inline" method="post" action="search.php">
								 <!--<input style="margin-top:12px" class="form-control" name="search" placeholder="Search"/>	
								 <button class="btn btn-default" type="button">Search</button>-->
								 <input style="margin-top:6px" type="text" class="form-control"name="search" placeholder="Search"/>
								 <input style="margin-top:6px"class="btn btn-default" type="submit"></input>	
								</form>	
				</div>               
			  </div>
			</div>
		  </div>
		</div>
	</div>

	<?php
		}
		else	{
	?>

	<div class="bs-docs-section clearfix">
        <div class="row">
          <div class="col-lg-12">          
            <div class="bs-example">
              <div class="navbar navbar-default">                
                 <div id="menu" class="navbar-collapse collapse navbar-responsive-collapse ">
					<ul class="nav navbar-nav">
						<li class="active"><a class="navbar-brand" href="index.php">Home</a></li>
						<li><a href="categories.php">Forum</a></li>
						<li><a href="signin.php">Sign In</a></li>
						<li><a href="signup.php">Sign Up</a></li>
						<li><a href="contactus.php">Contact Us</a></li>
					</ul>
				</div>               
			  </div>
			</div>
		  </div>
		</div>
	</div>

	<?php
		}
	?>