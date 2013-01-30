<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// blog_home.php
//
// This is the entry point of the user module for the gallery admin section
//
//
// Revision 2.2.0.2
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'file_lib.php';

include 'html_lib.php';	
include 'gallery_lib.php';	
include 'pw_parms.php';	
include 'blog_parms.php';	
include 'status_message.php';	
include 'pw_lib.php';
include 'admin_lib.php';
include 'appdev_revision.php';	

$debug	=	0;

// if user is admin then table name will be supplied

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
	
	$isAdmin	=	true;
	$tableName	=	$blTable;
	
	$linksExist	=	BlogLinkTableExists($tableName);

	if($debug) print "tableName: $tableName<br>\n";
	if($debug) print "linksExist: $linksExist<br>\n";

	$lhsLink	=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";		  
	$lhsText	=	"Back";		  
	}
else
	{
	$tableName	=	$blTable;
	
	if($debug) print "tableName: $tableName<br>\n";
	
	// authenticate
	if(AuthorizeBlogUser($systemUserID,$systemPassword,$tableName)==false)
		{
		print "Access denied.<br>$systemUserID is not a valid user<br>";
		exit(0);
		}
	
	$linksExist	=	BlogLinkTableExists($tableName);
	
	if($debug) print "linksExist: $linksExist<br>\n";

	$lhsLink	=	"../pw_login.php";		  
	$lhsText	=	"Log Out";		  


	if(BlogIsModerator($systemUserID,$tableName)==true)
		{
		if($debug) print "User is MODERATOR<br>\n";

		$isModerator	=	true;
		}
	else
		if($debug) print "User is NOT MODERATOR<br>\n";
	

	}	

$blogType	=	BlogHeaderGetType($tableName);
$blogName	=	BlogHeaderGetName($tableName);

if($debug) print "blogType: $blogType<br>\n";
if($debug) print "blogName: $blogName<br>\n";

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $blogName; ?> - Blog Home</title>
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
<p align="center" class="admin18Bold"><?php print $blogName; ?> - Blog Home</p>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40"> &nbsp;&nbsp;&nbsp;< <a class="boldLink14" href="#" onClick="javascript:window.close();">Close 
            Window</a></td>
          <td width="33%" align="center"><a href="blog_help.php#BlogAdminOverview" target="_blank" class="galleryUILink">Help</a></td>
          <td width="33%">&nbsp; </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="10%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="2%">&nbsp;</td>
          <td width="35%"> 
            <?php
			// the table value needs help	

			if($blogType=="LIFO" || $blogType=="ORDERED" || $blogType=="CALENDAR")
				{
				// new post and edit
				print '<p><a href="blog_edit.php?' . 
				"blMode=NewItem&blTable=$tableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword" . '" class="boldLink14">
				New Posting</a></p>';
				}
			// if LIFO type
			// show file sharing post dialog
			if($blogType=="LIFO" && ($isModerator || $isAdmin))
				{
				print '<p><a href="blog_file_upload.php?' . 
				"blMode=NewItem&blTable=$tableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword" . '" class="boldLink14">
				New File Sharing Post</a></p>';
				}

			// show music file sharing post dialog
			if($blogType=="MUSIC")
				{
				print '<p><a href="blog_music_upload.php?' . 
				"blMode=NewItem&blTable=$tableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword" . '" class="boldLink14">
				New Music Sharing Post</a></p>';
				}

			// if LIFO or MUSIC type
			// show set order dialog
			if(($blogType=="LIFO" || $blogType=="MUSIC") && ($isModerator || $isAdmin))
				{
				print '<p><a href="blog_set_order.php?' . 
				"blMode=NewItem&blTable=$tableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword" . '" class="boldLink14">
				Set Order</a></p>';
				}

			// if ORDERED type
			// show set order dialog
			if($blogType=="ORDERED")
				{
				print '<p><a href="blog_ad_set_order.php?' . 
				"blMode=NewItem&blTable=$tableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword" . 
				'" class="boldLink14">
				Set Order</a></p>';


				print '<p><a href="blog_graphic_ad_upload.php?' . 
				"blMode=NewItem&blTable=$tableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword" . 
				'" class="boldLink14">
				Upload Graphic</a></p>';
				}

			// show for all types...
			
			// edit picker
			print '<p><a href="blog_edit_picker.php?' . 
			"blPosterID=$systemUserID&blTable=$tableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword" . '" class="boldLink14">Edit and Delete Postings</a></p>';

			if($blogType!="MUSIC")
				{
			// viewer
			print '<p><a target="_blank" href="../blog.php?' . 
			"blPosterID=$systemUserID&blTable=$tableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword" . '" class="boldLink14">View All Postings</a></p>';
				}
			// ---------------------------------------------------------------------------------------------------------------------------------------
			// LINKS MANAGEMENT
			// ---------------------------------------------------------------------------------------------------------------------------------------

			if($linksExist &&($isModerator || $isAdmin))
				{
				print '<p><a href="blog_link_edit.php?' . 
				"blMode=AddLink&blTable=$tableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword" . '" class="boldLink14">Add Link</a></p>';

				print '<p><a href="blog_link_picker.php?' . 
				"blPosterID=$systemUserID&blTable=$tableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword" . '" class="boldLink14">Edit and Delete Links</a></p>';
				}


				

		  ?>
          </td>
          <td width="42%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td height="40" align="left" valign="middle" bgcolor="#EEEEEE">
      <?php
PrintUserMessage($systemUserID);
?>
    </td>
  </tr>
  <tr> 
    <td align="left" valign="top"> 
      <?php
	PrintGalleryFooter();
	?>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>
