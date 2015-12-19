<?php
include 'headers/header_signup.php';

$code = $_REQUEST['code'];
if (!isset($code))
{
	echo "No code supplied";
}
else
{
		$result = $con->query("SELECT * FROM proj4_users WHERE forget_code='$code'");
		if (mysqli_num_rows($result)== 1)
		{
		
			$results = $con->query("SELECT * FROM proj4_users WHERE forget_code='$code' AND active=1");
			if ($_POST['Done'])
{
	$username = $con->real_escape_string($_POST['user_name']);
	$email = $con->real_escape_string($_POST['user_email']);
	$uncheck = $con->query("SELECT * FROM proj4_users WHERE user_name='$username' and user_email='$email'");	
	if(mysqli_num_rows($uncheck)>= 1)
	{
	$password =$con->real_escape_string($_POST['user_pass']);
	$password = md5($password);
	$check = $con->query("SELECT * FROM proj4_users WHERE user_name='rpalle'");
	if(mysqli_num_rows($check)>= 1)
		{
		
		$con->query("UPDATE proj4_users SET user_pass= '$password' WHERE user_name= '$username'");
		$con->query("UPDATE proj4_users SET forget_code='0000' WHERE user_name= '$username'");
		
			
			?>
			<div class="row">
			<div class="col-lg-4">
			</div>
			<div class="well  col-lg-4">
			
			<fieldset>
			<legend>password changed</legend>
			<div class="form-actions">	<a href="signin.php">			
			<input type="submit" class="btn btn-primary" name='Done1' value='Done'><br>	
			</a>			
			</div>
			</fieldset>
			
			</div>
			</div>
			<div class="col-lg-4">
			</div>
<?php
		}
	else
		{
		echo " not a valid username";
		}
}
else
{

echo "username and email do not match";
}

}


else if(!$_POST['Done'])
{
?>

<div class="row">
	<div class="col-lg-4">
	</div>
	<div class="well  col-lg-4">
		<form class="form-inline" method="post" action="signin.php">
		<fieldset>
		<legend>Reset password</legend>
			<div class="form-actions">
				<label>
				<input type="text" class="input-medium" name="user_name" placeholder="Username" required="required" />
				</label><br>
				<label>
				<input type="text" class="input-medium" name="user_email" placeholder="email@cs.odu.edu" required="required" />
				</label><br>
				<label>
				<input type="password" class="input-medium" name="user_pass" placeholder="new password " required="required"/>
				</label><br>				
			<input type="submit" class="btn btn-primary" name='Done' value='Done'><br>										
			</div>
		</fieldset>
		</form>
	</div>
</div>
<div class="col-lg-4">
</div>
<?php
}			
		}	
		else
		{
		echo "invalid code";
		}
}
include 'footer.php';

?>
