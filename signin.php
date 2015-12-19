<?php
	include 'headers/header_signin.php';
?>
	
<div id="page" class="container">
	<?php
		//Check if user is already logged in
		if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
			echo '<p align="center"> You are already signed in</p>';
		}
		else	{
				//Form hasn't been posted yet, so display it
				if ($_SERVER['REQUEST_METHOD'] != 'POST')	{
	?>				
					<div class="row">
						<div class="col-lg-4">
						</div>
						<div class="well  col-lg-4">
							<form class="form-inline" method="post" action="">
								<fieldset>
									<legend>Sign In</legend>
									<div class="form-actions">
										<div class="col-lg-12">
											<input type="search" class="form-control" name="user_name" placeholder="Username" required="required" />
										</div>
										<div class="col-lg-12">
											<input type="password" class="form-control" name="user_pass" placeholder="Password " required="required"/>
										</div>
										<button class="btn btn-primary">Sign in</button><br><br>
										<label class="checkbox">
											<input type="checkbox" name="remember" value="remember">Remember me</input>
											&nbsp &nbsp &nbsp &nbsp &nbsp;
											<a href="forgot.php">Forgot password?</a>
										</label>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="col-lg-4">
					</div>
	<?php				
				}
				else	{
					//Form has been posted, process the data
					$user = $con->real_escape_string($_POST["user_name"]);
					$pass = $con->real_escape_string($_POST["user_pass"]);
					
					//Encrypt password
					$pass = md5($pass);

					$results = $con->query("SELECT * FROM proj4_users WHERE user_name = '$user' and user_pass = '$pass' and user_delete=0");
					$results1 = $con->query("SELECT * FROM proj4_users WHERE user_name = '$user' and user_pass = '$pass' and active=1 and user_delete=0");
					$num_rows = mysqli_num_rows($results);
					$num_rows1 = mysqli_num_rows($results1);
		
					//User entered the wrong credentials
					if ($num_rows==0)	{
						echo "Invalid username/password. Please try again.";
					}
					else if ($num_rows1 ==0){
					echo "Please activate your account";
					}
					//Everything went well, user can now be signed in
					else if($num_rows!=0)	{
						$_SESSION['signed_in'] = true;
						while($row=$results->fetch_assoc())	{
							//Set session variables
							$_SESSION['user_id'] = $row['user_id'];
							$_SESSION['user_name'] = $row['user_name'];
							$_SESSION['user_pass'] = $row['user_pass'];
							$_SESSION['user_level'] = $row['user_level'];
							$_SESSION['user_suspend'] = $row['user_suspend'];
						}
						if (isset($_POST['remember']))	{
							//Set cookies for 120 seconds
							setcookie('user_name', $_SESSION['user_name'], time() + 60*60);
						}
						header('Location: index.php');
					}	
					
				}
			}
	?>
</div>
<?php
include 'footer.php';
?>


