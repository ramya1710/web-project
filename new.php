<?php
	include 'headers/header_forum.php';
	$_SESSION['currentpage'] = 'replies.php';
?>

<link href="bootstrap-combined.min.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
    <script src="bootstrap-paginator.js"></script>
	
	
			
		
	<?php
$default = $con->query("SELECT value from proj4_pagination where page='replies'");
$perpageval=$default->fetch_assoc();

	if($_GET['perpage']){
					$perpage=$_GET['perpage'];
				}
				else{
					$perpage=$perpageval['value'];
				}				
				if($_GET['page']){
					$page=$_GET['page'];
				}
				else{
					$page=1;			
				}
				if ((!is_numeric($page)) ||(!is_numeric($perpage)) )
	{
	//header('Location: index.php');
	}
	else
	{
	
?>
	
<div class="container">
<?php
	$topic_id = $_GET['id'];
	if ((!is_numeric($topic_id)))
	{
	//header('Location: index.php');
	}
	$result = $con->query("SELECT * FROM proj4_posts WHERE post_id='$topic_id'");
		if (mysqli_num_rows($result)== 0)
		{
		//header('Location: index.php');
		} 
	$frozen = 0;
	$sql = "SELECT cat_id, cat_name, topic_id, topic_subject, topic_by, topic_date, topic_froze, user_name, user_level, role_name
				FROM proj4_categories, proj4_topics, proj4_users, proj4_roles
				WHERE topic_cat=cat_id AND topic_by=user_id AND user_level=role_id AND topic_id=$topic_id"; 
	$results = $con->query($sql);
	$row = $results->fetch_assoc();	
	$frozen = $row['topic_froze'];
?>
	<ul class='breadcrumb'>
		<li><a href='categories.php'>Forum</a></li>
		<li><a href='topics.php?id=<?php echo $row['cat_id']; ?>'><?php echo $row['cat_name']; ?> </a></li>
		<li class='active'> <?php echo $row['topic_subject'] ?> </li>
	</ul>

			<table><tr><td><div id="pagination" ></div></td><td>
				<!--give gap--><label>Topics per page :</label></td><td>
				<div><select style="width: 50px;" id ="perpage">
  <option value="<?php echo $perpage;?>"><?php echo $perpage;?></option>
  <option value="1">1</option>
  <option value="5">5</option>
  <option value="10">10</option>
</select></div></td></tr></table>
<?php
$pageno=($page-1)*$perpage;
				$count=$con->query(" SELECT count(post_topic) as c FROM proj4_posts, proj4_users, proj4_roles WHERE post_by=user_id AND user_level=role_id AND post_topic= " . $row['topic_id'] );
				$asdf=$count->fetch_assoc();
			//	echo $asdf['c'];
				$totalpages=ceil($asdf['c']/$perpage);
				if ($totalpages == 0)
				{
				$totalpages=1;
				}
				else
				{
				$totalpages=$totalpages;
				}
				if($page > $totalpages)
				{
				header('Location: index.php');
				}
	//echo $totalpages;

	$sql1 = "SELECT post_id, post_content, post_by, post_date, post_edit, post_editby, post_edited, post_deleted, user_id,avatar_path, user_name, user_level, role_name
				FROM proj4_posts, proj4_users, proj4_roles
				WHERE post_by=user_id AND user_level=role_id AND post_topic=" . $row['topic_id'] . 
				" ORDER BY post_date" ." limit $pageno , $perpage ";
	$results1 = $con->query($sql1);
	while ($row1 = $results1->fetch_assoc())	{
		$sql2 = "SELECT user_id, COUNT(post_id) as num_posts
							FROM proj4_users LEFT JOIN proj4_posts ON user_id=post_by
							WHERE user_id=" .  $row1['user_id'] .
							" GROUP BY post_by";
		$results2 = $con->query($sql2);
		$row2 = $results2->fetch_assoc();
		
		$sql4 = "SELECT user_id, user_name
					FROM proj4_users
					WHERE user_id=" . $row1['post_editby'];
		$results4 = $con->query($sql4);
		$row4 = $results4->fetch_assoc();
		
		$user_level = 'newbie';
		if ($row2['num_posts'] >= 10 && $row2['num_posts'] < 20)	{
			$user_level = 'intermediate';
		}
		else if ($row2['num_posts'] >= 20)	{
			$user_level = 'master';
		}
?>

	<div class='row'>
		<div class='col-lg-12'>
			<div class='panel panel-warning'>
				<div class='panel-heading'>
					<div class='row'>
						<div class='col-lg-3'>
							<h4><?php echo $row1['user_name'] . " (" . $row1['role_name'] . ")"; ?></h4>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user_level;?>
						</div>
						<div class='col-lg-3'>
							<h4>Created on:</h4>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row1['post_date'];?>
						</div>
<?php
		if ($row1['post_edited'] == 1)	{
?>
						<div class='col-lg-4'>
							<h4>Edited by:</h4>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row4['user_name'];?> on <?php echo $row1['post_edit'];?>
						</div>
<?php
		}
		else	{
			echo "<div class='col-lg-4'></div>";
		}
		if ($row1['post_by'] == $_SESSION['user_id'])	{
			if ($frozen != 1 && $row1['post_deleted'] !=1)	{
?>
						<div class='col-lg-2'>
							<h4><a href='editreply.php?id=<?php echo $row1['post_id']; ?>' class='text-muted'>Edit</a></h4>
						</div>
<?php	
			}
		}
?>
					</div>
				</div>
				<div class='panel-body'>
					<div class='row'>
						<div class='col-lg-12'>
<?php
		if ($row1['post_deleted'] == 1)	{
			echo "This post has been deleted.";
		}
		else
		{
			echo $row1['post_content'];		?>
			<div class='row'>	
					<?php 
					$input_string = $row1['avatar_path'];
					$str_explode = "|";
					$exploded_array = explode($str_explode,$input_string );
							$image_count=count($exploded_array);
							for($j=0;$j<$image_count-1;$j++){
					?>
			<a href="<?php echo $exploded_array[$j] ;?>" onclick="window.open (this.href, 'small-view'); return false">
				<img src="<?php echo $exploded_array[$j] ;?>" height=100px width=100px style="margin-left:50px"/>
			</a>			
			</div>
			<?php
		}
		}
?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	}
?>

<?php
	if ($frozen == 0)	{	//Display reply option
		if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)	{
		
		
	$sql1 = "SELECT post_id, post_content, post_by, post_date, post_edit, post_editby, post_edited, post_deleted, user_id,avatar_path, user_name, user_level, role_name
				FROM proj4_posts, proj4_users, proj4_roles
				WHERE post_by=user_id AND user_level=role_id AND post_topic=" . $row['topic_id'] . 
				" ORDER BY post_date" ." limit $pageno , $perpage ";
	$results1 = $con->query($sql1);
	while ($row1 = $results1->fetch_assoc())	{
		$sql2 = "SELECT user_id, COUNT(post_id) as num_posts
							FROM proj4_users LEFT JOIN proj4_posts ON user_id=post_by
							WHERE user_id=" .  $row1['user_id'] .
							" GROUP BY post_by";
		$results2 = $con->query($sql2);
		$row2 = $results2->fetch_assoc();
		
		$user_level1 = 1;
		if ($row2['num_posts'] >= 10 && $row2['num_posts'] < 20)	{
			$user_level1 = 2;
		}
		else if ($row2['num_posts'] >= 20)	{
			$user_level1 = 3;
		}
		}
			
?>


				<script>		
			var nposts= <?php echo $user_level1; ?>;
			$( document ).ready(function() { 					
				$('#file').change(function(){  
					//alert(this.files.length);
                    if(this.files.length>nposts) {
						alert('to many files');
                        
						 this.value = '';
					}
                    else{
						//alert('success');
						 $("#reply").submit();
					}
                });
			});
		</script>   
			           
			
		<?php	
			
			$sql3 = "SELECT user_id, user_suspend,user_level
						FROM proj4_users
						WHERE user_id=" . $_SESSION['user_id'];
			$results3 = $con->query($sql3);
			$row3 = $results3->fetch_assoc();
			if ($row3['user_level'] == 1)
			if ($row3['user_suspend'] == 0)	{	//User is not suspended
				if ($_SERVER['REQUEST_METHOD'] == 'POST')	{	//Form has been posted so save the results
					$post_content = $con->real_escape_string($_POST['post_content']);
					//$image_tempname = $_FILES['file']['name'];
					$count_images=count($_FILES['file']['tmp_name']);
					$post_date = date("Y-m-d H:i:s");
					$post_topic = $con->real_escape_string($_GET['id']);
					$post_by = $_SESSION['user_id'];
					$img_array.="";
				$allowedExts = array("jpg", "jpeg", "gif", "png");
	for($i=0;$i<$count_images;$i++)
	{
		$img_array.="avatars/".$_FILES['file']['name'][$i].'|';
	}
	for($i=0;$i<$count_images;$i++){
	$extension = end(explode(".", $_FILES["file"]["name"][0]));
	if ((($_FILES["file"]["type"][$i] == "image/gif") || 
		  ($_FILES["file"]["type"][$i] == "image/jpeg") || 
		 ($_FILES["file"]["type"][$i] == "image/png") || 
		 ($_FILES["file"]["type"][$i] == "image/pjpeg")) && 
		 ($_FILES["file"]["size"][$i] < 9000000) && 
		 in_array($extension, $allowedExts))	{
		
		if ($_FILES["file"]["error"][$i] > 0)	{
			echo "Return Code: " . $_FILES["file"]["error"][$i] . "<br>";
		}
		else	{
			echo "Upload: " . $_FILES["file"]["name"][$i] . "<br>";
  
			if (file_exists("avatars/" . $_FILES["file"]["name"][$i]))	{
				echo $_FILES["file"]["name"][$i] . " already exists. ";
			}
			else	{
				move_uploaded_file($_FILES["file"]["tmp_name"][$i],"avatars/" . $_FILES["file"]["name"][$i]);
				$path =   "avatars/" . $_FILES["file"]["name"][$i];
				
			}
		 }
					}
}					
					
					$sql5 = "SELECT topic_id, cat_id
								FROM proj4_topics, proj4_categories
								WHERE topic_cat=cat_id AND topic_id=" . $post_topic;
					$results5 = $con->query($sql5);
					$num_rows5 = $results5->num_rows;
					
					
					
					$sql6 = "SELECT user_id, user_suspend
								FROM proj4_users
								WHERE user_id=" . $post_by;
					$results6 = $con->query($sql6);
					$row6 = $results6->fetch_assoc();
					
					if ($num_rows5 == 0)	{
						echo "Sorry, this category is no longer available. You are being redirected to the main category page.";
						header("Refresh:3; URL=categories.php");
					}
					else if ($row6['user_suspend'] == 1)	{
						echo "You are currently suspended and are unable to post messages.";
					}
					else	{
						$sql4 = "INSERT INTO proj4_posts (avatar_path,post_content, post_date, post_topic, post_by)
									VALUES ('$img_array','$post_content', '$post_date', '$post_topic', '$post_by')";
						$results4 = $con->query($sql4);
					
						if (!$results4)	{
							echo "Your message has not been saved.  Please try again later.";
						}
						else	{
						
							echo "Your message was successfully posted.";
							//header('Refresh:1; ');
							//header('Location:http://weiglevm.cs.odu.edu/~rpalle/proj4/replies.php?id='.$row1['post_id'].'&page='.$pageno.'&perpage='.$perpage);
							//header('Location: index.php');
							
						}
					}
				}
				else	{	//Create form
?>
	<div class='row'>
		<div class='col-lg-12'>
			<form method='post' action='' id= 'reply' class='form-horizontal'  enctype="multipart/form-data">
				<fieldset>
					<div class='row'>
						<div class='col-lg-12'>
							<textarea class='form-control' rows='7' name='post_content' placeholder='Type your reply...'></textarea>
						</div>
					</div>
					<div class='row'>
						<div class='col-lg-1'>	
								<input name="file[]" type="file" id="file" multiple />							
							<button type='submit' name="submit" class='btn btn-default'>Post</button>							
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>

<?php
				}
			}
			else	{	//User is suspended and cannot post
				echo "You are currently suspended and are unable to post a message.";
			}
		}
		else	{	//User must sign in before posting
			echo "You must be signed in to post a message.  Click here to <a href='signin.php'>sign in</a> now.";
		}
	}
	else	{	//Thread is frozen
		echo "This thread is frozen.";
	}
?>

</div>

 <script type='text/javascript'>
		var options = {
            currentPage: <?php echo $page;?>,
            totalPages: <?php echo $totalpages;?>,
            onPageClicked: function(e,originalEvent,type,page){
                window.location.href = 'http://weiglevm.cs.odu.edu/~rpalle/proj4/replies.php?id=<?php echo $_GET['id'];?>&page='+page+'&perpage=<?php echo $perpage;?>';
				//alert(page);
            }
        }	
$('#perpage').val(<?php echo $perpage;?>);
        $('#pagination').bootstrapPaginator(options);
		$('#perpage').change(function() {

   window.location.href = 'http://weiglevm.cs.odu.edu/~rpalle/proj4/replies.php?id=<?php echo $_GET['id'];?>&page=1&perpage='+$('#perpage').val();
});
    </script>
	
<?php
}
	include 'footer.php';
?>