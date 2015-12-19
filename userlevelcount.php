<?php
	include 'headers/header_control.php';
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
			if ($_SESSION['user_level'] == 1 || $_SESSION['user_level'] == 2)	{
				if ($_SESSION['user_level'] == 1)	{
$levelid = $con->real_escape_string($_POST['levelid']);
$imagescount = $con->real_escape_string($_POST['imagescount']);
echo $pagiid ;
echo $defval;
	$results = $con->query("UPDATE proj4_images SET images_count='$imagescount'	WHERE id='$levelid'");
												if (!$results)	{
													//Display error
													echo "Error updating pagination.  Try again later.";
												}
												else	{
												
												header('Location: pgmang.php');
												}
}
}
else
{
header('Location: index.php');
}
}
else
{
header('Location: index.php');
}

?>