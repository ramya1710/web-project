<?php
	include 'headers/header_forum.php';
?>
	
<div class="container">
	<ul class="breadcrumb">
		<li class="active">Forum</li>
	</ul>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<div class="row">
						<div class="col-lg-3">
							<h3 class="panel-title">Category</h3>
						</div>
						<div class="col-lg-7">
							<h3 class="panel-title">Latest Thread</h3>
						</div>
						<div class="col-lg-1">
							<h3 class="panel-title">Threads</h3>
						</div>
						<div class="col-lg-1">
							<h3 class="panel-title">Posts</h3>
						</div>
					</div>
				</div>
				<div class="panel-body">
<?php
	$results1 = $con->query("SELECT cat_id, cat_name
								FROM proj4_categories
								ORDER BY cat_name");
	if (!$results1)	{	//Error retrieving data from database
		echo "The categories cannot be displayed.  Please try again later.";
	}
	else	{
		$num_rows1 = $results1->num_rows;
		if ($num_rows1==0)	{
			echo "No categories have been defined.";
		}
		else	{	//Display categories
			while ($row1 = $results1->fetch_assoc())	{
				$sql2 = "SELECT topic_id, topic_subject, topic_by, topic_date, user_name
							FROM proj4_topics, proj4_users
							WHERE topic_by=user_id AND topic_cat=" . $row1['cat_id'] .
							" ORDER BY topic_date DESC LIMIT 1";
				$results2 = $con->query($sql2);
				$row2 = $results2->fetch_assoc();
				$num_rows2 = $results2->num_rows;
				
				echo "<div class='row'>
						<div class='col-lg-3'>
							<h4><a href='topics.php?id=" . $row1['cat_id'] . "'>" . $row1['cat_name'] . "</a></h4>
						</div>";
				if ($num_rows2==0)	{
					echo "<div class='col-lg-7'>
							There are currently no topics in this category.
						</div>";
				}
				else	{
					echo "<div class='col-lg-7'>
							<a href='replies.php?id=" . $row2['topic_id'] . "'>" . $row2['topic_subject'] . "</a><br>
							by " . $row2['user_name'] . ", on " . $row2['topic_date'] . " <br><br>
						</div>";
				}
				
				$sql3 = "SELECT cat_id, COUNT(topic_id) as num_topics
							FROM proj4_categories LEFT JOIN proj4_topics ON cat_id=topic_cat
							WHERE cat_id=" .  $row1['cat_id'] .
							" GROUP BY topic_cat";
				$results3 = $con->query($sql3);
				$row3 = $results3->fetch_assoc();
				$num_rows3 = $results3->num_rows;
				if ($num_rows3==0)	{
					echo "<div class='col-lg-1'>
							<span class='badge'>0</span>
						</div>";
				}
				else	{
					echo "<div class='col-lg-1'><span class='badge'>"
						. $row3['num_topics'] .	
						"</span></div>";
				}
				
				$sql4 = "SELECT cat_id, COUNT(post_id) as num_posts
							FROM proj4_categories LEFT JOIN 
							(SELECT post_id, post_deleted, topic_cat FROM proj4_posts, proj4_topics WHERE post_deleted = 0 AND topic_id = post_topic) post_cat ON cat_id=topic_cat
							WHERE post_deleted = 0 AND cat_id=" .  $row1['cat_id'] .
							" GROUP BY topic_cat";
				$results4 = $con->query($sql4);
				$row4 = $results4->fetch_assoc();
				$num_rows4 = $results4->num_rows;
				if ($num_rows4==0)	{
					echo "<div class='col-lg-1'>
							<span class='badge'>0</span>
						</div>";
				}
				else	{
					echo "<div class='col-lg-1'><span class='badge'>"
						. $row4['num_posts'] .	
						"</span></div>";
				}
				echo "</div>";
			}
		}
	}
?>
					
				</div>
			</div>
		</div>
	</div>
</div>
				

<?php
include 'footer.php';
?>
