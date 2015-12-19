<?php
	include 'headers/header_control.php';
?>

<div class='container'>

<?php
	if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
		if ($_SESSION['user_level'] == 1)	{	//User is an admin
			$sql = "SELECT user_id, user_name, user_email
						FROM proj4_users
						WHERE user_id=" . $_GET['id'];
			$results = $con->query($sql);
			
			if (!$results)	{	//Error retrieving user information
				echo "User information cannot be displayed.  Please try again later.";
			}
			else	{
				$row = $results->fetch_assoc();
				$user_id = $row['user_id'];
				$user_name = $row['user_name'];
				$user_email = $row['user_email'];
?>

<div class='container'>
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
						<h3 class='panel-title'>Delete User: <?php echo $user_name; ?></h3>
					</div>
				</div>
				<div class='panel-body'>
<?php
				if ($_SERVER['REQUEST_METHOD'] == 'POST')	{	//Form has been posted so save the results
					$sql2 = "UPDATE proj4_users 
							 SET user_delete = 1
							 WHERE user_id = '$user_id'";
					$results2 = $con->query($sql2);
					if (!$results2)	{
						echo "Error deleting user.  Please try again later.";
					}
					else	{	//Send email to user
						$to = $user_email;
						$subject = "HIMYM Forum--Account Deletion";
						$headers = "From: himymforum@cs.odu.edu\r\n";
						$message = $_POST['email_message'];
						mail($to, $subject, $message, $headers);
						echo "You have successfully deleted " . $user_name . ".<br>
								You will be redirected to the User Management page shortly.";
						header("Refresh:3; URL=usermang.php");
					}
					
					
				}
				else	{	//Form hasn't been posted, so post it
?>

					<div class='row'>
						<form method='post' action='' class='form-horizontal'>
							<fieldset>
								<div class='form-group'>
									<label for='user_email' class='col-lg-2 control-label'>Email</label>
									<div class='col-lg-9'>
										<input type='text' class='form-control' name='user_email' value='<?php echo $user_email; ?>' disabled>
									</div>
								</div>
								<div class='form-group'>
									<label for='email_subject' class='col-lg-2 control-label'>Subject:</label>
									<div class='col-lg-9'>
										<input type='text' class='form-control' name='email_subject' value='HIMYM Forum--Account Deletion' disabled>
									</div>
								</div>
								<div class='form-group'>
									<label for='email_message' class='col-lg-2 control-label'>Message:</label>
									<div class='col-lg-9'>
										<textarea class='form-control' rows='7' name='email_message'>Hello <?php echo $user_name; ?>,
									
Your account has been deleted for the following reason:


Sincerely,
The team at HIMYM Forum
										</textarea>
									</div>
								</div>
								<div class='form-group'>
									<div class='col-lg-10 col-lg-offset-8'>
										<a href='usermang.php' class='text-muted'>Cancel</a>
										<button type='submit' class='btn btn-primary'>Delete</button>
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
	</div>
</div>

<?php
			}
		}
		else	{
			echo "You are not authorized to view this page.";
		}
	}
	else	{
		echo "You cannot access this page directly.";
	}
?>

</div>