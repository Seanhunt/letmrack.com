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


$statusMessage	=	$_GET["StatusMessage"];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>New User Group - Step 1</title>
<link href="../images/css/local.css" rel="stylesheet" type="text/css" />
</head>
<body>
<!-- The data encoding type, enctype, MUST be specified as below -->
    <form enctype="multipart/form-data" action="special_user_group_create-2.php" method="post">

<table width="80%" border="0">
  <tr>
    <td align="center"><h2>Step 1</h2></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="70%" border="0" align="center">
      <tr>
        <td width="34%" height="30" align="right">Expedition Name</td>
        <td width="66%">&nbsp;&nbsp;&nbsp;<input name="ExpeditionName" type="text" id="ExpeditionName" size="32" maxlength="32" /></td>
      </tr>
      <tr>
        <td height="30" align="right">Expedition Start Date&nbsp;</td>
        <td>&nbsp;&nbsp;&nbsp;<?php DisplayDateSelector();
  ?></td>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr><td align="center">
      <!-- MAX_FILE_SIZE must precede the file input field -->
      <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
      <!-- Name of input element determines name in $_FILES array -->
      Pick an Excel CSV file to Upload...
      <input name="userfile" type="file" />
  <br /><br />
  <input type="submit" value="Go to Step 2 ->" />
  <input type="hidden" name="Mode" value="DoUpload" />
        </td>
  </tr>
</table>
    </form>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage); // show a ststus message alert if called for
?>
