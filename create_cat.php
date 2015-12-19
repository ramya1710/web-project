<?php
	if ($_SERVER['REQUEST_METHOD'] != 'POST')	{
		//Form hasn't been posted yet, so display it
		echo '<form method="post" action=""><center>
					Category name: <input type="text" name="cat_name" required="required"/><br>
					<input type="submit" value="Add category"></center>
				</form>';
	}
	else	{
		//form has been posted, so save it
		$cat_name = $con->real_escape_string($_POST['cat_name']);
		$cat_description = $con->real_escape_string($_POST['cat_description']);
		$results = $con->query("INSERT INTO proj4_categories (cat_name)
									VALUES ('$cat_name')");
		if (!$results)	{
			//Display error
			echo "Error adding new category";
		}
		else	{
			echo "New category successfully added.";
		}
	}
	
?>