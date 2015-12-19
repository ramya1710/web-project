<?php
	include 'headers/header_contactus.php';
?>

	<div id="page" class="container">
		<div>
  <div style="float: left">
     <form name="contactform" method="post" action="send_form_email.php">
 
<table width="450px">
 
<tr>
 
 <td valign="top">
 
  <label for="first_name">First Name *</label>
 
 </td>
 
 <td valign="top">
 
  <input  type="text" name="first_name" maxlength="50" size="30" required>
 
 </td>
 
</tr>
 
<tr>
 
 <td valign="top"">
 
  <label for="last_name">Last Name *</label>
 
 </td>
 
 <td valign="top">
 
  <input  type="text" name="last_name" maxlength="50" size="30" required>
 
 </td>
 
</tr>
 
<tr>
 
 <td valign="top">
 
  <label for="email">Email Address *</label>
 
 </td>
 
 <td valign="top">
 
  <input  type="text" name="email" maxlength="80" size="30" required>
 
 </td>
 
</tr>
 
<tr>
 
 <td valign="top">
 
  <label for="telephone">Telephone Number</label>
 
 </td>
 
 <td valign="top">
 
  <input  type="text" name="telephone" maxlength="30" size="30">
 
 </td>
 
</tr>
 
<tr>
 
 <td valign="top">
 
  <label for="comments">Comments *</label>
 
 </td>
 
 <td valign="top">
 
  <textarea  name="comments" maxlength="1000" cols="25" rows="6" required></textarea>
 
 </td>
 
</tr>
 
<tr>
 
 <td colspan="2" style="text-align:center">
 
  <input type="submit" value="Submit"> 
 
 </td>
 
</tr>
 
</table>
 
</form>
  </div>
  <div >
     <h1 align="center"> Get in Touch  : </h1>
	 <p align="center"><i>HIMYM </br>
	 Phone number:658-560-9876</br>
	 
  </div>
  
  <div style="clear: both" ></div>
	</div>
</div>
<?php
include 'footer.php';
?>
