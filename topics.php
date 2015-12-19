<?php
	include 'headers/header_forum.php';
?>

<div class="container">

<?php
	$cat_id = $_GET['id'];
	$sql = "SELECT cat_id, cat_name
				FROM proj4_categories
				WHERE cat_id=" . $cat_id;
	$results = $con->query($sql);
	$row = $results->fetch_assoc();
	
?>

	<ul class='breadcrumb'>
		<li><a href='categories.php'>Forum</a></li>
		<li class='active'><?php echo $row['cat_name']; ?></li>
	</ul>
	
	<div class='row'>
		<div class='col-lg-12'>
			<div class="panel panel-warning">
				<div class="panel-heading">
					<div class="row">
						<div class='col-lg-2'>
							<h3 class="panel-title">Created by</h3>
						</div>
						<div class='col-lg-2'>
							<h3 class="panel-title">Created on</h3>
						</div>
						<div class='col-lg-7'>
							<h3 class="panel-title">Thread</h3>
						</div>
						<div class='col-lg-1'>
							<h3 class="panel-title">Posts</h3>
						</div>
					</div>
				</div>
				<div class='panel-body'>
				
<?php
	$sql1 = "SELECT topic_id, topic_subject, topic_date, topic_by, topic_froze,
						user_id, user_name, user_level, user_dp, user_date, user_email, role_name
				FROM proj4_topics, proj4_users, proj4_roles
				WHERE user_id=topic_by AND user_level=role_id AND topic_cat=" . $cat_id;
	$results1 = $con->query($sql1);
	
	if (!$results1)	{
		echo "The topics cannot be displayed.  Please try again later.";
	}
	else	{
		$num_rows1 = $results1->num_rows;
		if ($num_rows1==0)	{
			echo "No topics have been defined.";
		}
		else	{
			$largeModalNonce = 0;
			while ($row1 = $results1->fetch_assoc())	{
				if ($row1['user_dp'] != '')
					$path = $row1['user_dp'];
				else
					$path = 'images/unknown.jpeg';
				
				$sql2 = "SELECT topic_id, COUNT(post_id) as num_posts
							FROM proj4_topics LEFT JOIN proj4_posts ON topic_id=post_topic
							WHERE topic_id=" .  $row1['topic_id'] .
							" GROUP BY post_topic";
				$results2 = $con->query($sql2);
				$row2 = $results2->fetch_assoc();
				
				/* $sql3 = "SELECT user_id, COUNT(post_id) as num_posts
							FROM proj4_users LEFT JOIN proj4_posts ON user_id=post_by
							WHERE user_id=" .  $row1['user_id'] .
							" GROUP BY post_by";
				$results3 = $con->query($sql3);
				$row3 = $results3->fetch_assoc(); */
				
				$sql3 = "SELECT count(topic_id) num_topic
						 FROM proj4_topics
						 WHERE topic_by = " . $row1['user_id'];
				$results3 = $con->query($sql3);
				$row3 = $results3->fetch_assoc();
				
				$sql4 = "SELECT count(post_id) num_post
						 FROM proj4_posts
						 WHERE post_deleted = 0 AND post_by = " .  $row1['user_id'] . " AND post_deleted = 0";
				$results4 = $con->query($sql4);
				$row4 = $results4->fetch_assoc();

				$user_level = 'newbie';
				if ($row3['num_posts'] >= 10 && $row3['num_posts'] < 20)	{
					$user_level = 'intermediate';
				}
				else if ($row3['num_posts'] >= 20)	{
					$user_level = 'master';
				}
				
				$largeModalName = 'largeModal_' . ++$largeModalNonce;
?>
					<div class='row'>
						<div class='col-lg-2'>
							<a href="#" data-toggle="modal" data-target="#<?php echo $largeModalName; ?>"><?php echo $row1['user_name']; ?></a>
							<?php echo " (" . $row1['role_name'] . ")<br>"; ?>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user_level;?>
						</div>
						<div class='col-lg-2'>
							<?php echo $row1['topic_date']; ?>
						</div>
						<div class='col-lg-7'>
							<a href="replies.php?id=<?php echo $row1['topic_id'];?>"><?php echo $row1['topic_subject']; ?></a>
						</div>
						<div class='col-lg-1'>
							<span class='badge'><?php echo $row2['num_posts'];?></span>
						</div>
					</div>
					
					<div class="modal fade" id="<?php echo $largeModalName; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $largeModalName; ?>" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h2 class="modal-title" id="myModalLabel"><?php echo $row1['user_name']; ?> (<?php echo $row1['role_name'];?>)</h2>
									<h4>&nbsp;&nbsp;&nbsp;<?php echo $user_level; ?></h4>
								</div>
								<div class="modal-body">
									<div class='row'>
										<div class='col-lg-3'>
											<a href="<?php echo $path?>" onclick="window.open (this.href, 'large-view'); return false">
												<img src="<?php echo $path?>" alt="Image cannot be displayed." style="max-width: 100%" class='img-rounded'/>
											</a>
										</div>
										<div class='col-lg-9'>
											<div class='row'>
												<div class='col-lg-3'>
													<h5><b>Email:</b></h5>
												</div>
												<div class='col-lg-3'>
													<h5><?php echo $row1['user_email']; ?></h5>
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
													<h5><?php echo $row3['num_topic']; ?></h5>
												</div>
											</div>
											<div class='row'>
												<div class='col-lg-3'>
													<h5><b>Number of Posts:</b></h5>
												</div>
												<div class='col-lg-9'>
													<h5><?php echo $row4['num_post']; ?></h5>
												</div>
											</div>
										</div>
									</div>
									<br>		
									<div class='row'>
										<div class='col-lg-12'>
											<h4>Posts:</h4>
<?php
				$sql5 = "SELECT *
						 FROM proj4_posts
						 WHERE post_by = " . $row1['user_id'] . " AND post_deleted = 0
						 ORDER BY post_date DESC";
				$results5 = $con->query($sql5);
				while ($row5 = $results5->fetch_assoc())	{
					$sql6 = "SELECT topic_id, topic_subject, topic_cat
							 FROM proj4_topics
							 WHERE topic_id = " . $row5['post_topic'];
					$results6 = $con->query($sql6);
					$row6 = $results6->fetch_assoc();
					$sql7 = "SELECT cat_id, cat_name
							 FROM proj4_categories
							 WHERE cat_id = " . $row6['topic_cat'];
					$results7 = $con->query($sql7);
					$row7 = $results7->fetch_assoc();
?>
											<div class='panel panel-default'>
												<div class='panel-body'>
													<div class='row'>
														<div class='col-lg-11'>
															<a href='topics.php?id=<?php echo $row7['cat_id']; ?>'>
																<p><small><?php echo $row7['cat_name']; ?>
															</a>
															/
															<a href='replies.php?id=<?php echo $row6['topic_id']; ?>'>
																<?php echo $row6['topic_subject']; ?></small></p>
															</a>
														</div>
<?php
					if ($_SESSION[user_id] == $row1['user_id'])	{
?>
														<div class='col-lg-1'>
															<a href='editreply.php?id=<?php echo $row5['post_id']; ?>'><small>Edit</small></a>
														</div>
<?php
					}
?>
													</div>
													<div class='row'>
														<div class='col-lg-12'>
															<p><?php echo $row5['post_content']; ?></p>
														</div>
													</div>
													<div class='row'>
														<div class='col-lg-3'>
															<p class='text-muted'><small><?php echo $row5['post_date']; ?></small></p>
														</div>

<?php
					if ($row5['post_edited'] == 1)	{
?>

														<div class='col-lg-5'>
															<p class='text-muted'><small>Edited on: <?php echo $row5['post_edit']; ?></small></p>
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
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
<?php
			}
		}
?>



				</div>
			</div>
		</div>
	</div>

<?php
		if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
			$sql3 = "SELECT user_id, user_suspend
						FROM proj4_users
						WHERE user_id=" . $_SESSION['user_id'];
			$results3 = $con->query($sql3);
			$row3 = $results3->fetch_assoc();
			if ($row3['user_suspend'] == 0)	{	//User is not suspended
				if ($_SERVER['REQUEST_METHOD'] == 'POST')	{	//Form has been posted so save the results
					$topic_subject = $_POST['topic_subject'];
					$topic_date = date("Y-m-d H:i:s");
					$topic_cat = $con->real_escape_string($_GET['id']);
					$topic_by = $_SESSION['user_id'];
					
					$sql5 = "SELECT cat_id
								FROM proj4_categories
								WHERE cat_id=" . $topic_cat;
					$results5 = $con->query($sql5);
					$num_rows5 = $results5->num_rows;
					
					$sql6 = "SELECT user_id, user_suspend
								FROM proj4_users
								WHERE user_id=" . $topic_by;
					$results6 = $con->query($sql6);
					$row6 = $results6->fetch_assoc();
					
					if ($num_rows5 == 0)	{
						echo "Sorry, this category is no longer available. You are being redirected to the main category page.";
						header("Refresh:3; URL=categories.php");
					}
					else if ($row6['user_suspend'] == 1)	{
						echo "You are currently suspended and are unable to create threads.";
					}
					else	{
						$sql4 = "INSERT INTO proj4_topics (topic_subject, topic_date, topic_cat, topic_by)
									VALUES ('$topic_subject', '$topic_date', $topic_cat, $topic_by)";
					
						$results4 = $con->query($sql4);
						if (!$results4)	{
							echo "Your thread has not been created.  Please try again later.";
						}
						else	{
							echo "Your thread was created successfully.  You will be redirected to the category you were viewing shortly";
							header("Refresh:2;");
						}
					}
				}
				else	{	//Create form
?>
	
	
	<div class='row'>
		<div class='col-lg-12'>
			<form method='post' action='' class='form-horizontal'>
				<fieldset>
					<div class='row'>
						<div class='col-lg-12'>
							<textarea class='form-control' rows='3' name='topic_subject' placeholder='Begin a new thread...'></textarea>
						</div>
					</div>
					<div class='row'>
						<div class='col-lg-2 col-lg-offset-10'>
							<button type='submit' class='btn btn-default'>Create thread</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

<?php
				}
			}
			else	{
				echo "You are currently suspended and unable to create threads.";
			}
		}
	}
?>
				
				
</div>
</div>

<?php
	include 'footer.php';
?>