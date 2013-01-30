<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

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
include 'status_message.php';
include 'admin_lib.php';
include 'appdev_revision.php';

$debug	=	0;

// ---------------------------------------------------------------------------------------------------------------------------------------
// Authorize the user
// ---------------------------------------------------------------------------------------------------------------------------------------

if(UserIsAdmin($systemUserID) || UserIsSuper($systemUserID))
	{
	if($debug) print "User is ADMIN or SUPER<br>\n";

	if(AuthorizeAdminUser($systemUserID,$systemPassword)==false)
		{
		exit(0);
		}
	}
else
	{
	if($debug) print "User is BLOGGER<br>\n";

	$table		=	$blTable;
	
	if($debug) print "table: $table<br>\n";
	
	// authenticate
	if(AuthorizeBlogUser($systemUserID,$systemPassword,$table)==false)
		{
		print "Access denied.<br>";
		exit(0);
		}
	}	

if($debug) print "blPosterID: $blPosterID<br>";
if($debug) print "blTable: $blTable<br>";

// get the name of the blog
$blogName	=	BlogHeaderGetName($blTable);

$numberOfLinks	=	BlogGetNumberOfLinks($blTable,$blPosterID);

if($debug) print "numberOfLinks: $numberOfLinks<br>";

$helpLink				=	"blog_help.php#EditDeleteLinks";
$backLink				=	"blog_home.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $blogName; ?> - Edit and Delete Links</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="app_dev_II.css" rel="stylesheet" type="text/css">
<link href="admin.css" rel="stylesheet" type="text/css">
<link href="blog_II.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="40" align="center" class="cts18Bold"><?php print $blogName; ?> 
      - Edit and Delete Links</td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr bgcolor="#EEEEEE"> 
          <td width="30%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink">Back</a> </td>
          <td align="center"><a href="<?php print $helpLink; ?>" target="_blank" class="galleryUILink">Help</a> 
          </td>
          <td width="30%" height="40" align="right"> 
          </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="20">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10" align="center" class="admin18BoldRed"> <table width="360" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="360" align="left" valign="top"> 
            <?php

			for($i=0;$i<$numberOfLinks;$i++)
				{
				print BlogLinkGetPickerItem($i,$blTable,$blPosterID,$systemPassword);
				}
				
			if($numberOfLinks==0)
				print "You Have No Links to Edit or Delete...";	
	
			?>
          </td>
        </tr>
      </table>
      
    </td>
  </tr>
  <tr> 
    <td height="20" align="center">&nbsp; </td>
  </tr>
  <tr> 
    <td height="40" bgcolor="#eeeeee">
      <?php
PrintUserMessage($systemUserID);
?>
    </td>
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
<?php
DisplayStatusMessage($statusMessage);
?>