<?php
	include 'headers/header_control.php';
?>

<div class='container'>

<?php
	if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
		if ($_SESSION['user_level'] == 1)	{	//User is an admin
			$sql = "SELECT user_id, user_name, user_email, user_date, user_suspend, role_id, role_name
						FROM proj4_users, proj4_roles
						WHERE user_level=role_id 
						ORDER BY user_date";
			$results = $con->query($sql);
			
			if (!$results)	{	//Error retrieving user information
				echo "User information cannot be displayed.  Please try again later.";
			}
			else	{	//Prepare the edit user form
				while ($row = $results->fetch_assoc())	{
					if ($row['user_id'] == $_GET['id'])	{
						$user_id = $row['user_id'];
						$user_name = $row['user_name'];
						$user_email = $row['user_email'];
						$user_date = $row['user_date'];
						$role_id = $row['role_id'];
						$user_suspend = $row['user_suspend'];
?>

	<div class='row'>
		<h1>Welcome <?php echo $_SESSION['user_name']; ?>!</h1>
		<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;What would you like to do?</h3>
		<ul class='nav nav-tabs'>
			<li class='active'><a href='usermang.php'>Manage Users</a></li>
			<li><a href='catmang.php'>Manage Categories</a></li>
			<li><a href='pgmang.php'>Manage Pagination</a></li>
		</ul>
	</div>
	<div class='row'>
		<div class='col-lg-3'>
		</div>
		<div class='col-lg-6'>
			<div class='panel panel-warning'>
				<div class='panel-heading'>
					<div class='row'>
						<h3 class='panel-title'>Edit User: <?php echo $user_name; ?></h3>
					</div>
				</div>
				<div class='panel-body'>
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST')	{	//Form has been posted so save the results
		
		$user_id1 = $_GET['id'];
		$user_name1 = $con->real_escape_string($_POST['user_name']);
		$user_email1 = $con->real_escape_string($_POST['user_email']);
		$role_id1 = $con->real_escape_string($_POST['role_id']);
		$user_suspend1 = $con->real_escape_string($_POST['user_suspend']);
		$user_delete1 = $con->real_escape_string($_POST['user_delete']);
		
		
		if ($user_delete1 != 1)	{	//Update user
			$sql1 = "UPDATE proj4_users
						SET user_name='$user_name1', user_email='$user_email1', user_level='$role_id1', user_suspend='$user_suspend1'
						WHERE user_id='$user_id1'";
			$results1 = $con->query($sql1);
			if (!$results1)	{
				echo "Error updating user.  Please try again later.";
			}
			else	{
				if ($user_suspend1 != $user_suspend)	{	//Change has been made to suspension status
					if ($user_suspend1 == 0)	{	//User has been unsuspended
						$to = $user_email1;
						$subject = "HIMYM Forum--Account Suspension";
						$headers = "From: himymforum@cs.odu.edu\r\n";
						$message = "Hello " . $user_name1 . ",\n\nYour account has been unsuspended.\n";
						mail($to, $subject, $message, $headers);
					}
					else	{	//User has been suspended
						$to = $user_email1;
						$subject = "HIMYM Forum--Account Suspension";
						$headers = "From: himymforum@cs.odu.edu\r\n";
						$message = "Hello " . $user_name1 . ",\n\nYour account has been temporarily suspended.\n";
						mail($to, $subject, $message, $headers);
					}
				}
				header('Location: usermang.php');
			}
		}
		else	{	// Delete user
			/* $sql2 = "DELETE FROM proj4_users WHERE user_id = '$user_id1'";
			$results2 = $con->query($sql2);
			if (!$results2)	{
				echo "Error deleting user.  Please try again later.";
			}
			else	{
				$to = $user_email1;
				$subject = "HIMYM Forum--Account Deletion";
				$headers = "From: himymforum@cs.odu.edu\r\n";
				$message = "Hello " . $user_name1 . ",\n\nYour account has been deleted.\n";
				mail($to, $subject, $message, $headers);
				header('Location: usermang.php');
			} */
			$loc = "deleteuser.php?id=" . $user_id1;
			header("Location: $loc");
		}
	}
	else	{	//Form hasn't been posted, so post it
?>
					<div class='row'>
						<form method='post' action='' class='form-horizontal'>
							<fieldset>
								<div class='form-group'>
									<label for='user_name' class='col-lg-2 control-label'>Username</label>
									<div class='col-lg-9'>
										<input type='text' class='form-control' name='user_name' value='<?php echo $user_name; ?>'>
									</div>
								</div>
								<div class='form-group'>
									<label for='user_email' class='col-lg-2 control-label'>Email</label>
									<div class='col-lg-9'>
										<input type='text' class='form-control' name='user_email' value='<?php echo $user_email; ?>'>
									</div>
								</div>
<?php
		if ($role_id == 1)	{	//Editing an admin
			if ($user_id == $_SESSION['user_id'])	{	//Current user, do not allow change of user role
?>
								<div class='form-group'>
									<label class='col-lg-2 control-label'>Level</label>
									<span class='col-lg-9'>
										<h5>You cannot edit your own user role</h5>
									</span>
								</div>
								
<?php
			}
			else	{	//User is editing another admin
?>

								<div class='form-group'>
									<label class='col-lg-2 control-label'>Level</label>
									<div class='col-lg-9'>
										<div class='radio'>
											<label>
												<input type='radio' name='role_id' id='admin' value='1' checked=''>
												Admin
											</label>
										</div>
										<div class='radio'>
											<label>
												<input type='radio' name='role_id' id='moderator' value='2'>
												Moderator
											</label>
										</div>
										<div class='radio'>
											<label>
												<input type='radio' name='role_id' id='user' value='0'>
												User
											</label>
										</div>
									</div>
								</div>

<?php			
			}
		}
		else if ($role_id == 2)	{
?>
								
								<div class='form-group'>
									<label class='col-lg-2 control-label'>Level</label>
									<div class='col-lg-9'>
										<div class='radio'>
											<label>
												<input type='radio' name='role_id' id='admin' value='1'>
												Admin
											</label>
										</div>
										<div class='radio'>
											<label>
												<input type='radio' name='role_id' id='moderator' value='2' checked=''>
												Moderator
											</label>
										</div>
										<div class='radio'>
											<label>
												<input type='radio' name='role_id' id='user' value='0'>
												User
											</label>
										</div>
									</div>
								</div>
								
<?php
		}
		else	{
?>
								
								<div class='form-group'>
									<label class='col-lg-2 control-label'>Level</label>
									<div class='col-lg-9'>
										<div class='radio'>
											<label>
												<input type='radio' name='role_id' id='admin' value='1'>
												Admin
											</label>
										</div>
										<div class='radio'>
											<label>
												<input type='radio' name='role_id' id='moderator' value='2'>
												Moderator
											</label>
										</div>
										<div class='radio'>
											<label>
												<input type='radio' name='role_id' id='user' value='0' checked=''>
												User
											</label>
										</div>
									</div>
								</div>	
								
<?php
		}
		if ($user_id == $_SESSION['user_id'])	{	//Do not allow user to suspend
?>		
								<div class='form-group'>
									<label class='col-lg-2 control-label'>Suspend</label>
									<span class='col-lg-9'>
										<h5>You cannot suspend yourself</h5>
									</span>
								</div>
<?php
		}
		else	{
			if ($user_suspend == 0)	{	//User is not currently suspended
?>
								<div class='form-group'>
									<label class='col-lg-2 control-label'>Suspend</label>
									<div class='col-lg-9'>
										<div class='radio'>
											<label>
												<input type='radio' name='user_suspend' id='suspend' value='1'>
												Suspended
											</label>
										</div>
										<div class='radio'>
											<label>
												<input type='radio' name='user_suspend' id='unsuspend' value='0' checked=''>
												Unsuspended
											</label>
										</div>
									</div>
								</div>
<?php
			}
			else	{	//User is already suspended
?>
								
								<div class='form-group'>
									<label class='col-lg-2 control-label'>Suspend</label>
									<div class='col-lg-9'>
										<div class='radio'>
											<label>
												<input type='radio' name='user_suspend' id='suspend' value='1' checked=''>
												Suspended
											</label>
										</div>
										<div class='radio'>
											<label>
												<input type='radio' name='user_suspend' id='unsuspend' value='0'>
												Unsuspended
											</label>
										</div>
									</div>
								</div>
								
<?php
			}
		}
		if ($user_id == $_SESSION['user_id'])	{	//Do not allow user to delete
?>

								<div class='form-group'>
									<label class='col-lg-2 control-label'>Delete</label>
									<span class='col-lg-9'>
										<h5>You cannot delete yourself</h5>
									</span>
								</div>

<?php
		}
		else	{
?>
								<div class='form-group'>
									<label class='col-lg-2 control-label'>Delete</label>
									<div class='col-lg-9'>
										<div class='checkbox'>
											<label>
												<input type='checkbox' name='user_delete' id='delete' value='1'>
												Delete User
											</label>
										</div>
									</div>
								</div>
<?php
		}
?>
								
								<div class='form-group'>
									<div class='col-lg-10 col-lg-offset-8'>
										<a href='usermang.php' class='text-muted'>Cancel</a>
										<button type='submit' class='btn btn-primary'>Submit</button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
<?php
	}
?>
				</div>
			</div>
		</div>
		<div class='col-lg-3'>
		</div>
	</div>
	

<?php
					}
				}
			}
		}
		else if ($_SESSION['user_level'] == 2)	{	//User is a moderator
			$sql = "SELECT user_id, user_name, user_email, user_date, user_suspend, role_id, role_name
						FROM proj4_users, proj4_roles
						WHERE user_level=role_id
						ORDER BY user_date";
			$results = $con->query($sql);
			
			if (!$results)	{	//Error retrieving user information
				echo "User information cannot be displayed.  Please try again later.";
			}
			else	{	//Prepare the edit user form
				while ($row = $results->fetch_assoc())	{
					if ($row['user_id'] == $_GET['id'])	{
						$user_id = $row['user_id'];
						$user_name = $row['user_name'];
						$user_email = $row['user_email'];
						$user_date = $row['user_date'];
						$role_id = $row['role_id'];
						$user_suspend = $row['user_suspend'];
?>

	<div class='row'>
		<h1>Welcome <?php echo $_SESSION['user_name']; ?>!</h1>
		<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;What would you like to do?</h3>
		<ul class='nav nav-tabs'>
			<li class='active'><a href='usermang.php'>Manage Users</a></li>
		</ul>
	</div>
	<div class='row'>
		<div class='col-lg-3'>
		</div>
		<div class='col-lg-6'>
			<div class='panel panel-warning'>
				<div class='panel-heading'>
					<div class='row'>
						<h3 class='panel-title'>Edit User: <?php echo $user_name; ?></h3>
					</div>
				</div>
				<div class='panel-body'>
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST')	{	//Form has been posted so save the results
		$user_id = $_GET['id'];
		$user_name = $con->real_escape_string($_POST['user_name']);
		$user_email = $con->real_escape_string($_POST['user_email']);
		$role_id = $con->real_escape_string($_POST['role_id']);
		$user_suspend = $con->real_escape_string($_POST['user_suspend']);
		$user_delete = $con->real_escape_string($_POST['user_delete']);
		
		if ($user_delete != 1)	{	//Update user
			$sql1 = "UPDATE proj4_users
						SET user_name='$user_name', user_email='$user_email', user_level='$role_id', user_suspend='$user_suspend'
						WHERE user_id='$user_id'";
			$results1 = $con->query($sql1);
			if (!$results1)	{
				echo "Error updating user.  Please try again later.";
			}
			else	{
				header('Location: usermang.php');
			}
		}
		else	{	// Delete user
			$sql2 = "DELETE FROM proj4_users WHERE user_id = '$user_id'";
			$results2 = $con->query($sql2);
			if (!$results2)	{
				echo "Error deleting user.  Please try again later.";
			}
			else	{
				header('Location: usermang.php');
			}
		}
	}
	else	{	//Form hasn't been posted, so post it
?>
					<div class='row'>
						<form method='post' action='' class='form-horizontal'>
							<fieldset>
								<div class='form-group'>
									<label for='user_name' class='col-lg-2 control-label'>Username</label>
									<div class='col-lg-9'>
										<input type='text' class='form-control' name='user_name' value='<?php echo $user_name; ?>'>
									</div>
								</div>
								<div class='form-group'>
									<label for='user_email' class='col-lg-2 control-label'>Email</label>
									<div class='col-lg-9'>
										<input type='text' class='form-control' name='user_email' value='<?php echo $user_email; ?>'>
									</div>
								</div>
								
<?php
		if ($user_id == $_SESSION['user_id'])	{	//Do not allow user to suspend
?>		
								<div class='form-group'>
									<label class='col-lg-2 control-label'>Suspend</label>
									<span class='col-lg-9'>
										<h5>You cannot suspend yourself</h5>
									</span>
								</div>
<?php
		}
		else	{
			if ($user_suspend == 0)	{	//User is not currently suspended
?>
								<div class='form-group'>
									<label class='col-lg-2 control-label'>Suspend</label>
									<div class='col-lg-9'>
										<div class='radio'>
											<label>
												<input type='radio' name='user_suspend' id='suspend' value='1'>
												Suspended
											</label>
										</div>
										<div class='radio'>
											<label>
												<input type='radio' name='user_suspend' id='unsuspend' value='0' checked=''>
												Unsuspended
											</label>
										</div>
									</div>
								</div>
<?php
			}
			else	{	//User is already suspended
?>
								
								<div class='form-group'>
									<label class='col-lg-2 control-label'>Suspend</label>
									<div class='col-lg-9'>
										<div class='radio'>
											<label>
												<input type='radio' name='user_suspend' id='suspend' value='1' checked=''>
												Suspended
											</label>
										</div>
										<div class='radio'>
											<label>
												<input type='radio' name='user_suspend' id='unsuspend' value='0'>
												Unsuspended
											</label>
										</div>
									</div>
								</div>
								
<?php
			}
		}
?>
								
								<div class='form-group'>
									<div class='col-lg-10 col-lg-offset-8'>
										<button class='btn btn-default'>Cancel</button>
										<button type='submit' class='btn btn-primary'>Submit</button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
<?php
	}
?>
				</div>
			</div>
		</div>
		<div class='col-lg-3'>
		</div>
	</div>
	

<?php
					}
				}
			}
		}
		else	{	//User is regular user, cannot access page
			echo "You do not have the authorization to view this page.";
		}
	}
	else	{
		echo "You cannot access this page directly.";
	}
?>

</div>

<?php
	include 'footer.php';
?>