<?php
include 'headers/header_control.php';
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
			if ($_SESSION['user_level'] == 1 || $_SESSION['user_level'] == 2)	{
				if ($_SESSION['user_level'] == 1)	{
$cat_id = $_GET['id'];

$results = $con->query("DELETE FROM proj4_categories WHERE cat_id = '$cat_id'");
header('Location: catmang.php');
}
}
else{header('Location: index.php');}
}
else
{

header('Location: index.php');
}
include 'footer.php';
?>