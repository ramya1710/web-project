<?php
include 'headers/header_control.php';

if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
			if ($_SESSION['user_level'] == 1 || $_SESSION['user_level'] == 2)	{
				
$post_id = $_GET['id'];

$results = $con->query("UPDATE proj4_posts SET post_deleted = 1 WHERE post_id = '$post_id'");
if (!$results)	{
	echo "Error deleting post.  Please try again later.";
}

header('Location: postmang.php');
}
}
else
{

header('Location: index.php');
}

include 'footer.php';
?>