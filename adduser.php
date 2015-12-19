<?php
	include 'headers/header_admin.php';
?>

<?php
		if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
			if ($_SESSION['user_level'] == 1)	{
				echo 
					"<div class='container'>
						<h1>Welcome " . $_SESSION['user_name'] . "!</h1>
						<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;What would you like to do? </h3>
						<ul class='nav nav-tabs'>
							<li class='active'><a href='usermang.php'>Manage Users</a></li>
							<li><a href='catmang.php'>Manage Categories</a></li>
							<li><a href='pgmang.php'>Manage Pagination</a></li>
						</ul>";
					
				//Display User table
				$results = $con->query("SELECT user_id, user_name, user_email, user_date, user_level, role_name, user_suspend
											FROM proj3_users, proj3_roles
											WHERE user_level=role_id
											ORDER BY user_date");
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
						//Prepare the table to display categories
							echo "<div class='row'>";
								echo "<div class='col-lg-12'>";
									echo "<h3>Add User</h3>";
									if ($_SERVER['REQUEST_METHOD'] != 'POST')	{
										//Form hasn't been posted yet, so display it
										echo '
											<div class="row">
												<div class="col-lg-6">
													<div class="well bs-component">
														<form method="post" action="" class="form-horizontal">
															<fieldset>
																<div class="form-group">
																	<label for="user_name" class="col-lg-2 control-label">Username</label>
																	<div class="col-lg-10">
																		<input type="text" class="form-control" name="user_name" placeholder="Username">
																	</div>
																</div>
																<div class="form-group">
																	<label for="user_pass" class="col-lg-2 control-label">Password</label>
																	<div class="col-lg-10">
																		<input type="password" class="form-control" name="user_pass" placeholder="Password">
																	</div>
																</div>
																<div class="form-group">
																	<label for="user_email" class="col-lg-2 control-label">Email</label>
																	<div class="col-lg-10">
																		<input type="text" class="form-control" name="user_email" placeholder="Email">
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-lg-2 control-label">Level</label>
																	<div class="col-lg-10">
																	<div class="radio">
																		<label>
																			<input type="radio" name="role_id" id="admin" value="1">
																			Admin
																		</label>
																	</div>
																	<div class="radio">
																		<label>
																			<input type="radio" name="role_id" id="moderator" value="2">
																			Moderator
																		</label>
																	</div>
																	<div class="radio">
																		<label>
																			<input type="radio" name="role_id" id="user" value="0" checked="">
																			User
																		</label>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<div class="col-lg-10 col-lg-offset-2">
																	<button class="btn btn-default">Cancel</button>
																	<button type="submit" class="btn btn-primary">Submit</button>
																</div>
															</div>
														</fieldset>
													</form>
												</div>
											</div>
										</div>';
									}
									else	{
										//form has been posted, so save it
										$user_name = $con->real_escape_string($_POST['user_name']);
										$user_password = $con->real_escape_string($_POST['user_pass']);
										$user_email = $con->real_escape_string($_POST['user_email']);
										$user_date = date("Y-m-d H:i:s");
										$role_id = $con->real_escape_string($_POST['role_id']);
										$user_level;
										$results1 = $con->query("SELECT role_id
																   FROM proj3_roles
																   WHERE role_id = '$role_id'");
										while ($row = $results1->fetch_assoc())	{
											$user_level = $row['role_id'];
										}
										$results = $con->query("INSERT INTO proj3_users (user_name, user_pass, user_email, user_date, user_level)
																VALUES ('$user_name', '$user_password', '$user_email', '$user_date', '$user_level')");
										if (!$results)	{
											//Display error
											echo "Error adding new user.  Try again later.";
										}
										else	{
											echo "Successfully created new user!<br>";
										}
									}
									echo "<br><br><a href='usermang.php'><button class='btn btn-info'>
															Return to User Table</button></a>";
								echo "</div>";
							echo "</div>";
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
<?php
	include 'footer.php';
?>