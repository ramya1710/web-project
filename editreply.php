<?php
	include 'headers/header_forum.php';
?>

<div class="container">
<?php
	$post_id = $_GET['id'];
	if (!is_numeric($post_id))
	{
	header('Location: index.php');
	}
	else
	{
	$result = $con->query("SELECT * FROM proj4_posts WHERE post_id='$post_id'");
		if (mysqli_num_rows($result)== 1)
		{
	$frozen = 0;
	$sql = "SELECT cat_id, cat_name, topic_id, topic_subject, topic_by, topic_date, topic_froze, user_name, user_level, role_name
				FROM proj4_categories, proj4_topics, proj4_users, proj4_roles, proj4_posts
				WHERE topic_cat=cat_id AND topic_by=user_id AND user_level=role_id AND topic_id=post_topic AND post_id=$post_id"; 
	$results = $con->query($sql);
	$row = $results->fetch_assoc();
	
	$frozen = $row['topic_froze'];
?>
	<ul class='breadcrumb'>
		<li><a href='categories.php'>Categories</a></li>
		<li><a href='topics.php?id=<?php echo $row['cat_id']; ?>'><?php echo $row['cat_name']; ?> </a></li>
		<li class='active'> <?php echo $row['topic_subject'] ?> </li>
	</ul>

<?php
	$sql1 = "SELECT post_id, post_content, post_topic, post_by, post_date, post_edit, post_editby, post_edited, post_deleted, user_id, user_name, user_level, role_name
				FROM proj4_posts, proj4_users, proj4_roles
				WHERE post_by=user_id AND user_level=role_id AND post_id=" . $post_id;
	$results1 = $con->query($sql1);
	while ($row1 = $results1->fetch_assoc())	{
		$post_topic = $row1['post_topic'];
		$sql2 = "SELECT user_id, COUNT(post_id) as num_posts
							FROM proj4_users LEFT JOIN proj4_posts ON user_id=post_by
							WHERE user_id=" .  $row1['user_id'] .
							" GROUP BY post_by";
		$results2 = $con->query($sql2);
		$row2 = $results2->fetch_assoc();
		
		$sql5 = "SELECT user_id, user_name
					FROM proj4_users
					WHERE user_id=" . $row1['post_editby'];
		$results5 = $con->query($sql5);
		$row5 = $results5->fetch_assoc();
		
		$user_level = 'newbie';
		if ($row2['num_posts'] >= 10 && $row2['num_posts'] < 20)	{
			$user_level = 'intermediate';
		}
		else if ($row2['num_posts'] >= 20)	{
			$user_level = 'master';
		}
?>

	<div class='row'>
		<div class='col-lg-12'>
			<div class='panel panel-warning'>
				<div class='panel-heading'>
					<div class='row'>
						<div class='col-lg-3'>
							<h4><?php echo $row1['user_name'] . " (" . $row1['role_name'] . ")"; ?></h4>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user_level;?>
						</div>
						<div class='col-lg-3'>
							<h4>Created on:</h4>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row1['post_date'];?>
						</div>
<?php
		if ($row1['post_edited'] == 1)	{
?>
						<div class='col-lg-4'>
							<h4>Edited on:</h4>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row5['user_name'];?> on <?php echo $row1['post_edit'];?>
						</div>
<?php
		}
		else	{
			echo "<div class='col-lg-4'></div>";
		}
?>
					</div>
				</div>
				<div class='panel-body'>
					<div class='row'>
						<div class='col-lg-12'>
							<form method='post' action='' class='form-horizontal'>
								<fieldset>
									<div class='row'>
										<div class='col-lg-12'>
											<textarea class='form-control' rows='7' name='post_content'><?php echo $row1['post_content']; ?></textarea>
										</div>
									</div>
									<div class='row'>
										<div class='col-lg-1 col-lg-offset-11'>
											<button type='submit' class='btn btn-default'>Edit</button>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	}
?>

<?php
	if ($frozen == 0)	{	//Display reply option
		if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
			$sql3 = "SELECT user_id, user_suspend
						FROM proj4_users
						WHERE user_id=" . $_SESSION['user_id'];
			$results3 = $con->query($sql3);
			$row3 = $results3->fetch_assoc();
			if ($row3['user_suspend'] == 0)	{	//User is not suspended
				if ($_SERVER['REQUEST_METHOD'] == 'POST')	{	//Form has been posted so save the results
					$post_id = $con->real_escape_string($_GET['id']);
					$post_content = $con->real_escape_string($_POST['post_content']);
					$post_edit = date("Y-m-d H:i:s");
					$post_editby = $_SESSION['user_id'];
					$post_edited = 1;
					
					$sql4 = "UPDATE proj4_posts
								SET post_content='$post_content', post_edit='$post_edit', post_editby='$post_editby', post_edited=$post_edited
								WHERE post_id=$post_id";
					$results4 = $con->query($sql4);
					
					if (!$results4)	{
						echo "Your edited message has not been saved.  Please try again later.<br>";
						echo $sql4;
					}
					else	{
						echo "Your message has been edited successfully.  You will be redirected to the thread you were viewing shortly.";
						
						if ($_SESSION['currentpage'] == 'replies.php')	{
							$loc = "replies.php?id=" . $post_topic;
						}
						else	{
							$loc = $_SESSION['currentpage'];
						}

						header("Refresh:3; URL=$loc");
					}
				}
				else	{	//Create form
?>

</div>

<?php
				}
			}
			else	{	//User is suspended and cannot post
				echo "You are currently suspended and are unable to post a message.";
			}
		}
		else	{	//User must sign in before posting
			echo "You must be signed in to post a message.  Click here to <a href='signin.php'>sign in</a> now.";
		}
	}
	else	{	//Thread is frozen
		echo "This thread is frozen.";
	}
	}
	else{
	header('Location: index.php');
	}
	}
	
?>

</div>
	
<?php
	include 'footer.php';
?>