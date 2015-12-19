<?php
	include 'headers/header_profile.php';
?>

<?php
	if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
		$sql1 = "SELECT *
				 FROM proj4_users
				 WHERE user_id = " . $_SESSION['user_id'];
		$results1 = $con->query($sql1);
		$row1 = $results1->fetch_assoc();
		
		if ($row1['user_dp'] != '')
			$path = $row1['user_dp'];
		else
			$path = 'images/unknown.jpeg';
		
		$sql7 = "SELECT role_id, role_name
				 FROM proj4_roles
				 WHERE role_id = " . $row1['user_level'];
		$results7 = $con->query($sql7);
		$row7 = $results7->fetch_assoc();
		
		$sql2 = "SELECT count(topic_id) num_topic
				 FROM proj4_topics
				 WHERE topic_by = " . $_SESSION['user_id'];
		$results2 = $con->query($sql2);
		$row2 = $results2->fetch_assoc();
		
		$sql3 = "SELECT count(post_id) num_post
				 FROM proj4_posts
				 WHERE post_by = " . $_SESSION['user_id'] . " AND post_deleted = 0";
		$results3 = $con->query($sql3);
		$row3 = $results3->fetch_assoc();
		
		$sql8 = "SELECT user_id, COUNT(post_id) as num_posts
				 FROM proj4_users LEFT JOIN proj4_posts ON user_id=post_by
				 WHERE user_id=" .  $row1['user_id'] .
				 " GROUP BY post_by";
		$results8 = $con->query($sql8);
		$row8 = $results8->fetch_assoc();
		
		$user_level = 'newbie';
		if ($row8['num_posts'] >= 10 && $row8['num_posts'] < 20)	{
			$user_level = 'intermediate';
		}
		else if ($row8['num_posts'] >= 20)	{
			$user_level = 'master';
		}
?>

<div class='container'>
	<div class='row'>
		<h1><?php echo $row1['user_name'] . " (" . $row7['role_name'] . ")"; ?></h1>
		<h3>&nbsp;&nbsp;&nbsp;<?php echo $user_level; ?></h3>
	</div>
	<br>
	<div class='row'>
		<div class='col-lg-3'>
			<div class='row'>
			<a href="<?php echo $path?>" onclick="window.open (this.href, 'large-view'); return false">
				<img src="<?php echo $path?>" alt="Image cannot be displayed." style="max-width: 100%" class='img-rounded'/>
			</a>
			</div>
			<div class='row'>
			<form action="" method="post" enctype="multipart/form-data">
				<input type="file" name="file" id="file" />
				<input type="submit" name="submit" value="Update">
			</form>
			</div>
<?php
if ($_POST['submit'] == "Update") 
	{
		$allowedExts = array("jpg", "jpeg", "gif", "png");
		$extension = end(explode(".", $_FILES["file"]["name"]));
		if ((($_FILES["file"]["type"] == "image/jpg")||
			 ($_FILES["file"]["type"] == "image/gif") || 
		     ($_FILES["file"]["type"] == "image/jpeg") || 
			 ($_FILES["file"]["type"] == "image/png") || 
			 ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 9000000)&& in_array($extension, $allowedExts))
		{
			if ($_FILES["file"]["error"] > 0)
			{
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
			}
			else
			{
				echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	  
				// if (file_exists("pictures/" . $_FILES["file"]["name"]))
				// {
						// echo $_FILES["file"]["name"] . " already exists. ";
				// }
				//else
				//{
						$id2=uniqid();
						move_uploaded_file($_FILES["file"]["tmp_name"],"images/" .$id2.$_FILES["file"]["name"]);
						$path =   "images/" .$id2.$_FILES["file"]["name"];
				//}
			}
			$sql9 = "UPDATE proj4_users SET user_dp='$path' where user_id = " . $_SESSION['user_id'];
			$results9 = $con->query($sql9);
			echo'<meta http-equiv="refresh" content="0.5">';
			//header('Location: '.$_SERVER['REQUEST_URI']);
	 }
	else
	  {
	  echo "No file uploaded";
	  }

	}
