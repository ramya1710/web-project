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
								<li class='active'><a href='usermang.php'>Manage Users</a></li>
								<li><a href='catmang.php'>Manage Categories</a></li>
								<li><a href='topicmang.php'>Manage Threads</a></li>
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
								<li class='active'><a href='usermang.php'>Manage Users</a></li>
								<li><a href='topicmang.php'>Manage Threads</a></li>
								<li><a href='postmang.php'>Manage Posts</a></li>
							</ul>";
				}
					
				//Display User table
				$results = $con->query("SELECT user_id, user_name, user_email, user_date, user_level, role_name, user_suspend
											FROM proj4_users, proj4_roles
											WHERE user_level=role_id AND user_delete = 0
											ORDER BY user_name");
					if (!$results)	{
						//Display error
						echo "Users cannot be displayed.  Please try again later.";
					}
					else	{
						$num_rows = $results->num_rows;
						if ($num_rows==0)	{
							echo "No users have been defined.";
						}
						else	{
?>

<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<div class="row">
						<div class="col-lg-2">
							<h3 class="panel-title">Username</h3>
						</div>
						<div class="col-lg-2">
							<h3 class="panel-title">Email</h3>
						</div>
						<div class="col-lg-1">
							<h3 class="panel-title">Role</h3>
						</div>
						<div class="col-lg-1">
							<h3 class="panel-title">Suspended</h3>
						</div>
						<div class="col-lg-2">
							<h3 class="panel-title">Date Registered</h3>
						</div>
						<div class="col-lg-1">
							<h3 class="panel-title">Threads</h3>
						</div>
						<div class="col-lg-1">
							<h3 class="panel-title">Posts</h3>
						</div>
						<div class="col-lg-2">
							<h3 class="panel-title">Last Post Date</h3>
						</div>
					</div>
				</div>
				<div class="panel-body">
<?php
							$results1 = $con->query("SELECT user_id, user_name, user_email, user_date, user_level, user_suspend, role_name
														FROM proj4_users, proj4_roles
														WHERE user_level=role_id AND user_delete = 0
														ORDER BY user_name");
							if (!$results1)	{	//Error retrieving data from database
								echo "The users cannot be displayed.  Please try again later.";
							}
							else	{	//Display users
								while ($row1 = $results1->fetch_assoc())	{
									$sql2 = "SELECT user_id, COUNT(topic_id) as num_topics
												FROM proj4_users LEFT JOIN proj4_topics ON user_id=topic_by
												WHERE user_id=" .  $row1['user_id'] .
												" AND user_delete = 0
												GROUP BY topic_by";
									$results2 = $con->query($sql2);
									$row2 = $results2->fetch_assoc();
									$num_rows2 = $results2->num_rows;
									
									$sql3 = "SELECT user_id, COUNT(post_id) as num_posts
												FROM proj4_users LEFT JOIN proj4_posts ON user_id=post_by
												WHERE user_id=" .  $row1['user_id'] .
												" AND user_delete = 0
												GROUP BY post_by";
									$results3 = $con->query($sql3);
									$row3 = $results3->fetch_assoc();
									$num_rows3 = $results3->num_rows;
									
									$sql4 = "SELECT post_id, post_by, post_date
												FROM proj4_posts
												WHERE post_by=" . $row1['user_id'] .
												" ORDER BY post_date DESC LIMIT 1";
									$results4 = $con->query($sql4);
									$row4 = $results4->fetch_assoc();
									$num_rows4 = $results4->num_rows;
									
									echo "<div class='row'>
											<div class='col-lg-2'>
												<h4><a href='edituser.php?id=" . $row1['user_id'] . "'>" . $row1['user_name'] . "</a></h4>
											</div>
											<div class='col-lg-2'>"
												. $row1['user_email'] . 
											"</div>
											<div class='col-lg-1'>"
												. $row1['role_name'] . 
											"</div>";
									if ($row1['user_suspend'] == 0)	{
										echo "<div class='col-lg-1'>No</div>";
									}
									else	{
										echo "<div class='col-lg-1'>Yes</div>";
									}
									echo "<div class='col-lg-2'>"
												. $row1['user_date'] . 
											"</div>
											<div class='col-lg-1'><span class='badge'>"
												. $row2['num_topics'] . 
											"</span></div>
											<div class='col-lg-1'><span class='badge'>"
												. $row3['num_posts'] . 
											"</span></div>
											<div class='col-lg-2'>"
												. $row4['post_date'] . 
											"</div>
										</div>";
								}
							}
?>

<?php					
						}
					}
				echo "</div>";
			}
			else	{
				echo "You must be a site admin to view this page.";
			}
		}
		else	{
			echo "You must be logged in as a site admin to view this page.";
		}
	?>
</div>
<?php
	include 'footer.php';
?>