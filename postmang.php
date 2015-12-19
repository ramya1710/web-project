<?php
	include 'headers/header_control.php';
?>

<?php
	if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
		if ($_SESSION['user_level'] == 1 || $_SESSION['user_level'] == 2)	{
			if ($_SESSION['user_level'] == 1)	{
				echo 
					"<div class='container'>
						<h1>Welcome " . $_SESSION['user_name'] . "!</h1>
						<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;What would you like to do? </h3>
						<ul class='nav nav-tabs'>
							<li><a href='usermang.php'>Manage Users</a></li>
							<li><a href='catmang.php'>Manage Categories</a></li>
							<li><a href='topicmang.php'>Manage Threads</a></li>
							<li class='active'><a href='postmang.php'>Manage Posts</a></li>
							<li><a href='pgmang.php'>Manage Pagination</a></li>
						</ul>";
			}
			else	{
				echo 
					"<div class='container'>
						<h1>Welcome " . $_SESSION['user_name'] . "!</h1>
						<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;What would you like to do? </h3>
						<ul class='nav nav-tabs'>
							<li><a href='usermang.php'>Manage Users</a></li>
							<li><a href='topicmang.php'>Manage Threads</a></li>
							<li class='active'><a href='postmang.php'>Manage Posts</a></li>
						</ul>";
			}
		}
		else
{
header('Location: index.php');
}
		$sql = "SELECT post_id, post_content, post_by, post_date, user_name, post_topic, topic_subject, topic_cat, cat_name, topic_froze, post_deleted
					FROM proj4_posts, proj4_users, proj4_categories, proj4_topics
					WHERE post_by=user_id AND topic_cat=cat_id AND post_topic=topic_id
					ORDER BY cat_name, topic_id";
		$results = $con->query($sql);
		if (!$results)	{
				echo "Posts cannot be displayed.  Please try again later.";
			}
		else	{
			$num_rows = $results->num_rows;
			if ($num_rows==0)	{
				echo "No posts have been created.";
			}
			else	{
?>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<div class="row">
						<div class="col-lg-1">
							<h3 class="panel-title">Category</h3>
						</div>
						<div class="col-lg-2">
							<h3 class="panel-title">Thread</h3>
						</div>
						<div class="col-lg-2">
							<h3 class="panel-title">Posted by</h3>
						</div>
						<div class="col-lg-3">
							<h3 class="panel-title">Message</h3>
						</div>
						<div class="col-lg-2">
							<h3 class="panel-title">Post Date</h3>
						</div>
						<div class="col-lg-1">
							<h3 class="panel-title">Edit</h3>
						</div>
						<div class="col-lg-1">
							<h3 class="panel-title">Delete</h3>
						</div>
					</div>
				</div>
				<div class="panel-body">
<?php
				while ($row = $results->fetch_assoc())	{
?>

					<div class='row'>
						<div class='col-lg-1'>
							<?php echo $row['cat_name']; ?>
						</div>
						<div class='col-lg-2'>
							<?php echo $row['topic_subject']; ?>
						</div>
						<div class='col-lg-2'>
							<?php echo $row['user_name']; ?>
						</div>
						<div class='col-lg-3'>
							<?php echo $row['post_content']; ?>
						</div>
						<div class='col-lg-2'>
							<?php echo $row['post_date']; ?>
						</div>
						<div class='col-lg-1'>
<?php
						if ($row['post_deleted'] == 1)	{
							echo "Edit";
						}
						else if ($row['topic_froze'] == 1)	{
							echo "Froze";
						}
						else	{
							echo "<a href='editpost.php?id=" . $row['post_id'] . "'>Edit</a>";
						}
?>
							
						</div>
						<div class='col-lg-1'>
<?php
						if ($row['post_deleted'] == 0)	{
							echo "<a href='deletepost.php?id=" . $row['post_id'] . "'>Delete</a>";
						}
						else	{
							echo "Deleted";
						}
?>
						</div>
					</div>
					
<?php
				}
			}
		}
	}	
	else{header('Location: index.php');}
?>