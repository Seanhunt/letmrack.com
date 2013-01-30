
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// debug_show.php
//
// shows the debug log
// 
// Revision:	121006 File Originated
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'admin_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	
include 'debug_lib.php';

$debug		=	0;

$submit		=	GetPassedValue("Submit");
$backLink	=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";
$numberOfRows	=	GetNumberOfRows("SELECT * FROM APPDEV_Debug WHERE 1");

if($debug) print "submit: $submit<br>";
if($debug) print "numberOfRows: $numberOfRows<br>";

//DebugLog("submit: $submit",$debug,__LINE__,__FILE__,__FUNCTION__);


if($submit=="Clear Debug Log")
	{
	if($debug) print "CLEAR LOG MODE<br>";
	
	$query	=	"TRUNCATE APPDEV_Debug";
	OpenDatabase();
	QueryDatabase($query);
	CloseDatabase();
	//DebugLog("query: $query",$debug,__LINE__,__FILE__,__FUNCTION__);
	}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Debug Message Viewer</title>
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
    <td height="30" align="center" valign="middle" class="admin18Bold">Debug Message 
      Viewer </td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="250" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="300" align="center"><a href="admin_help.php#SectionName" target="_blank" class="galleryUILink">Help</a></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="3%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="93%">
		  
		  
		  
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center"> 
                  <form name="form1" method="post" action="debug_show.php">
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
        <input type="submit" name="Submit" value="Clear Debug Log">
                  </form></td>
  </tr>
  <tr> 
                <td class="admin12Bold">&nbsp;</td>
  </tr>


  
  	<?php
	PrintDebugTable();
	?>

</table>
		  
		  
		  
		  </td>
          <td width="4%" bgcolor="#999999">&nbsp;</td>
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
<?php

function PrintDebugTable()
{
$debug			=	0;
$numberOfRows	=	GetNumberOfRows("SELECT * FROM APPDEV_Debug WHERE 1");

if($debug) print "PrintDebugTable()<br>";

if($numberOfRows==0)
	{
	print '<p class="admin12Bold">&nbsp;&nbsp;There are no messages in the log to display...</p>';
	return;
	}

if($debug) print "Number of Rows: $numberOfRows<br><br>";

for($i=0;$i<$numberOfRows;$i++)
	{
	$line		=	GetFieldByIndex("APPDEV_Debug","line",$i);
	$item		=	GetFieldByIndex("APPDEV_Debug","id_number",$i);
	$function	=	GetFieldByIndex("APPDEV_Debug","function",$i);
	$module		=	GetFieldByIndex("APPDEV_Debug","module",$i);
	$time		=	GetFieldByIndex("APPDEV_Debug","time_stamp",$i);
	$message	=	GetFieldByIndex("APPDEV_Debug","message",$i);

	PrintOneDebugRow($item,$time,$module,$function,$line,$message);
	}
}


function PrintOneDebugRow($item,$time,$module,$function,$line,$message)
{
print	'
<!-- Begin Debug Table Row -->
  <tr> 
    <td>
	<hr>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td valign="middle">
			<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="admin12Bold">
                          <tr valign="middle"> 
                            <td width="50" height="30" align="center"> ' . $item 
                              . '</td>
                            <td width="200" align="left"> ' . $time . '</td>
                            <td width="200" align="left">Module: ' . $module . 
                              '</td>
                            <td align="left">Function: ' . $function . '</td>
              </tr>
            </table></td>
        </tr>
        <tr> 
                      <td height="30" class="admin12Regular"><br>
                        &nbsp;Line #: ' . $line . " " . $message . '</td>
        </tr>
      </table></td>
  </tr>
<!-- End Debug Table Row -->
		';
		

}

 



?>

