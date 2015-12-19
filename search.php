<?php
	include 'headers/header_search.php';
?>
<?php
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
$searchstring = $con->real_escape_string($_POST['search']);
if (strlen($searchstring) == 0)
{
$query2="select cat_name from proj4_categories";
$check2 = $con->query($query2);

?>
<div class="container">
<form class="form-inline" method="post" action="search.php">
								 <input style="margin-top:12px" class="input-medium" name="search" placeholder="Search" required="required"/><br><br>
								 <select name="search2" onchange=this.form.submit() >
								 <option> Select  Category </option>
								 <?php while($row2 = $check2->fetch_assoc())	{	?> 								 
									<option value="<?php echo $row2['cat_name']; ?>"><?php echo $row2['cat_name']; ?>  </option>	
									 <?php }  ?>
									</select>						
								</form> 
								</div>
<?php
	include 'footer.php';
?>
<?php
}
else if(strlen($searchstring) >= 1){
	
	if (isset($_POST['search']))
	{
		$searchstring2 = $con->real_escape_string($_POST['search']);
		$delete_copy = "delete from proj4_posts_copy";
		$delete_res = $con->query($delete_copy); 

		$reply_copy = "INSERT INTO  proj4_posts_copy SELECT post_id,post_content,post_topic,post_by,cat_name,topic_subject,user_name FROM proj4_categories pc,proj4_topics pt,proj4_users pu,proj4_posts pp where pc.cat_id=pt.topic_cat and pp.post_by = pu.user_id and pt.topic_id  = pp.post_topic ";
		$reply_copy_res = $con->query($reply_copy);


		if (isset($_POST['search2']))
		{
		$searchstring4 = $con->real_escape_string($_POST['search2']);
		$search_rep = "select category_name as cat_name,topic_sub as topic_subject,post_content,post_username as user_name, MATCH(post_content) AGAINST('+$searchstring2' IN BOOLEAN MODE) as mostSearched from proj4_posts_copy where MATCH(post_content) AGAINST('+$searchstring2' IN BOOLEAN MODE) and category_name = '$searchstring4' order by mostSearched desc";
		$check1 = $con->query($search_rep);

		}
		else if(!isset($_POST['search2']))
		{
			/*  $query1="select cat_name,topic_subject,post_content,user_name from proj4_posts,proj4_topics,proj4_categories,proj4_users where post_topic=topic_id and topic_cat=cat_id and post_by=user_id and cat_name='$searchstring2' and post_content like '%$searchstring%'";
			$check1= $con->query($query1);
			  */
			 
			 

			$search_rep = "select category_name as cat_name,topic_sub as topic_subject,post_content,post_username as user_name, MATCH(post_content) AGAINST('+$searchstring2' IN BOOLEAN MODE) as mostSearched from proj4_posts_copy where MATCH(post_content) AGAINST('+$searchstring2' IN BOOLEAN MODE) order by mostSearched desc";
			$check1 = $con->query($search_rep);

		}
		if(mysqli_num_rows($check1) == 0){
		
		 $query1="select cat_name,topic_subject,post_content,user_name from proj4_posts,proj4_topics,proj4_categories,proj4_users where post_topic=topic_id and topic_cat=cat_id and post_by=user_id and post_content like '%$searchstring%'";
		$check1 = $con->query($query1);
	
		
		}	  
	 }
	$query2="select cat_name from proj4_categories;";
	$check2 = $con->query($query2);

	if(mysqli_num_rows($check1)>= 1)
			{ 
					
?>	
	<div class="container">
		<div class='row'>
			<div class='col-lg-12'>
				<div class="panel panel-warning">
					<div class="panel-heading">
						<div class="row">
							Results for <?php echo $searchstring; ?>
						</div>
					</div>		

					
					<div class='panel-body'>
					<?php while($row1 = $check1->fetch_assoc())	{	?>
						<div class='row'>						
							<div class='col-lg-3'>
								<?php echo $row1['cat_name']; ?>		
							</div>
							<div class='col-lg-3'>
								<?php echo $row1['topic_subject']; ?>		
							</div>	
							<div class='col-lg-3'>
								<?php echo $row1['post_content']; ?>		
							</div>	
							<div class='col-lg-3'>
								<?php echo $row1['user_name']; ?>		
							</div>							
						</div>
						<?php } ?>
					</div>
				</div>
				<form class="form-inline" method="post" action="search.php">
									 <input style="margin-top:12px" class="input-medium" name="search" placeholder="Search" required="required"/><br><br>
									 <select name="search2" onchange=this.form.submit() >
									 <option> Select  Category </option>
									 <?php while($row2 = $check2->fetch_assoc())	{	?> 								 
										<option value="<?php echo $row2['cat_name']; ?>"><?php echo $row2['cat_name']; ?></option>	
										 <?php }  ?>
										</select>
			</div>
		</div>
	</div>
			
			<?php
			
			}
		else
			{
			echo "No results found";
			}

	}
		else	{
			echo "You must be signed in to view this page";
		}
} ?>

