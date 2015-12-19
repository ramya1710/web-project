<?php
include 'headers/header_control.php';
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
			if ($_SESSION['user_level'] == 1 || $_SESSION['user_level'] == 2)	{
				
$topic_id = $_GET['id'];

$results = $con->query("UPDATE proj4_topics SET topic_froze = 1 WHERE topic_id = '$topic_id'");
if (!$results)	{
	echo "Error freezing thread.  Please try again later.";
}

header('Location: topicmang.php');

}
}
else
{
header('Location: index.php');
}

include 'footer.php';
?>