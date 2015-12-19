<?php
	include 'headers/connect.php';
?>
	
	<?php	
			session_destroy();
			//setcookie('user_id', "", time() - 3600);
			setcookie('user_name', "", time() - 120);
			//setcookie('user_pass', "", time() - 3600);
			//setcookie('user_level', "", time() - 3600);
			
			
			header('Location: index.php');
	?>

<?php
	include "footer.php";
?>