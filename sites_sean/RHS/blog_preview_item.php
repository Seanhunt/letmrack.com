<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// blog_preview_item.php
//
// Shows preview of a blog item with all data included. Does not adhere to the site format.
//
// TODO: implement music blog template file
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
include 'file_lib.php';
include 'pw_lib.php';
include 'html_lib.php';
include 'blog_parms.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'select_controls.php';
include 'ui_lib.php';
include 'admin_lib.php';
include 'appdev_revision.php';

$debug	=	0;

// get the name of the blog
$blogName	=	BlogHeaderGetName($blTable);
$blogType	=	BlogHeaderGetType($blTable);

if($debug) print "blItemNumber: $blItemNumber<br>";
if($debug) print "blTable: $blTable<br>";

if($blogType=="CALENDAR")
	$templateFileName	=	"blog_event_preview.html";
else
	$templateFileName	=	"blog_post_preview.html";

$index	=	BlogGetItemIndex($blItemNumber,$blTable);

if($debug) print "index: $index<br>";

OpenDatabase();

$rs	=	QueryDatabase("SELECT * FROM $blTable WHERE blRecordNumber='$blItemNumber'");

CloseDatabase();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $blogName; ?> - Preview Post</title>



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="app_dev_II.css" rel="stylesheet" type="text/css">
<link href="admin.css" rel="stylesheet" type="text/css">
<link href="blog_II.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="40" align="center" class="rhs18Bold"><?php print $blogName; ?> - Preview Post</td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr bgcolor="#EEEEEE"> 
          <td width="30%" height="40">
          &nbsp;&nbsp;<a href="#" class="galleryUILink" onClick="javascript:window.close();">Close</a></td>
          <td align="center">&nbsp; </td>
          <td width="30%" height="40" align="right">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="20">&nbsp;</td>
  </tr>
  <tr> 
    <td height="100" align="center"> 
      <table width="100%" border="`" cellspacing="0" cellpadding="0">
        <tr>
          <td width="160">&nbsp;</td>
          <td><?php
			print	BlogFillItemRS($rs,0,$templateFileName,$blTable);	
			?></td>
          <td width="160">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td height="20" align="center">&nbsp; </td>
  </tr>
  <tr> 
    <td bgcolor="#dddddd">&nbsp;</td>
  </tr>
  <tr> 
    <td> 
      <?php
	PrintBlogFooter();
	?>
    </td>
  </tr>
</table>
</body>
</html>