?>
		</div>
		<div class='col-lg-9'>
			<div class='row'>
				<div class='col-lg-3'>
					<h5><b>Email:</b></h5>
				</div>
				<div class='col-lg-3'>
					<h5><?php echo $row1['user_email']; ?></h5>
				</div>
				<div class='col-lg-1'>
					<h5><a href='updateemail.php'>Edit</a></h5>
				</div>
			</div>
			<div class='row'>
				<div class='col-lg-3'>
					<h5><b>Password:</b></h5>
				</div>
				<div class='col-lg-9'>
					<h5><a href='updatepass.php'>Update Password</a></h5>
				</div>
			</div>
			<div class='row'>
				<div class='col-lg-3'>
					<h5><b>Member Since:</b></h5>
				</div>
				<div class='col-lg-9'>
					<h5><?php echo $row1['user_date']; ?></h5>
				</div>
			</div>
			<div class='row'>
				<div class='col-lg-3'>
					<h5><b>Threads Created:</b></h5>
				</div>
				<div class='col-lg-9'>
					<h5><a href='userprofile.php'><?php echo $row2['num_topic']; ?></a></h5>
				</div>
			</div>
			<div class='row'>
				<div class='col-lg-3'>
					<h5><b>Number of Posts:</b></h5>
				</div>
				<div class='col-lg-9'>
					<h5><a href='userprofile2.php'><?php echo $row3['num_post']; ?></a></h5>
				</div>
			</div>
		</div>
	</div>
	<br><br>
	<div class='row'>
		<ul class='nav nav-tabs'>
			<li><a href='userprofile.php'>Threads</a></li>
			<li class='active'><a href='userprofile2.php'>Posts</a></li>
		</ul>
	</div>
	<div class='row'>
		<br>
	</div>

<?php
		$sql4 = "SELECT *
				 FROM proj4_posts
				 WHERE post_by = " . $_SESSION['user_id'] . " AND post_deleted = 0
				 ORDER BY post_date DESC";
		$results4 = $con->query($sql4);
		while ($row4 = $results4->fetch_assoc())	{
			$sql5 = "SELECT topic_id, topic_subject, topic_cat
					 FROM proj4_topics
					 WHERE topic_id = " . $row4['post_topic'];
			$results5 = $con->query($sql5);
			$row5 = $results5->fetch_assoc();
			$sql6 = "SELECT cat_id, cat_name
					 FROM proj4_categories
					 WHERE cat_id = " . $row5['topic_cat'];
			$results6 = $con->query($sql6);
			$row6 = $results6->fetch_assoc();
?>		
	<div class='panel panel-default'>
		<div class='panel-body'>
			<div class='row'>
				<div class='col-lg-11'>
					<a href='topics.php?id=<?php echo $row6['cat_id']; ?>'>
						<p><small><?php echo $row6['cat_name']; ?>
					</a>
					/
					<a href='replies.php?id=<?php echo $row5['topic_id']; ?>'>
						<?php echo $row5['topic_subject']; ?></small></p>
					</a>
				</div>
				<div class='col-lg-1'>
					<a href='editreply.php?id=<?php echo $row4['post_id']; ?>'><small>Edit</small></a>
				</div>
			</div>
			<div class='row'>
				<div class='col-lg-12'>
					<p><?php echo $row4['post_content']; ?></p>
				</div>
			</div>
			<div class='row'>
				<div class='col-lg-2'>
					<p class='text-muted'><small><?php echo $row4['post_date']; ?></small></p>
				</div>

<?php
			if ($row4['post_edited'] == 1)	{
?>

				<div class='col-lg-5'>
					<p class='text-muted'><small>Edited on: <?php echo $row4['post_edit']; ?></small></p>
				</div>
			
<?php
			}
?>
			</div>
		</div>
	</div>
	
<?php
		}
?>

</div>


<?php
	}
	else	{
		echo "You must be signed in to view this page";
	}
?>
<?php
	include 'footer.php';
?>