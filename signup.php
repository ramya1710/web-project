<?php
	include 'headers/header_signup.php';
	require 'CaptchasDotNet.php';
	$captchas = new CaptchasDotNet ('rpalle', 'ulGFjlYn4nww2M3R1IBqPAiMsGgivLQoES0YYUWm',
                                '/tmp/captchasnet-random-strings','3600',
                                'abcdefghkmnopqrstuvwxyz','6',
                                '240','80','000088');
?>

<?php
$wrngcap=false;
if ($_POST['signup'])
{
	$username = $con->real_escape_string($_POST['user_name']);
	$password =$con->real_escape_string($_POST['user_pass']);
	$email = $con->real_escape_string($_POST['user_email']);
	$mail = $con->real_escape_string($_POST['mail']);
	$captcha = $con->real_escape_string($_POST['captcha']);
	$random = $con->real_escape_string($_POST['random']);

	
	
	 if (!$captchas->validate ($random))
  {
    echo 'The session key (random) does not exist, please go back and reload form.<br/>';
    echo 'In case you are the administrator of this page, ';
    echo 'please check if random keys are stored correct.<br/>';
    echo 'See http://captchas.net/sample/php/ "Problems with save mode"';
  }
   elseif (!$captchas->verify ($captcha))
  {
    echo 'You entered the wrong Captcha. ';
	$wrngcap=true;
	
  }
  // Return a success message
  else
  {
 
	
	
	list($user,$domain)=split('[@]',$email);
	if ($domain == 'cs.odu.edu')
	{
	$password = md5($password);
 
	$check = $con->query("SELECT * FROM proj4_users WHERE user_name='$username'");
	if(mysqli_num_rows($check)>= 1)
		{
		echo "user name already taken";
		}
	else
		{
		
		$allowedExts = array("jpg", "jpeg", "gif", "png");
$extension = end(explode(".", $_FILES["file"]["name"]));
if ((($_FILES["file"]["type"] == "image/jpg")
||   ($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/pjpeg"))
&& ($_FILES["file"]["size"] < 9000000)
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
   // echo "Type: " . $_FILES["file"]["type"] . "<br>";
    //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

	$id2=uniqid();
	move_uploaded_file($_FILES["file"]["tmp_name"],"images/" .$id2.$_FILES["file"]["name"]);
	$path =   "images/" .$id2.$_FILES["file"]["name"];
    }
  }
else
  {
  echo "No file uploaded";
  }
		$code = rand(1111111,9999999);
		if (strcmp($mail,'html')==0)
		{
		$to = $email;
		$subject = "Activate your account";
		$headers = "FROM:   HIMYM";
		$body = "Hello $username,\n\nYou registered and need to activate your account.Click on the link below to activate.\nhttp://weiglevm.cs.odu.edu/~rpalle/proj4/activate.php?code=$code\n\nThnaks!";
		}
		else if (strcmp($mail,'text')==0)
		{
		$to = $email;
		$subject = "Activate your account";		
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "Content-Transfer-Encoding: 7bit\r\n";
		$headers .= "FROM:   HIMYM <br>";
		$body = "Hello $username <br><br> You registered and need to activate your account.<br> copy and paste the below URL :<br>";
		$body .=" \nhttp://weiglevm.cs.odu.edu/~rpalle/proj4/activate.php?code=$code";
		
		}
		if (!mail($to,$subject,$body,$headers))
		echo "try later";
		else
			{
			$con->query("INSERT INTO proj4_users(user_name,user_pass,user_email, user_date, code, user_dp) VALUES ('$username','$password','$email', NOW(), '$code', '$path')"); 
			echo "you have been registered.check ur mail ($email) to activate";
			}
		}
}
else
{
echo 'please enter cs mail';
}
}
}
 ?>
<div class="row">
	<div class="col-lg-4">
	</div>
	<div class="well  col-lg-4">
		<form class="form-horizontal" method="post" action="signup.php" enctype="multipart/form-data">
		<fieldset>
		<legend>Sign up</legend>
			<div class="form-actions">
				<label>
				<input type="text" class="input-medium" name="user_name" placeholder="Username" required="required" value="<? if($wrngcap){echo $username;} ?>" />
				</label><br>
				 <input type="hidden" name="random" value="<?= $captchas->random () ?>" />
				<label>
				<input type="password" class="input-medium" name="user_pass" placeholder="password" required="required"/>
				</label><br>
				<label>				
				<input type="text" class="input-medium" name="user_email" placeholder="@cs.odu.edu " required="required" value="<? if($wrngcap){echo $email;} ?>"/>
				</label><br>
				<input type="radio" name="mail" value="html" required value="1"/>HMTL mail &nbsp &nbsp 
				<input type="radio" name="mail" value="text"/>Text mail<br>
				 <label for="file">Upload image:</label>
				<input type="file" name="file" id="file" />
				 <?= $captchas->image () ?><a href="javascript:captchas_image_reload('captchas.net')">Reload Image</a><br>
          <a href="<?= $captchas->audio_url () ?>">Phonetic spelling (mp3)</a> <br>
				<input type="text" class="input-medium" name="captcha"  placeholder="Enter captcha" required="required" /><br>
			<input type="submit" class="btn btn-primary" name='signup' value='signup'><br>										
			</div>
		</fieldset>
		</form>
	</div>
</div>
<div class="col-lg-4">
</div>

					
<?php
	include 'footer.php'
?>					