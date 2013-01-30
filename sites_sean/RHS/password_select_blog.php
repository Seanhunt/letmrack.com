<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// blog_home.php
//
// This is the entry point of the user module for the gallery admin section
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

$debug		=	0;
$lhsText	=	"Log Out";		  
$lhsLink	=	"../pw_login.php";		  

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Select Diary or Gallery</title>
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
<p align="center" class="admin18Bold">Select Diary or Gallery</p>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40"> &nbsp;&nbsp;&lt; <a href="<?php print $lhsLink; ?>" class="galleryUILink" > 
            <?php print $lhsText; ?></a> </td>
          <td width="33%" align="center"><a href="blog_help.php#BlogSelector" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="15%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="3%">&nbsp;</td>
          <td width="44%"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="30" align="left" class="admin15Bold">Diaries</td>
              </tr>
              
              <tr> 
                <td>
  <?php
// spin out the blog tables here
$query = "SELECT * FROM APPDEV_UserPermissions WHERE pwUserID='$systemUserID' AND pwPermission LIKE 'APPDEV_BLOG_%'";

if($debug) print "query: $query<br>";

OpenDatabase();

$rs				=	QueryDatabase($query);	

$numberOfBlogs	=	mysql_numrows($rs);

CloseDatabase();

if($debug) print "numberOfBlogs: $numberOfBlogs<br>";

$galleryRowTemplate	=	'
                  <table width="100%" border="0">
                    <tr>
                      <td width="24" align="center" valign="middle">
					  <img src="images/login_scroll_icons/camera.gif" width="16" height="16"></td>
                      <td align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
					  <a href="#" target="_blank" class="boldLink14">
					  _GALLERY_
					  </a></td>
                    </tr>
                  </table>
						';



for($i=0;$i<$numberOfBlogs;$i++)
	{

	$tableID	=	GetDatabaseResult($rs,$i,"pwPermission");

	if($debug) print "tableID: $tableID<br>";
	
	$blogName	=	BlogHeaderGetName($tableID);
	
	$targetURL	=	"blog_home.php?blTable=$tableID&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	
//	print '<a target="_blank" class="boldLink14" href="' . $targetURL . '">' . $blogName . "</a><br>";
	$blogRowTemplate	=	'
					  <table width="100%" border="0">
						<tr>
						  <td width="24" align="center" valign="middle">
						  <img src="images/login_scroll_icons/blog.gif" width="16" height="16"></td>
						  <td align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
						  <a href="' . $targetURL . '"  target="_blank" class="boldLink14">
						  ' . $blogName . ' Diary
						  </a></td>
						</tr>
					  </table>
							';


	print	$blogRowTemplate;
	}

?>                </td>
              </tr>
              <tr>
                <td height="30" align="left"><span class="admin15Bold">Galleries</span></td>
              </tr>
              
              <tr>
                <td><?php
// spin out the gallery tables here

$query = "SELECT * FROM APPDEV_UserPermissions WHERE pwUserID='$systemUserID' AND pwPermission LIKE 'APPDEV_GALLERY_%'";

if($debug) print "query: $query<br>";

OpenDatabase();

$rs				=	QueryDatabase($query);	

$numberOfGalleries	=	mysql_numrows($rs);

CloseDatabase();

if($debug) print "numberOfGalleries: $numberOfGalleries<br>";
if($debug) print "systemUserID: $systemUserID<br>";
if($debug) print "systemPassword: $systemPassword<br>";

for($i=0;$i<$numberOfGalleries;$i++)
	{
	$tableID	=	GetDatabaseResult($rs,$i,"pwPermission");

	if($debug) print "tableID: $tableID<br>";

	$artistID		=	str_replace("APPDEV_GALLERY_","",$tableID);
	
	$galleryName	=	GetFieldByComparison("APPDEV_GalleryProfiles","Title","ArtistID",$artistID);

	if($debug) print "artistID: $artistID<br>";
	
	if($debug) print "galleryName: $galleryName<br>";
	
	$targetURL	=	"gallery_home.php?gaTable=$tableID&SystemPassword=$systemPassword&SystemUserID=$systemUserID";
	
//	print '<a target="_blank" class="boldLink14" href="' . $targetURL . '">' . $galleryName . "</a><br>";
	$galleryRowTemplate	=	'
					  <table width="100%" border="0">
						<tr>
						  <td width="24" align="center" valign="middle">
						  <img src="images/login_scroll_icons/camera.gif" width="16" height="16"></td>
						  <td align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
						  <a href="' . $targetURL . '" target="_blank" class="boldLink14">
						  ' . $galleryName . ' Gallery
						  </a></td>
						</tr>
					  </table>
							';
	print $galleryRowTemplate;
	}
?></td>
              </tr>
              <tr>
                <td height="25" align="center">&nbsp;</td>
              </tr>
            </table>
            
          </td>
          <td width="38%" bgcolor="#999999">&nbsp;</td>
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
