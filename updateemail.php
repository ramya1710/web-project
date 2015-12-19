<?php
	include 'headers/header_profile.php';
?>

<?php
	if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
		$sql1 = "SELECT user_id, user_name, user_email, user_date, user_level, user_dp
				 FROM proj4_users
				 WHERE user_id = " . $_SESSION['user_id'];
		$results1 = $con->query($sql1);
		$row1 = $results1->fetch_assoc();
		
		$sql7 = "SELECT role_id, role_name
				 FROM proj4_roles
				 WHERE role_id = " . $row1['user_level'];
		$results7 = $con->query($sql7);
		$row7 = $results7->fetch_assoc();
		
		$sql8 = "SELECT user_id, COUNT(post_id) as num_posts
				 FROM proj4_users LEFT JOIN proj4_posts ON user_id=post_by
				 WHERE user_id=" .  $row1['user_id'] .
				 " GROUP BY post_by";
		$results8 = $con->query($sql8);
		$row8 = $results8->fetch_assoc();
		
		$user_level = 'newbie';
		if ($row8['num_posts'] >= 10 && $row8['num_posts'] < 20)	{
			$user_level = 'intermediate';
		}
		else if ($row8['num_posts'] >= 20)	{
			$user_level = 'master';
		}	
		
		if ($row1['user_dp'] != '')
			$path = $row1['user_dp'];
		else
			$path = "images/unknown.jpeg";
?>
	
<div class='container'>
	<div class='row'>
		<h1><?php echo $row1['user_name'] . " (" . $row7['role_name'] . ")"; ?></h1>
		<h3>&nbsp;&nbsp;&nbsp;<?php echo $user_level; ?></h3>
	</div>
	<br>
	<div class='row'>
		<div class='col-lg-3'>
			<img src="<?php echo $path; ?>" alt="Image cannot be displayed." style="max-width: 100%"/>
		</div>
	
<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST')	{	//Form has been posted so save the results
		
			//Check if current password matches password in the database
			$sql2 = "SELECT user_pass
					 FROM proj4_users
					 WHERE user_id = " . $_SESSION['user_id'];
			$results2 = $con->query($sql2);
			$row2 = $results2->fetch_assoc();
			
			$pass = $_POST['pass'];
			$pass = md5($pass);
			
			$new_email = $_POST['new_email'];
			
			if ($row2['user_pass'] == $pass)	{	//Current password matches that in db, go ahead and update email
				$sql3 = "UPDATE proj4_users 
						 SET user_email = '$new_email'
						 WHERE user_id = " . $_SESSION['user_id'];
				$results3 = $con->query($sql3);
				if (!$results3)	{
					echo "Error updating email.  Please try again later.";
				}
				else	{
					echo "You have successfully updated your email. <br>
							You will be redirected back to your user profile shortly. </div>";
					header("Refresh:3; URL=userprofile.php");
				}
			}
			else	{	//Current password does not match what is in the database, repost form with alert
?>

		<div class='col-lg-6'>
			<p class='text-danger'>Password is incorrect.</p><br>
			<form method='post' action='' class='form-horizontal'>
				<fieldset>
					<div class='form-group'>
						<label for='new_email' class='col-lg-4 control-label'>New Email:</label>
						<div class='col-lg-7'>
							<input type='text' class='form-control' name='new_email' value=<?php echo $new_email; ?>>
						</div>
					</div>
					<div class='form-group has-error'>
						<label for='pass' class='col-lg-4 control-label'>Verify Password:</label>
						<div class='col-lg-7'>
							<input type='password' class='form-control' name='pass'>
						</div>
					</div>
					<div class='form-group'>
						<div class='col-lg-10 col-lg-offset-8'>
							<a href='userprofile.php' class='text-muted'>Cancel</a>
							<button type='submit' class='btn btn-primary'>Update</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

<?php
			}
		}
		else	{	//Form has not been posted, so post it
?>
		<div class='col-lg-6'>
			<form method='post' action='' class='form-horizontal'>
				<fieldset>
					<div class='form-group'>
						<label for='new_email' class='col-lg-4 control-label'>New Email:</label>
						<div class='col-lg-7'>
							<input type='text' class='form-control' name='new_email' placeholder='@cs.odu.edu'>
						</div>
					</div>
					<div class='form-group'>
						<label for='pass' class='col-lg-4 control-label'>Verify Password:</label>
						<div class='col-lg-7'>
							<input type='password' class='form-control' name='pass'>
						</div>
					</div>
					<div class='form-group'>
						<div class='col-lg-10 col-lg-offset-8'>
							<a href='userprofile.php' class='text-muted'>Cancel</a>
							<button type='submit' class='btn btn-primary'>Update</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>

<?php
		}
	}
	else	{
		echo "You must be signed in to view this page";
	}
?>

<?php
	include 'footer.php';
?>