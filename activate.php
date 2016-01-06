<?php
include 'headers/header_signup.php';

$code = $_REQUEST['code'];
if (!isset($code))
{
	echo "No code supplied";
}
else
{
		$result = $con->query("SELECT * FROM proj4_users WHERE code='$code'");
		if (mysqli_num_rows($result)== 1)
		{
		
			$results = $con->query("SELECT * FROM proj4_users WHERE code='$code' AND active=1");
			if (mysqli_num_rows($results)== 1)
			{
			echo "you have already activated your account";
			header("Refresh: 3; URL=categories.php");
			}
			else{
			$results = $con->query("UPDATE proj4_users SET active='1' WHERE code='$code'");
			echo "Congratulations!  Your account has been activated.  You are now being redirected to sign in page.";
			header("Refresh: 3; URL=signin.php");
			}
		}	
		else
		{
		echo "invalid code";
		}
}
include 'footer.php';

?>
ramya



ramya