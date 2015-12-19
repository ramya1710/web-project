<?php
	include 'headers/header_control.php';
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
			if ($_SESSION['user_level'] == 1 || $_SESSION['user_level'] == 2)	{
				if ($_SESSION['user_level'] == 1)	{
$pagiid = $con->real_escape_string($_POST['pagiid']);
$defval = $con->real_escape_string($_POST['defvalue']);
echo $pagiid ;
echo $defval;
	$results = $con->query("UPDATE proj4_pagination SET value='$defval'	WHERE id='$pagiid'");
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