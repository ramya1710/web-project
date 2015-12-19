<?php
//Create a new session
session_start();

//Connect to the database
$con = new mysqli ("localhost", "rpalle", "asdf1265", "rpalle");
if (mysqli_connect_errno())	{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>