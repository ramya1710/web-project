<?php
	include 'headers/header_control.php';
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
 <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
 
<script type="text/javascript">
function edit(){

$('.val').hide();
$('.frm').show();
}
function show(){
$("#savefrm").submit();

}
function editlevel(id){
var k=".vallevel"+id;
var h=".frmlevel"+id;

$(k).hide();
$(h).show();
}
function showlevel(id){
var frm="#savelevelfrm"+id;
$(frm).submit();

}


  </script>
  

	<?php
		if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
			if ($_SESSION['user_level'] == 1 || $_SESSION['user_level'] == 2)	{
				if ($_SESSION['user_level'] == 1)	{
					echo 
						"<div class='container'>
							<h1>Welcome " . $_SESSION['user_name'] . "!</h1>
							<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;What would you like to do? </h3>
							<ul class='nav nav-tabs'>
								<li><a href='usermang.php'>Manage Users</a></li>
								<li><a href='catmang.php'>Manage Categories</a></li>
								<li><a href='topicmang.php'>Manage Threads</a></li>
								<li><a href='postmang.php'>Manage Posts</a></li>
								<li class='active'><a href='pgmang.php'>Manage Pagination</a></li>
							</ul>";
				}
				else	{
					echo 
						"<div class='container'>
							<h1>Welcome " . $_SESSION['user_name'] . "!</h1>
							<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;What would you like to do? </h3>
							<ul class='nav nav-tabs'>
								<li class='active'><a href='usermang.php'>Manage Users</a></li>
								<li><a href='topicmang.php'>Manage Threads</a></li>
								<li><a href='postmang.php'>Manage Posts</a></li>
							</ul>";
							
							echo "you are a moderator.you do not have access to this page";
				}
						if ($_SESSION['user_level'] == 1)	{
						$results = $con->query("SELECT * from proj4_pagination");
						$results1 = $con->query("SELECT * from proj4_images");
						if (!$results)	{
						//Display error
						echo "Page default values cannot be displayed.  Please try again later.";
					}
					else	{
						$num_rows = $results->num_rows;
						
						if ($num_rows==0)	{
							echo "No default values have been defined.";
						}
						else	{
						echo "<div class='row'>";
								echo "<div class='col-lg-6' >";
									
								echo "<table class='table-bordered'>
											<th>Page name</th> 
											<th>default value</th>
											<th>Edit</th>";
								while ($row = $results->fetch_assoc())	{
										echo "<tr>";
											echo "<td>";
												echo $row['page'];
											echo "</td>";
											echo "<td>";
											echo "<span class='frm' style='display:none' >";
												echo "<form id='savefrm' method='post' action='pagisave.php'>
						<input type='hidden' name='pagiid' value='".$row['id']."' />
						<input type='text' name='defvalue' value='".$row['value']."'  />
						
					</form>";
											echo "</span>";
											echo "<span class='val' >";
												echo $row['value'];
												echo "</span>";
											echo "</td>";
											echo "<td style='text-align: center; vertical-align: middle;'>";
											
												echo "<button id='editval' class='btn btn-sm val' onclick='edit()'>Edit</button>";
												echo "<button id='saveval' class='btn btn-sm frm' style='display:none' onclick='show()'>Save</button>";
											
											echo "</td>";
								
									
										echo "</tr>";
									}	
						echo "</table>";
										echo "</div>";
								
							echo "</div>";
						}
					}
					if(!$results1){
					
					}else{
					
					$num_rowslevel = $results1->num_rows;
					if($num_rowslevel==0){
					// no data 
					
					}else{
							echo "<div>";
										
										
										echo "<table class='table-bordered'>
											<th>User Level</th> 
											<th>default Count</th>
											<th>Edit</th>";
								while ($rowlevel = $results1->fetch_assoc())	{
										echo "<tr>";
											echo "<td>";
												echo $rowlevel['user_level'];
											echo "</td>";
											echo "<td>";
											echo "<span class='frmlevel".$rowlevel['id']."' style='display:none' >";
												echo "<form id='savelevelfrm".$rowlevel['id']."' method='post' action='userlevelcount.php'>
						<input type='hidden' name='levelid' value='".$rowlevel['id']."' />
						<input type='text' name='imagescount' value='".$rowlevel['images_count']."'  />
						
					</form>";
											echo "</span>";
											echo "<span class='vallevel".$rowlevel['id']."' >";
												echo $rowlevel['images_count'];
												echo "</span>";
											echo "</td>";
											echo "<td style='text-align: center; vertical-align: middle;'>";
											
												echo "<button id='editval' class='btn btn-sm vallevel".$rowlevel['id']."' onclick='editlevel(".$rowlevel['id'].")'>Edit</button>";
												echo "<button id='saveval' class='btn btn-sm frmlevel".$rowlevel['id']."' style='display:none' onclick='showlevel(".$rowlevel['id'].")'>Save</button>";
											
											echo "</td>";
								
									
										echo "</tr>";
									}	
						echo "</table>";
										
										
										
										
										
										echo "</div>";
										}
					}
					echo "</div>";
					}
			}
			else	{
				echo "You must be a site admin to view this page.";
			}
		}
		else	{
			echo "You must be logged in as a site admin to view this page.";
		}
	?>

 <?php
	include 'footer.php'
?>	