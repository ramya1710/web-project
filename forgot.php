<?php
	include 'headers/header_signin.php';
?>


<?php

if ($_POST['Done'])
{
	$username = $con->real_escape_string($_POST['user_name']);
	
	
	$check = $con->query("SELECT * FROM proj4_users WHERE user_name='$username'");	
	$email = $con->query("SELECT user_email FROM proj4_users WHERE user_name='$username'");	
	$info = $email->fetch_assoc();
	 $mail = $info['user_email'];
	 $code = rand(1111111,9999999);	
	if(mysqli_num_rows($check)>= 1)
		{
		
		echo "Check your registered email for password reset link";
		$to = $mail;
		$subject = "Link to reset";
		$headers = "FROM:   HIMYM";
		$body = "Hello $username,\n\nclick the below link to reset your password: .\nhttp://weiglevm.cs.odu.edu/~rpalle/proj4/forgot_activate.php?code=$code\n\nThanks!";
		$con->query("update proj4_users set forget_code =$code where user_name='$username'");
		}
	else
		{
		echo " not a valid username<br>";
		}
	if (!mail($to,$subject,$body,$headers))
		echo "try again";
}
?>

<div class="row">
	<div class="col-lg-4">
	</div>
	<div class="well  col-lg-4">
		<form class="form-inline" method="post" action="forgot.php">
		<fieldset>
		<legend>Forgot password</legend>
			<div class="form-actions">
				<label>
				<input type="text" class="input-medium" name="user_name" placeholder="Username" required="required" />
				</label><br>				
			<input type="submit" class="btn btn-primary" name='Done' value='Done'><br>										
			</div>
		</fieldset>
		</form>
	</div>
</div>
<div class="col-lg-4">
</div>