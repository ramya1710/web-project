<?php
	include 'headers/header_home.php';
?>
		
	
	<div id="page" class="container">
		<div class="row">
			<div class="col-lg-8">
			</div>
			<div class="col-lg-4">
			<?php
				if ($_SESSION['signed_in'])	{
					echo "<h1>Welcome " . $_SESSION['user_name'] . "!</h1>";
				}
				else	{
					echo "<h1>Welcome Guest!</h1>";
				}
			?>
		</div>
		</div>
		<div style="float: left;">
			<div>
				<h2>Most Recent Topics</h2>
			</div>
			<div>
				<?php
					$results = $con->query("SELECT cat_id, cat_name, cat_description, topic_id, topic_subject, topic_cat, topic_date
											FROM proj4_categories LEFT JOIN proj4_topics ON cat_id=topic_cat ORDER BY topic_date DESC LIMIT 3");
					if (!$results)	{
						//Display error
						echo "The categories cannot be displayed.  Please try again.";
					}
					else	{
						$num_rows = $results->num_rows;
						if ($num_rows==0)	{
							echo "No categories have been defined.";
						}
						else	{
						//Prepare the table to display categories
							echo "<table class='table-striped'>
									<th style='width:200px'>Category</th> 
									<th style='width:200px'>Topic</th> 
									<th>Created On</th>";
							while ($row = $results->fetch_assoc())	{
								echo "<tr>";
									echo "<td>";
										echo "<h3><a href='topics.php?id=" . $row['cat_id'] . "'>" . $row['cat_name'] . "</a></h3>";
									echo "</td>";
									echo "<td>";
										if ($row['topic_id'] != 0)	{
											echo "<a href='replies.php?id=" . $row['topic_id'] . "'>" . $row['topic_subject'] . "</a>";
										}
										else	{
											echo "No topic.";
										}
									echo "</td>";
									echo "<td>";
										if ($row['topic_id'] != 0)	{
											echo $row['topic_date'];
										}
										else	{
											
										}
									echo "</td>";
								echo "</tr>";
							}
							echo "</table>";
						}
					}
				?>
			</div>
		</div>

		<div style="float: right;">			
				<h2>Season Finale!</h2>
			<img src="images/new_eps.jpg" width="298" height="198" alt="" />
			<p>How Your Mother Met Me</p>
			<a href="http://www.cbs.com/shows/how_i_met_your_mother/video/DAD52B59-B5BB-EFBE-1766-CB7D9D8BCD2F/how-i-met-your-mother-how-your-mother-met-me/" 
			class="button">Click for episode</a>
		</div>
	</div>
<?php
	include 'footer.php'
?>	
