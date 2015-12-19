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
								<li class='active'><a href='catmang.php'>Manage Categories</a></li>
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
							
							echo "you are a moderator.you do not have access to this page";
				}
				if ($_SESSION['user_level'] == 1)	{
				//Display Category table
				$results = $con->query("SELECT cat_id, cat_name, cat_description
											FROM proj4_categories");
					if (!$results)	{
						//Display error
						echo "The categories cannot be displayed.  Please try again.";
					}
					else	{
						$num_rows = $results->num_rows;
						if ($num_rows==0)	{
							echo "<div class='container'>";
							echo "<h3>No categories have been defined.</h3><br>";
							echo "<div class='col-lg-4'>";
									echo "<h3>Add Category</h3>";
									echo "<table class='table-striped'>
											<th style='width:100px'>Category</th> 
											<th style='width:375px'>Description</th>
											<th>Add</th>";
									if ($_SERVER['REQUEST_METHOD'] != 'POST')	{
										//Form hasn't been posted yet, so display it
										echo "<form method='post' action=''>
												<tr>
													<td>
														<input type='text' class='input-medium' name='cat_name' required='required' />
													</td>
													<td>
														<input type='text' class='input-medium' name='cat_description' required='required' />
													</td>
													<td>
														<button class='btn btn-success'>Add</button>
													</td>
												</tr>
											</table>
										</form>";
									}
									else	{
										//form has been posted, so save it
										$cat_name = $con->real_escape_string($_POST['cat_name']);
										$cat_description = $con->real_escape_string($_POST['cat_description']);
										$results = $con->query("INSERT INTO proj4_categories (cat_name, cat_description)
																VALUES ('$cat_name', '$cat_description')");
										if (!$results)	{
											//Display error
											echo "Error creating category.  Try again later.";
										}
										else	{
											header('Location: catmang.php');
										}
									}
								echo "</div>";
							echo "</div>";
						}
						else	{
						//Prepare the table to display categories
							echo "<div class='row'>";
								echo "<div class='col-lg-6'>";
									echo "<h3>Category Table</h3>";
									echo "<table class='table-bordered'>
											<th style='width:100px'>Category</th> 
											<th style='width:375px'>Description</th>
											<th>Delete</th>";
									while ($row = $results->fetch_assoc())	{
										echo "<tr>";
											echo "<td>";
												echo "<a href='category.php?id=" . $row['cat_id'] . "'>" . $row['cat_name'] . "</a>";
											echo "</td>";
											echo "<td>";
												echo $row['cat_description'];
											echo "</td>";	
											echo "<td style='text-align: center; vertical-align: middle;'>";
												echo "<a href='deletecat.php?id=" . $row['cat_id'] . "'><button class='btn btn-danger'>
															Delete</button></a>";
											echo "</td>";	
										echo "</tr>";
									}
									echo "</table>";
								echo "</div>";
								echo "<div class='col-lg-4'>";
									echo "<h3>Add Category</h3>";
									echo "<table class='table-border'>
											<th style='width:100px'>Category</th> 
											<th style='width:375px'>Description</th>
											<th>Add</th>";
									if ($_SERVER['REQUEST_METHOD'] != 'POST')	{
										//Form hasn't been posted yet, so display it
										echo "<form method='post' action=''>
												<tr>
													<td>
														<input type='text' class='input-medium' name='cat_name' required='required' />
													</td>
													<td>
														<input type='text' class='input-medium' name='cat_description' required='required' />
													</td>
													<td>
														<button class='btn btn-success'>Add</button>
													</td>
												</tr>
											</table>
										</form>";
									}
									else	{
										//form has been posted, so save it
										$cat_name = $con->real_escape_string($_POST['cat_name']);
										$cat_description = $con->real_escape_string($_POST['cat_description']);
										$results = $con->query("INSERT INTO proj4_categories (cat_name, cat_description)
																VALUES ('$cat_name', '$cat_description')");
										if (!$results)	{
											//Display error
											echo "Error creating category.  Try again later.";
										}
										else	{
											header('Location: catmang.php');
										}
									}
								echo "</div>";
							echo "</div>";
						}
					}
			}			
			}
			else	{
				echo "You must be a site admin to view this page.";
			}
		}
		else	{
			echo "You must be logged in as a site admin to view this page.";
		}
?>
<?php
	include 'footer.php';
?>