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
							<li class='active'><a href='topicmang.php'>Manage Threads</a></li>
							<li><a href='postmang.php'>Manage Posts</a></li>
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
							<li class='active'><a href='topicmang.php'>Manage Threads</a></li>
							<li><a href='postmang.php'>Manage Posts</a></li>
						</ul>";
			}
			$sql = "SELECT topic_id, topic_subject, topic_date, topic_froze, user_id, user_name, cat_id, cat_name
						FROM proj4_topics, proj4_categories, proj4_users
						WHERE topic_cat=cat_id AND topic_by=user_id
						ORDER BY cat_name";
			$results = $con->query($sql);
			if (!$results)	{
				echo "Threads cannot be displayed.  Please try again later.";
			}
			else	{
				$num_rows = $results->num_rows;
				if ($num_rows==0)	{
					echo "No threads have been created.";
				}
				else	{
?>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<div class="row">
						<div class="col-lg-2">
							<h3 class="panel-title">Category</h3>
						</div>
						<div class="col-lg-2">
							<h3 class="panel-title">Created By</h3>
						</div>
						<div class="col-lg-4">
							<h3 class="panel-title">Thread</h3>
						</div>
						<div class="col-lg-2">
							<h3 class="panel-title">Last Post Date</h3>
						</div>
						<div class="col-lg-1">
							<h3 class="panel-title">Posts</h3>
						</div>
						<div class="col-lg-1">
							<h3 class="panel-title">Freeze</h3>
						</div>
					</div>
				</div>
				<div class="panel-body">
<?php
					while ($row = $results->fetch_assoc())	{
						$sql1 = "SELECT topic_id, COUNT(post_id) as num_posts
									FROM proj4_topics LEFT JOIN proj4_posts ON topic_id=post_topic
									WHERE topic_id=" .  $row['topic_id'] .
									" GROUP BY post_topic";
						$results1 = $con->query($sql1);
						$row1 = $results1->fetch_assoc();
						
						$sql2 = "SELECT post_id, post_date
									FROM proj4_posts
									WHERE post_topic=" . $row1['topic_id'] .
									" ORDER BY post_date DESC LIMIT 1";
						$results2 = $con->query($sql2);
						$row2 = $results2->fetch_assoc();
?>
					<div class='row'>
						<div class='col-lg-2'>
							<?php echo $row['cat_name']; ?>
						</div>
						<div class='col-lg-2'>
							<?php echo $row['user_name']; ?>
						</div>
						<div class='col-lg-4'>
							<?php echo $row['topic_subject']; ?>
						</div>
						<div class="col-lg-2">
							<?php echo $row2['post_date']; ?>
						</div>
						<div class='col-lg-1'>
							<span class='badge'><?php echo $row1['num_posts'];?></span>
						</div>
						<div class='col-lg-1'>
<?php
						if ($row['topic_froze'] == 0)	{
							echo "<a href='freezethread.php?id=" . $row['topic_id'] . "'>Freeze</a>";
						}
						else	{
							echo "Frozen";
						}
?>
						</div>
					</div>
<?php
					}
?>			
				</div>
			</div>
		</div>
	</div>

<?php
				}
			}
			
		}
		else
{
header('Location: index.php');
}
	}
	else{header('Location: index.php');}
?>