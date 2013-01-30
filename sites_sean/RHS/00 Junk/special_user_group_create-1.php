<?php

include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'file_lib.php';
include 'counter_lib.php';
include 'html_lib.php';
include 'seo_lib.php';	
include 'ui_lib.php';	
include 'mail_sub_parms.php';	
include 'mail_lib.php';	
include 'chron_lib.php';	
include 'appdev_revision.php';	
include 'select_controls.php';	
include 'status_message.php';	
include 'admin_lib.php';
include 'appdev_revision.php';	


$statusMessage	=	$_GET["StatusMessage"];
$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";

?>





<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Create User Group - Step 1</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- Hide from the robots -->		
<meta name="robots" content="noindex,nofollow">
<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="nofollow">
<META NAME="GOOGLEBOT" CONTENT="noindex">
<!-- End Hide from the robots -->
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center" valign="middle" class="admin18Bold">Create User Group - Step 1</td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#SectionName" target="_blank" class="galleryUILink">Help</a></td>
          <td width="33%">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="5%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="70%">
          
          
          
<!-- The data encoding type, enctype, MUST be specified as below -->
    <form enctype="multipart/form-data" action="special_user_group_create-2.php" method="post">

<table width="100%" border="0">
  <tr>
    <td height="10" align="center">&nbsp;</td>
  </tr>
  
  <tr>
    <td><table width="80%" border="1" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="34%" height="40" align="right" valign="middle" class="rhs12Bold">Expedition Name&nbsp;&nbsp;</td>
        <td width="66%" valign="middle">&nbsp;&nbsp;&nbsp;<input name="ExpeditionName" type="text" id="ExpeditionName" size="32" maxlength="32" /></td>
      </tr>
      <tr>
        <td height="40" align="right" valign="middle" class="rhs12Bold">Expedition Start Date&nbsp;&nbsp;</td>
        <td valign="middle">&nbsp;&nbsp;&nbsp;<?php DisplayDateSelector();
  ?></td>
      </tr>
      <tr>
        <td height="40" align="right" valign="middle" class="rhs12Bold">Windows (PC) Excel CSV File&nbsp;&nbsp;</td>
        <td valign="middle"><input type="hidden" name="MAX_FILE_SIZE" value="30000" />
          &nbsp;&nbsp;&nbsp;<input name="userfile" type="file" /></td>
      </tr>

    </table></td>
  </tr>
  
  <tr>
  	<td height="50" align="center" valign="middle">
      
  	<input type="submit" value="Next" />
  	<input type="hidden" name="Mode" value="DoUpload" />
  	<input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>"> 
	<input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">    </td>
  </tr>
</table>
    </form>
          
          
          
          
          </td>
          <td width="25%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE">&nbsp;</td>
  </tr>
  <tr> 
    <td align="left" valign="top"> 
      <?php
	PrintAdminFooter();
	?>
    </td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>



<?php
DisplayStatusMessage($statusMessage);
?>
