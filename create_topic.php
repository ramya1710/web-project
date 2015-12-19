<?php
	include 'headers/connect.php';
?>

<?php
			if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
				//The user is signed in
				if ($_SERVER['REQUEST_METHOD'] != 'POST')	{
					//The form hasn't been displayed, so display it
					//Retrieve categories from db for use in dropdown
					$results = $con->query("SELECT * FROM proj4_categories");
					if (!$results)	{
						//Query failed
						echo "Error while retrieving categories.  Please try again.";
					}
					else	{
						$num_rows = $results->num_rows;
						if ($num_rows == 0)	{
							//There are no categories, so a topic cannot be posted
							if ($_SESSION['user_level'] == 1)	{
								echo "You have not created any categories yet.";
							}
							else	{
								echo "Before you can post a topic, an admin must create some categories.";
							}	
						}
						else	{
							echo "<form method='post' action=''>
								Subject: <input type='text' name='topic_subject' /><br>
								Category:";
							echo "<select name='topic_cat'>";
							while ($row = $results->fetch_assoc())	{
								echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
							}
							echo "</select><br>";
							
							echo "Message: <textarea name='post_content'/> </textarea><br>
								<input type='submit' value='Create topic' />
							</form>";
						}
					}	
				}
				else	{
					//Start the transaction
					$con->autocommit(false);
					
					//The form has been posted, save it
					//Insert the topic into the topics table
					$topic_subject = $con->real_escape_string($_POST['topic_subject']);
					$topic_date = date("Y-m-d H:i:s");
					$topic_cat = $con->real_escape_string($_POST['topic_cat']);
					$topic_by = $_SESSION['user_id'];
					$results = $con->query("INSERT INTO proj4_topics (topic_subject, topic_date, topic_cat, topic_by)
											VALUES ('$topic_subject', '$topic_date', '$topic_cat', '$topic_by')");
					if (!$results)	{
						//Something went wrong
						echo "An error occurred while inserting your data.  Try again later.";
						$results = $con->query($con->rollback());
					}
					else	{
						//The first query worked
						//Begin the second query to save the post in the posts table
						
						$post_content = $con->real_escape_string($_POST['post_content']);
						$post_date = date("Y-m-d H:i:s");
						$post_topic = $con->insert_id;	
						$post_by = $_SESSION['user_id'];
						$results = $con->query("INSERT INTO proj4_posts (post_content, post_date, post_topic, post_by)
												VALUES ('$post_content', '$post_date', '$post_topic', '$post_by')");
							
						if (!$results)	{
							//Something went wrong
							echo "An error occured while inserting your post.  Try again later.";
							$results = $con->query($con->rollback());
						}
						else	{
							$results = $con->query($con->commit());
							
							//FINALLY!  The query succeeded.  Time to party!
							echo "You have successfully created <a href='forum.php?id=" . $post_topic . "'>your new topic</a>.";
						}
					}
				}
			}
			else	{
				//The user is not signed in
				echo "You have to be signed in to create a topic.  <a href='signin.php'>Sign in now.</a>";
			}
			?>